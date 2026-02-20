<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Stripe\Stripe;
use Stripe\BillingPortal\Session as PortalSession;

class BillingController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index(): View
    {
        $user = Auth::user();
        $subscription = $user->subscription;
        
        $usage = [
            'projects' => $user->projects()->count(),
            'teams' => $user->teams()->count(),
        ];

        $limits = [
            'projects' => $subscription?->plan->max_projects,
            'teams' => $subscription?->plan->max_teams,
        ];

        return view('billing.index', compact('subscription', 'usage', 'limits'));
    }

    public function manageBilling()
    {
        try {
            $session = PortalSession::create([
                'customer' => Auth::user()->stripe_customer_id,
                'return_url' => route('billing.index'),
            ]);

            return redirect()->to($session->url);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function cancelSubscription()
    {
        $subscription = Auth::user()->subscription;
        
        if (!$subscription || !$subscription->stripe_subscription_id) {
            return redirect()->back()->with('error', 'Aucun abonnement actif');
        }

        try {
            \Stripe\Subscription::retrieve($subscription->stripe_subscription_id)->cancel();
            $subscription->update(['status' => 'canceled', 'cancel_at' => now()]);
            return redirect()->route('billing.index')->with('success', 'Abonnement annulé');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}