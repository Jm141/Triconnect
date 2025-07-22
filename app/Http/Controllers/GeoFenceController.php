<?php

namespace App\Http\Controllers;

use App\Models\GeoFence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Location;
class GeoFenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalGeofences = GeoFence::count();
        $circleCount = GeoFence::where('type', 'circle')->count();
        $rectangleCount = GeoFence::where('type', 'rectangle')->count();
        return view('geofences.index', compact('totalGeofences', 'circleCount', 'rectangleCount'));
    }

    public function getGeofences()
{
    $geofences = Geofence::all();
    return response()->json($geofences);
}


    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('geofences.create', );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string|in:circle,rectangle',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'radius' => 'nullable|numeric',
            'swLat' => 'nullable|numeric',
            'swLng' => 'nullable|numeric',
            'neLat' => 'nullable|numeric',
            'neLng' => 'nullable|numeric'
        ]);

        $geofence = Geofence::where('name', $data['name'])->first();

        if ($geofence) {
            $geofence->update($data);
            return response()->json(['message' => 'Geofence updated successfully.']);
        } else {
            Geofence::create($data);
            return response()->json(['message' => 'Geofence saved successfully.']);
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:geofences,id',
            'type' => 'required|string',
        ]);

        $geofence = Geofence::findOrFail($request->id);

        if ($request->type === 'circle') {
            $geofence->lat = $request->lat;
            $geofence->lng = $request->lng;
            $geofence->radius = $request->radius;
        } elseif ($request->type === 'rectangle') {
            $geofence->swLat = $request->swLat;
            $geofence->swLng = $request->swLng;
            $geofence->neLat = $request->neLat;
            $geofence->neLng = $request->neLng;
        }

        $geofence->save();

        return response()->json(['message' => 'Geofence updated successfully']);
    }

    public function insertLocation(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            Log::error('User not authenticated in insertLocation');
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        Log::info('Request received for insertLocation', [
            'user' => $user->userCode,
            'email' =>$user->email,
            'request_data' => $request->all()
        ]);

        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $location = Location::create([
                'userCode' => $user->userCode,
                'email' => $user->email,
                'lat' => $validatedData['latitude'],
                'lng' => $validatedData['longitude'],
            ]);

            Log::info('Location saved successfully', [
                'userCode' => $user->userCode,
                'email' => $user->email,
                'lat' => $validatedData['latitude'],
                'lng' => $validatedData['longitude'],
            ]);

            return response()->json(['message' => 'Location saved successfully']);
        } catch (\Exception $e) {
            Log::error('Error saving location: ' . $e->getMessage(), [
                'userCode' => $user->userCode,
                'email' => $user->email,
                'lat' => $validatedData['latitude'],
                'lng' => $validatedData['longitude'],
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function checkGeoFence(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        $geofences = GeoFence::all();

        $isInside = false;

        foreach ($geofences as $geofence) {
            if ($geofence->type === "circle") {
                $geofenceLat = $geofence->lat;
                $geofenceLng = $geofence->lng;
                $radius = $geofence->radius;

                $distance = $this->haversineDistance($lat, $lng, $centerLat, $centerLng);
                if ($distance <= $radius) {
                    $isInside = true;
                    break;
                }
            } elseif ($geofence->type === "rectangle") {
                if ($lat >= $geofence->sw_lat && $lat <= $geofence->ne_lat &&
                    $lng >= $geofence->sw_lng && $lng <= $geofence->ne_lng) {
                    $isInside = true;
                    break;
                }
            }
        }

        return response()->json([
            // "message" => $isInside ? "You are safe inside the fence." : "Alert! You are outside the fence."
        ]);
    }

    private function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // Earth's radius in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
    /**
     * Display the specified resource.
     */
    public function show(GeoFence $geoFence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeoFence $geoFence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, GeoFence $geoFence)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeoFence $geoFence)
    {
        //
    }
}
