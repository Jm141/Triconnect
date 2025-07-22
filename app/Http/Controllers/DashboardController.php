<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Parents;
// use App\Models\Geofence; // Uncomment if you have a Geofence model

class DashboardController extends Controller
{
    public function index()
    {
        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $parentCount = Parents::count();

        // For the chart
        $grades = Student::select('grade_level')->distinct()->pluck('grade_level')->toArray();
        $gradeCounts = [];
        foreach ($grades as $grade) {
            $gradeCounts[] = Student::where('grade_level', $grade)->count();
        }

        // For the map (optional, if you have geofences)
        // $geofences = Geofence::all()->map(function($geo) {
        //     return [
        //         'type' => $geo->type,
        //         'lat' => $geo->lat,
        //         'lng' => $geo->lng,
        //         'radius' => $geo->radius,
        //         'swLat' => $geo->swLat,
        //         'swLng' => $geo->swLng,
        //         'neLat' => $geo->neLat,
        //         'neLng' => $geo->neLng,
        //     ];
        // });
        $geofences = [];

        return view('dashboard', compact('studentCount', 'teacherCount', 'parentCount', 'grades', 'gradeCounts', 'geofences'));
    }
} 