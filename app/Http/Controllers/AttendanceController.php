<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Attendance;
use App\Models\UserAccess;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function scan(Request $request)
    {
        try {
            if (!$request->has('qr_value')) {
                return response()->json(['message' => 'No QR code detected.'], 400);
            }
    
            $qrValue = $request->input('qr_value');
            $user = Auth::user();
    
            if (!$user) {
                return response()->json(['message' => 'Unauthorized access.'], 401);
            }
    
            $userAccess = UserAccess::where('userCode', $user->userCode)->first();
    
            if (!$userAccess) {
                Log::warning('UserAccess record not found', ['userCode' => $user->userCode]);
                return response()->json(['message' => 'User access not found.'], 403);
            }
    
            $existingAttendance = Attendance::where('userCode', $user->userCode)->where('status', 'logged_in')->first();
    
            if ($existingAttendance) {
                $existingAttendance->status = 'logged_out';
                $existingAttendance->save();
            }
    
            Attendance::create([
                'userCode' => $user->userCode,
                'roomCode' => $qrValue,
                'role' => $userAccess->access,
                'status' => 'logged_in',
            ]);
    
            return response()->json(['message' => 'Attendance logged successfully'], 200);
    
        } catch (Exception $e) {
            Log::error('Error in QR Code Scan:', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
