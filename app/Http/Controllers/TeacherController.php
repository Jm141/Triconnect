<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserAccess;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return view('teachers.index', compact('teachers'));
    }
   

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $staffCode = 'STAF-' . strtoupper(uniqid());
        
        // Debug: Log the request data
        \Log::info('Teacher creation attempt', $request->all());
        
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'nullable|string',
            'email' => 'required|email|string',  
            'phone' => 'required|numeric',
            'birthday' => 'required|date',  
            'address' => 'required|string',
            'age' => 'required|string', 
            'password' => 'required|string',
            'access_level' => 'required|in:admin,principal,teacher', // Updated to match form options
        ]);

        // Debug: Log validation passed
        \Log::info('Validation passed for teacher creation');

        $existingTeacher = Teacher::where('firstname', $request->firstname)
        ->where('middlename', $request->middlename)
        ->where('lastname', $request->lastname)
        ->first();

        if ($existingTeacher) {
            \Log::info('Teacher already exists check failed');
            session()->flash('error', 'Teacher already exists!');
            return redirect()->back()->withInput();
        }

        $existingEmail = Teacher::where('email', $request->email)->first();
        if ($existingEmail) {
            \Log::info('Email already exists check failed');
            session()->flash('error', 'Email already exists!');
            return redirect()->back()->withInput();
        }

        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            \Log::info('Username already exists check failed');
            session()->flash('error', 'Username already exists!');
            return redirect()->back()->withInput();
        }

        $existingPhone = Teacher::where('phone', $request->phone)->first();
        if ($existingPhone) {
            \Log::info('Phone already exists check failed');
            session()->flash('error', 'Phone number already exists!');
            return redirect()->back()->withInput();
        }

        try {
            \Log::info('Attempting to create teacher');
            $teacher = new Teacher;
            $teacher->firstname = $request->firstname;
            $teacher->lastname = $request->lastname;
            $teacher->middlename = $request->middlename;
            $teacher->email = $request->email;
            $teacher->phone = $request->phone;
            $teacher->birth = $request->birthday;
            $teacher->address = $request->address;
            $teacher->age = $request->age;
            $teacher->staff_code = $staffCode;
            $teacher->status = "active";
            $teacher->save();

            User::create([
                'userCode' => $staffCode, 
                'name' => $teacher->firstname,
                'email' => $request->email,
                'password' => bcrypt($request->password), 
            ]);
            
            // Create user access with the selected access level
            UserAccess::create([
                'userCode' => $staffCode,
                'access' => $request->access_level, // Use the selected access level
                'email' => $request->email,
                'updated_at' => now(),
            ]);
            
            \Log::info('Teacher created successfully');
            session()->flash('success', 'Teacher added successfully with ' . ucfirst($request->access_level) . ' access!');
            return redirect()->route('teachers.index');
        } catch (Exception $e) {
            \Log::error('Error creating teacher: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while adding the teacher: ' . $e->getMessage());
            return redirect()->back()->withInput(); 
        }
    }
    public function show(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function edit($staff_code)
    {
        $teacher = Teacher::where('staff_code', $staff_code)->first();
    
        if (!$teacher) {
            return redirect()->route('teachers.index')->with('error', 'Teacher not found');
        }

        // Get current access level from UserAccess table
        $userAccess = UserAccess::where('userCode', $staff_code)->first();
        $currentAccess = $userAccess ? $userAccess->access : 'teacher';
    
        return view('teachers.edit', compact('teacher', 'currentAccess'));
    }
    
    public function update(Request $request)
    {
        $teacher = Teacher::where('staff_code', $request->input('staffCode'))->first();

        if (!$teacher) {
            return back()->with('error', 'Teacher not found');
        }

        // Validate access level
        $request->validate([
            'role' => 'required|in:admin,principal,teacher',
        ]);
    
        $teacher->update($request->only([
            'firstname', 'middlename', 'lastname', 'birth', 'age', 'email', 'phone', 'address'
        ]));
    
        // Update user access with the new role
        UserAccess::updateOrCreate(
            ['userCode' => $request->input('staffCode')],  
            [
                'access' => $request->input('role') . ($request->has('canupdate') && $request->input('canupdate') ? ',canupdate' : ''), 
                'updated_at' => now()
            ]
        );
    
        session()->flash('success', 'Teacher data updated successfully with ' . ucfirst($request->input('role')) . ' access!');
        return redirect()->route('teachers.index');
    }
    
    

    public function destroy(Teacher $teachers)
    {
        $teachers->delete();
        return redirect()->route('teachers.index');
    }
}

