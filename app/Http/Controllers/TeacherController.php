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
        
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'required|string',
            'email' => 'required|email|string',  
            'phone' => 'required|numeric|string',
            'birthday' => 'required|date',  
            'address' => 'required|string',
            'age' => 'required|string', 
            // 'username' => 'required|string', 
            'password' => 'required|string', 
        ]);

        $existingTeacher = Teacher::where('firstname', $request->firstname)
        ->where('middlename', $request->middlename)
        ->where('lastname', $request->lastname)
        ->first();

        if ($existingTeacher) {
            session()->flash('error', 'Teacher already exists!');
            return redirect()->back()->withInput();
        }

        $existingEmail = Teacher::where('email', $request->email)->first();
        if ($existingEmail) {
            session()->flash('error', 'Email already exists!');
            return redirect()->back()->withInput();
        }

        $existingUser = User::where('email', $request->username)->first();
        if ($existingUser) {
            session()->flash('error', 'Username already exists!');
            return redirect()->back()->withInput();
        }

        $existingPhone = Teacher::where('phone', $request->phone)->first();
        if ($existingPhone) {
            session()->flash('error', 'Phone number already exists!');
            return redirect()->back()->withInput();
        }

        try {
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
            UserAccess::create([
                'userCode' => $staffCode,
                'access' => 'teacher',
                'email' => $request->email,
                'updated_at' => now(),
            ]);
            session()->flash('success', 'Teacher added successfully!');
            return redirect()->route('teachers.index');
        } catch (Exception $e) {
            session()->flash('error', 'An error occurred while adding the teacher.');
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
    
        return view('teachers.edit', compact('teacher'));
    }
    
    public function update(Request $request)
    {
        $teacher = Teacher::where('staff_code', $request->input('staffCode'))->first();

        if (!$teacher) {
            return back()->with('error', 'Teacher not found');
        }
    
        $teacher->update($request->only([
            'firstname', 'middlename', 'lastname', 'birth', 'age', 'email', 'phone', 'address', 'role', 'canupdate'
        ]));
    
        UserAccess::updateOrCreate(
            ['userCode' => $request->input('staffCode')],  
            [
                'access' => $request->input('role') . ($request->has('canupdate') && $request->input('canupdate') ? ',canupdate' : ''), 
                'updated_at' => now()
            ]
        );
    
        session()->flash('success', 'Teacher data updated successfully!');
        return redirect()->route('teachers.index');
    }
    
    

    public function destroy(Teacher $teachers)
    {
        $teachers->delete();
        return redirect()->route('teachers.index');
    }
}

