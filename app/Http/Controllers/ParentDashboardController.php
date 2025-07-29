<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Location;
use App\Models\UserAccess;
use App\Models\Family;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ParentDashboardController extends Controller
{
    /**
     * Show parent dashboard with students and location tracking
     */
    public function index()
    {
        $user = auth()->user();
        $familyCode = $user->userCode;
        
        // Get all students under this family code
        $students = Student::where('family_code', $familyCode)->get();
        
        // Get location history for all students in this family (without reverse geocoding for performance)
        $locationHistory = Location::whereIn('userCode', $students->pluck('family_code'))
            ->orderBy('created_at', 'desc')
            ->limit(50) // Limit to last 50 records for performance
            ->get();
        
        // Don't do reverse geocoding here to prevent timeout
        // Addresses will be loaded via AJAX if needed
        
        return view('parent.dashboard', [
            'students' => $students,
            'locationHistory' => $locationHistory,
            'familyCode' => $familyCode
        ]);
    }
    
    /**
     * Get student location history
     */
    public function studentLocationHistory($studentCode)
    {
        $user = auth()->user();
        $familyCode = $user->userCode;
        
        // Verify the student belongs to this family
        $student = Student::where('family_code', $studentCode)
            ->where('family_code', $familyCode)
            ->firstOrFail();
        
        // Get location history for this specific student (without reverse geocoding for performance)
        $locationHistory = Location::where('userCode', $studentCode)
            ->orderBy('created_at', 'desc')
            ->limit(100) // Limit to last 100 records
            ->get();
        
        // Don't do reverse geocoding here to prevent timeout
        // Addresses will be loaded via AJAX if needed
        
        return view('parent.student-location', [
            'student' => $student,
            'locationHistory' => $locationHistory
        ]);
    }
    
    /**
     * Reverse geocoding using OpenStreetMap Nominatim
     */
    private function reverseGeocode($lat, $lng)
    {
        if (!$lat || !$lng) {
            return 'Location not available';
        }
        
        try {
            $response = Http::timeout(5)->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lng,
                'zoom' => 18,
                'addressdetails' => 1
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['display_name'] ?? 'Address not found';
            }
        } catch (\Exception $e) {
            // Log error if needed
        }
        
        return 'Address lookup failed';
    }
    
    /**
     * Get address for a specific location via AJAX
     */
    public function getAddress($lat, $lng)
    {
        $address = $this->reverseGeocode($lat, $lng);
        return response()->json(['address' => $address]);
    }
    
    /**
     * Get real-time location for all students in family
     */
    public function getRealTimeLocations()
    {
        $user = auth()->user();
        $familyCode = $user->userCode;
        
        $students = Student::where('family_code', $familyCode)->get();
        
        $realTimeLocations = [];
        
        foreach ($students as $student) {
            $latestLocation = Location::where('userCode', $student->family_code)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($latestLocation) {
                $realTimeLocations[] = [
                    'student' => $student,
                    'location' => $latestLocation,
                    'address' => 'Loading...', // Don't do reverse geocoding here to prevent timeout
                    'lastUpdate' => $latestLocation->created_at->diffForHumans()
                ];
            }
        }
        
        return response()->json($realTimeLocations);
    }
} 