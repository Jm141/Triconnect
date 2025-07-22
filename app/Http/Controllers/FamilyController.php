<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\Family;
use App\Models\Student;
use App\Models\BillingLog;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index()
    {
        $families = Parents::with(['students', 'family'])->get();
    
        foreach ($families as $family) {
            $family->billing_amount = $family->family ? $family->family->calculateBillingAmount() : 0;
        }
    
        return view('families.index', compact('families'));
    }

    public function updateFamilyStatusByParent($parentId, $action)
{
    $family = Family::where('family_code', $parentId)->first();

    if (!$family) {
        return redirect()->route('families.index')->with('error', 'No family found for this family code.');
    }

    if ($action == 'Subscribed' || $action == 'UnSubscribed') {
        $family->status = $action;
        $family->save();  
    }

    $students = Student::where('family_code', $parentId)->get();
    
    foreach ($students as $student) {
        if ($action == 'UnSubscribed') {
            $student->status = 'inactive';
            session()->flash('success', 'Student De-Activated successfully!');
        } elseif ($action == 'Subscribed') {
            
            $student->status = 'active';
            session()->flash('success', 'Student activated successfully!');
        }
        
        $student->save();
    }

    // Redirect with a success message
    return redirect()->route('families.index')->with('success', 'Family status updated.');
}
public function recordPayment($familyCode)
{
    $family = Family::where('family_code', $familyCode)->first();
    
    if (!$family) {
        return redirect()->back()->with('error', 'Family not found');
    }

    $billingAmount = $family->calculateBillingAmount();

    $subscriptionPlan = $family->subscriptionPlan();
    if (!$subscriptionPlan) {
        return redirect()->back()->with('error', 'Subscription plan not found');
    }

    $billingLog = new BillingLog();
    $billingLog->family_code = $family->family_code;
    $billingLog->subscription_plan = $family->subscription;
    $billingLog->base_amount = $subscriptionPlan->base_amount;
    $billingLog->additional_multiplier = $subscriptionPlan->additional_multiplier;
    $billingLog->amount_due = $billingAmount;
    $billingLog->save();

    $family->status = 'Paid';
    $family->save();

    return redirect()->back()->with('success', 'Payment recorded successfully and status updated!');
}

    
    
    
    
    
}
