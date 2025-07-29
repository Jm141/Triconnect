<?php

namespace App\Http\Controllers;

use App\Models\BillingLog;
use App\Models\Family;
use App\Models\Student;
use App\Models\SubscriptionPlan;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BillingLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $billingLogs = BillingLog::with(['family', 'family.parents', 'family.students', 'subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBilling = $billingLogs->sum('amount_due');
        $pendingBilling = $billingLogs->where('status', 'pending')->sum('amount_due');
        $paidBilling = $billingLogs->where('status', 'paid')->sum('amount_due');

        return view('billing_logs.index', compact('billingLogs', 'totalBilling', 'pendingBilling', 'paidBilling'));
    }

    /**
     * Generate billing for all families automatically
     */
    public function generateAllBilling()
    {
        try {
            DB::beginTransaction();

            $families = Family::with(['students', 'parents'])->get();
            $generatedCount = 0;
            $errors = [];

            foreach ($families as $family) {
                try {
                    $billingAmount = $family->calculateBillingAmount();
                    
                    if ($billingAmount > 0) {
                        // Check if billing already exists for this month
                        $existingBilling = BillingLog::where('family_code', $family->family_code)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->first();

                        if (!$existingBilling) {
                            $subscriptionPlan = $family->subscriptionPlan();
                            
                            BillingLog::create([
                                'family_code' => $family->family_code,
                                'subscription_plan_id' => $subscriptionPlan ? $subscriptionPlan->id : null,
                                'subscription_plan' => $subscriptionPlan ? $subscriptionPlan->name : 'No Plan',
                                'base_amount' => $subscriptionPlan ? $subscriptionPlan->base_amount : 0,
                                'additional_multiplier' => $subscriptionPlan ? $subscriptionPlan->additional_multiplier : 0,
                                'amount_due' => $billingAmount,
                                'status' => 'pending',
                                'billing_date' => Carbon::now(),
                                'due_date' => Carbon::now()->addDays(30),
                                'student_count' => $family->students->count(),
                            ]);

                            $generatedCount++;
                        }
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error generating billing for family {$family->family_code}: " . $e->getMessage();
                    Log::error("Billing generation error for family {$family->family_code}: " . $e->getMessage());
                }
            }

            DB::commit();

            $message = "Successfully generated billing for {$generatedCount} families.";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return redirect()->route('billing.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Billing generation failed: " . $e->getMessage());
            return redirect()->route('billing.index')->with('error', 'Failed to generate billing: ' . $e->getMessage());
        }
    }

    /**
     * Generate billing for a specific family
     */
    public function generateFamilyBilling($familyCode)
    {
        try {
            $family = Family::with(['students', 'parents'])->where('family_code', $familyCode)->firstOrFail();
            
            $billingAmount = $family->calculateBillingAmount();
            
            if ($billingAmount <= 0) {
                return redirect()->route('billing.index')->with('error', 'No billing amount calculated for this family.');
            }

            // Check if billing already exists for this month
            $existingBilling = BillingLog::where('family_code', $familyCode)
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', Carbon::now()->month)
                ->first();

            if ($existingBilling) {
                return redirect()->route('billing.index')->with('error', 'Billing already exists for this family this month.');
            }

            $subscriptionPlan = $family->subscriptionPlan();
            
            BillingLog::create([
                'family_code' => $familyCode,
                'subscription_plan_id' => $subscriptionPlan ? $subscriptionPlan->id : null,
                'subscription_plan' => $subscriptionPlan ? $subscriptionPlan->name : 'No Plan',
                'base_amount' => $subscriptionPlan ? $subscriptionPlan->base_amount : 0,
                'additional_multiplier' => $subscriptionPlan ? $subscriptionPlan->additional_multiplier : 0,
                'amount_due' => $billingAmount,
                'status' => 'pending',
                'billing_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(30),
                'student_count' => $family->students->count(),
            ]);

            return redirect()->route('billing.index')->with('success', "Billing generated successfully for family {$family->family_name} ({$familyCode}).");

        } catch (\Exception $e) {
            Log::error("Family billing generation failed for {$familyCode}: " . $e->getMessage());
            return redirect()->route('billing.index')->with('error', 'Failed to generate billing: ' . $e->getMessage());
        }
    }

    /**
     * Mark billing as paid
     */
    public function markAsPaid($billingId)
    {
        try {
            $billingLog = BillingLog::findOrFail($billingId);
            
            $billingLog->update([
                'status' => 'paid',
                'paid_date' => Carbon::now(),
                'payment_method' => 'manual',
            ]);

            return redirect()->route('billing.index')->with('success', 'Billing marked as paid successfully.');

        } catch (\Exception $e) {
            Log::error("Failed to mark billing as paid: " . $e->getMessage());
            return redirect()->route('billing.index')->with('error', 'Failed to mark billing as paid.');
        }
    }

    /**
     * Mark billing as pending
     */
    public function markAsPending($billingId)
    {
        try {
            $billingLog = BillingLog::findOrFail($billingId);
            
            $billingLog->update([
                'status' => 'pending',
                'paid_date' => null,
                'payment_method' => null,
            ]);

            return redirect()->route('billing.index')->with('success', 'Billing marked as pending successfully.');

        } catch (\Exception $e) {
            Log::error("Failed to mark billing as pending: " . $e->getMessage());
            return redirect()->route('billing.index')->with('error', 'Failed to mark billing as pending.');
        }
    }

    /**
     * Delete billing log
     */
    public function destroy($billingId)
    {
        try {
            $billingLog = BillingLog::findOrFail($billingId);
            $billingLog->delete();

            return redirect()->route('billing.index')->with('success', 'Billing log deleted successfully.');

        } catch (\Exception $e) {
            Log::error("Failed to delete billing log: " . $e->getMessage());
            return redirect()->route('billing.index')->with('error', 'Failed to delete billing log.');
        }
    }

    /**
     * Get billing statistics
     */
    public function getBillingStats()
    {
        $stats = [
            'total_billing' => BillingLog::sum('amount_due'),
            'pending_billing' => BillingLog::where('status', 'pending')->sum('amount_due'),
            'paid_billing' => BillingLog::where('status', 'paid')->sum('amount_due'),
            'total_families' => BillingLog::distinct('family_code')->count(),
            'pending_families' => BillingLog::where('status', 'pending')->distinct('family_code')->count(),
            'paid_families' => BillingLog::where('status', 'paid')->distinct('family_code')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get overdue billing
     */
    public function getOverdueBilling()
    {
        $overdueBilling = BillingLog::where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->with(['family', 'family.parents'])
            ->get();

        return response()->json($overdueBilling);
    }

    /**
     * Export billing data
     */
    public function exportBilling(Request $request)
    {
        $query = BillingLog::with(['family', 'family.parents', 'family.students']);

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $billingLogs = $query->get();

        // Generate CSV
        $filename = 'billing_logs_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($billingLogs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Family Code',
                'Parent Name',
                'Subscription Plan',
                'Base Amount',
                'Additional Multiplier',
                'Amount Due',
                'Status',
                'Billing Date',
                'Due Date',
                'Student Count'
            ]);

            // CSV data
            foreach ($billingLogs as $log) {
                $parentName = $log->family && $log->family->parents->isNotEmpty() 
                    ? $log->family->parents->first()->fname . ' ' . $log->family->parents->first()->lname 
                    : 'N/A';

                fputcsv($file, [
                    $log->family_code,
                    $parentName,
                    $log->subscription_plan,
                    $log->base_amount,
                    $log->additional_multiplier,
                    $log->amount_due,
                    $log->status,
                    $log->billing_date,
                    $log->due_date,
                    $log->student_count ?? 0
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 