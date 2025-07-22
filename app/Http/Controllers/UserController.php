<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLoginForm()
    {
        return view('user');
    }

    public function showDashboard()
    {
        return view('userDashboard');
    }
    public function index()
    {
        return view('user');
    }
  
    public function login(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt([
        'email' => $request->email,
        'password' => $request->password,
    ], $request->remember)) {
        // echo "success"; 
        $user = Auth::user();

        $userAccess = UserAccess::where('email', $request->email)->first();

        if ($userAccess) {
            session(['userAccess' => $userAccess]);
            return redirect()->route('userDashboard');
        } else {
            // echo "failed"; 
            // echo $userAccess->access;
            return redirect()->route('user')->with('error', 'No access found');
        }
    } else {
        // Authentication failed
        return redirect()->route('login')->with('error', 'Invalid credentials');
    }
}
    
    /**
     * Display the registration form for teacher registration.
     */
    public function showRegistrationForm()
    {
        return view('user.registerTeacher');
    }

    /**
     * Register a new teacher.
     */
    public function registerTeacher(Request $request)
    {
        $staffCode = 'STAF-' . strtoupper(uniqid());
        
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'required|string',
            'email' => 'required|email|unique:students,email',  
            'phone' => 'required|numeric|unique:students,phone',
            'birthday' => 'required|date',  
            'address' => 'required|string',
            'age' => 'required|string', 
            'username' => 'required|string', 
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
                'email' => $request->username,
                'password' => bcrypt($request->password), 
            ]);
            
            session()->flash('success', 'Teacher added successfully!');
            return redirect()->route('user.index');
        } catch (Exception $e) {
            session()->flash('error', 'An error occurred while adding the teacher.');
            return redirect()->back()->withInput(); 
        }
    }

    /**
     * Handle the login attempt.
     */
  

    /**
     * Log the user out.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('user');
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
