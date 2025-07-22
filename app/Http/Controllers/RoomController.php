<?php

namespace App\Http\Controllers;

use App\Models\Room;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('rooms.create');
    }
        

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $roomCode = 'Room-' . strtoupper(uniqid());
        
        $validated = $request->validate([
            'room' => 'required|string',
           
        ]);
       
  
  $existingPhone= Room::where('name',$request->room)->first();
    
  if ($existingPhone){
    session()->flash('error', 'Room  already exists!');
    return redirect()->back()->withInput();
  }
        try {
            
            $room = new Room;
        
            $room->name = $request->room;
            $room->room_code = $roomCode;
            $room->status = "active";
            
            $room->save();
            
            session()->flash('success', 'Room added successfully!');
            return redirect()->route('rooms.index');
        } catch (Exception $e) {
            // echo $e;
            session()->flash('error', 'An error occurred while adding the room.');
            return redirect()->back()->withInput(); 
        }
    }
    public function generateQr($room_code)
    {
        $qr = QrCode::size(200)->generate($room_code);
        return response($qr)->header('Content-Type', 'image/png');
    }
    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
    }
}
