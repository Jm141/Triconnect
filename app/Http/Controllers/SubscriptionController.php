<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\UserAccess;
use App\Models\BillingLog;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all the subscription plans for display in the admin view.
        $subscriptionPlans = SubscriptionPlan::all();

        return view('subscription.index', compact('subscriptionPlans'));
    }

    public function billingLogsIndex()
    {
        $billingLogs = BillingLog::with('family','parents')->get();
    
        return view('billing_logs.index', compact('billingLogs'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'                   => 'required|string|max:255|unique:subscription_plans,name',
            'base_amount'            => 'required|numeric|min:0',
            'additional_multiplier'  => 'required|numeric|min:0|max:1',
        ]);

        SubscriptionPlan::create($validatedData);

        return redirect()->route('subscription.index')
                         ->with('success', 'Subscription plan created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        $plan = SubscriptionPlan::findOrFail($id);

         $validatedData = $request->validate([
            'name'                  => 'required|string|max:255|unique:subscription_plans,name,' . $plan->id,
            'base_amount'           => 'required|numeric|min:0',
            'additional_multiplier' => 'required|numeric|min:0|max:1',
        ]);

      
        $plan->update($validatedData);

        return redirect()->route('subscription.index')
                         ->with('success', 'Subscription plan updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
