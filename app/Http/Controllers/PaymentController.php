<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function showPlans(): View
    {
        $plans = Plan::query()
            ->orderBy('id')
            ->get()
            ->groupBy('name')
            ->map(function ($plansByName) {
                return $plansByName->first(function (Plan $plan) {
                    if ((int) $plan->price <= 0) {
                        return true;
                    }

                    $priceId = trim((string) $plan->stripe_price_id);
                    if ($priceId === '') {
                        return false;
                    }

                    return ! str_contains($priceId, 'xxxxx');
                }) ?? $plansByName->first();
            })
            ->sortBy('price')
            ->values();

        return view('pricing.index', compact('plans'));
    }

    public function checkout(string $planId): RedirectResponse
    {
        $plan = Plan::query()->findOrFail($planId);
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ((int) $plan->price <= 0) {
            $currentSubscription = $user->subscription;
            if (
                $currentSubscription &&
                $currentSubscription->stripe_subscription_id &&
                $currentSubscription->status !== 'canceled'
            ) {
                return redirect()->route('pricing.index')->with(
                    'info',
                    'Pour passer au plan gratuit, annulez d abord votre abonnement payant dans Stripe.'
                );
            }

            Subscription::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_id' => $plan->id,
                    'stripe_subscription_id' => null,
                    'status' => 'active',
                    'current_period_start' => now(),
                    'current_period_end' => null,
                    'cancel_at' => null,
                ]
            );

            return redirect()->route('billing.index')->with('success', 'Plan gratuit active.');
        }

        $secretKey = (string) config('services.stripe.secret');
        if ($secretKey === '') {
            return redirect()->back()->with('error', 'Configuration Stripe manquante (STRIPE_SECRET_KEY).');
        }

        try {
            Stripe::setApiKey($secretKey);

            $priceId = $this->resolveStripePriceId($plan);
            if (! $priceId) {
                return redirect()->back()->with('error', 'ID de prix Stripe invalide pour ce plan.');
            }

            $customerId = $this->ensureStripeCustomer($user);

            $session = CheckoutSession::create([
                'mode' => 'subscription',
                'customer' => $customerId,
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'success_url' => route('payment.success', ['session_id' => '{CHECKOUT_SESSION_ID}']),
                'cancel_url' => route('pricing.index'),
                'client_reference_id' => (string) $user->id,
                'metadata' => [
                    'user_id' => (string) $user->id,
                    'plan_id' => (string) $plan->id,
                ],
                'subscription_data' => [
                    'metadata' => [
                        'user_id' => (string) $user->id,
                        'plan_id' => (string) $plan->id,
                    ],
                ],
                'allow_promotion_codes' => true,
            ]);

            return redirect()->away($session->url);
        } catch (ApiErrorException $e) {
            report($e);

            return redirect()->back()->with('error', 'Erreur Stripe: ' . $e->getMessage());
        }
    }

    public function success(Request $request): View
    {
        $sessionId = (string) $request->query('session_id', '');
        $secretKey = (string) config('services.stripe.secret');

        if ($sessionId !== '' && $secretKey !== '') {
            try {
                Stripe::setApiKey($secretKey);

                $session = CheckoutSession::retrieve($sessionId, [
                    'expand' => ['subscription'],
                ]);

                $user = Auth::user();
                if (
                    $user &&
                    isset($session->client_reference_id) &&
                    ctype_digit((string) $session->client_reference_id) &&
                    (int) $session->client_reference_id !== $user->id
                ) {
                    return view('payment.success');
                }

                if (
                    $user &&
                    is_string($session->customer) &&
                    $user->stripe_customer_id &&
                    $user->stripe_customer_id !== $session->customer
                ) {
                    return view('payment.success');
                }

                if ($user && is_string($session->customer) && $user->stripe_customer_id !== $session->customer) {
                    $user->update(['stripe_customer_id' => $session->customer]);
                }

                $stripeSubscription = $session->subscription;
                if (is_string($stripeSubscription) && $stripeSubscription !== '') {
                    $stripeSubscription = StripeSubscription::retrieve($stripeSubscription);
                }

                if ($stripeSubscription && is_object($stripeSubscription)) {
                    $this->syncSubscriptionFromStripe($stripeSubscription, $user?->id);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return view('payment.success');
    }

    public function webhook(Request $request): JsonResponse
    {
        $secretKey = (string) config('services.stripe.secret');
        $webhookSecret = (string) config('services.stripe.webhook_secret');

        if ($secretKey === '' || $webhookSecret === '') {
            return response()->json(['status' => 'ignored'], 200);
        }

        try {
            Stripe::setApiKey($secretKey);

            $event = Webhook::constructEvent(
                $request->getContent(),
                (string) $request->header('Stripe-Signature'),
                $webhookSecret
            );
        } catch (SignatureVerificationException|\UnexpectedValueException $e) {
            report($e);

            return response()->json(['status' => 'invalid_signature'], 400);
        }

        try {
            switch ($event->type) {
                case 'checkout.session.completed':
                    $session = $event->data->object;
                    if (($session->mode ?? null) === 'subscription' && ! empty($session->subscription)) {
                        $userId = null;

                        if (isset($session->client_reference_id) && ctype_digit((string) $session->client_reference_id)) {
                            $userId = (int) $session->client_reference_id;
                        } elseif (isset($session->metadata->user_id) && ctype_digit((string) $session->metadata->user_id)) {
                            $userId = (int) $session->metadata->user_id;
                        }

                        if ($userId && isset($session->customer) && is_string($session->customer)) {
                            User::query()
                                ->whereKey($userId)
                                ->whereNull('stripe_customer_id')
                                ->update(['stripe_customer_id' => $session->customer]);
                        }

                        $stripeSubscription = StripeSubscription::retrieve((string) $session->subscription);
                        $this->syncSubscriptionFromStripe($stripeSubscription, $userId);
                    }
                    break;

                case 'customer.subscription.created':
                case 'customer.subscription.updated':
                case 'customer.subscription.deleted':
                    $this->syncSubscriptionFromStripe($event->data->object);
                    break;
            }
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['status' => 'error'], 500);
        }

        return response()->json(['status' => 'success']);
    }

    private function ensureStripeCustomer(User $user): string
    {
        if ($user->stripe_customer_id) {
            return (string) $user->stripe_customer_id;
        }

        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => (string) $user->id,
            ],
        ]);

        $user->update([
            'stripe_customer_id' => (string) $customer->id,
        ]);

        return (string) $customer->id;
    }

    private function resolveStripePriceId(Plan $plan): ?string
    {
        $configured = trim((string) $plan->stripe_price_id);
        if ($configured === '') {
            return null;
        }

        if (str_contains($configured, 'xxxxx')) {
            return null;
        }

        if (str_starts_with($configured, 'price_')) {
            return $configured;
        }

        if (str_starts_with($configured, 'prod_')) {
            $product = Product::retrieve($configured);
            $defaultPrice = $product->default_price ?? null;

            if (is_string($defaultPrice) && str_starts_with($defaultPrice, 'price_')) {
                return $defaultPrice;
            }

            if (is_object($defaultPrice) && isset($defaultPrice->id) && str_starts_with((string) $defaultPrice->id, 'price_')) {
                return (string) $defaultPrice->id;
            }
        }

        return null;
    }

    private function syncSubscriptionFromStripe(object $stripeSubscription, ?int $forcedUserId = null): void
    {
        $user = $this->resolveUserForStripeSubscription($stripeSubscription, $forcedUserId);
        if (! $user) {
            return;
        }

        if (isset($stripeSubscription->customer) && is_string($stripeSubscription->customer) && $user->stripe_customer_id !== $stripeSubscription->customer) {
            $user->update(['stripe_customer_id' => $stripeSubscription->customer]);
        }

        $plan = $this->resolvePlanForStripeSubscription($stripeSubscription);
        if (! $plan) {
            return;
        }

        Subscription::query()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan_id' => $plan->id,
                'stripe_subscription_id' => isset($stripeSubscription->id) && is_string($stripeSubscription->id) && $stripeSubscription->id !== ''
                    ? $stripeSubscription->id
                    : null,
                'status' => $this->normalizeStripeStatus((string) ($stripeSubscription->status ?? 'incomplete')),
                'current_period_start' => $this->toCarbonDate($stripeSubscription->current_period_start ?? null),
                'current_period_end' => $this->toCarbonDate($stripeSubscription->current_period_end ?? null),
                'cancel_at' => $this->toCarbonDate($stripeSubscription->cancel_at ?? null),
            ]
        );
    }

    private function resolveUserForStripeSubscription(object $stripeSubscription, ?int $forcedUserId = null): ?User
    {
        if ($forcedUserId) {
            $user = User::query()->find($forcedUserId);
            if ($user) {
                return $user;
            }
        }

        $metadataUserId = data_get($stripeSubscription, 'metadata.user_id');
        if ((is_string($metadataUserId) && ctype_digit($metadataUserId)) || is_int($metadataUserId)) {
            $user = User::query()->find((int) $metadataUserId);
            if ($user) {
                return $user;
            }
        }

        $customerId = data_get($stripeSubscription, 'customer');
        if (is_string($customerId) && $customerId !== '') {
            return User::query()->where('stripe_customer_id', $customerId)->first();
        }

        return null;
    }

    private function resolvePlanForStripeSubscription(object $stripeSubscription): ?Plan
    {
        $priceId = data_get($stripeSubscription, 'items.data.0.price.id');
        if (is_string($priceId) && $priceId !== '') {
            $plan = Plan::query()->where('stripe_price_id', $priceId)->orderByDesc('id')->first();
            if ($plan) {
                return $plan;
            }
        }

        $productId = data_get($stripeSubscription, 'items.data.0.price.product');
        if (is_string($productId) && $productId !== '') {
            $plan = Plan::query()->where('stripe_price_id', $productId)->orderByDesc('id')->first();
            if ($plan) {
                return $plan;
            }
        }

        $metadataPlanId = data_get($stripeSubscription, 'metadata.plan_id');
        if ((is_string($metadataPlanId) && ctype_digit($metadataPlanId)) || is_int($metadataPlanId)) {
            return Plan::query()->find((int) $metadataPlanId);
        }

        return null;
    }

    private function normalizeStripeStatus(string $status): string
    {
        return match ($status) {
            'active', 'canceled', 'past_due', 'incomplete' => $status,
            'trialing' => 'active',
            'unpaid', 'paused' => 'past_due',
            default => 'incomplete',
        };
    }

    private function toCarbonDate(mixed $timestamp): ?Carbon
    {
        if (! is_numeric($timestamp)) {
            return null;
        }

        return Carbon::createFromTimestamp((int) $timestamp);
    }
}
