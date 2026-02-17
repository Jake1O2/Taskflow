<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Affiche les plans de tarification
     */
    public function showPlans()
    {
        $plans = Plan::all();
        return view('pricing.index', compact('plans'));
    }

    /**
     * Initialise le checkout Stripe
     */
    public function checkout(string $planId)
    {
        // Logique @todo: Intégrer Stripe Checkout
        return redirect()->back()->with('info', "Checkout pour le plan $planId en cours d'implémentation...");
    }

    /**
     * Gère le retour après succès du paiement
     */
    public function success()
    {
        return view('payment.success');
    }

    /**
     * Gère les webhooks Stripe
     */
    public function webhook(Request $request)
    {
        // Logique @todo: Gérer les événements Stripe
        return response()->json(['status' => 'success']);
    }
}
