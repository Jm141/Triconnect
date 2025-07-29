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

    // First, find the user by email
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return redirect()->route('user')->with('error', 'User not found');
    }

    // Check password using bcrypt
    if (!password_verify($request->password, $user->password)) {
        return redirect()->route('user')->with('error', 'Invalid credentials');
    }

    // Password is correct, now authenticate the user
    Auth::login($user, $request->remember);

    // Check if this is the super admin
    if ($user->userCode === 'Admin=349262938') {
        // Super admin - redirect to main dashboard
        return redirect()->route('dashboard');
    } else {
        // For regular users, get their access from user_access_links
        $userAccess = UserAccess::where('userCode', $user->userCode)->first();

        if ($userAccess) {
            // Add the user's name to the userAccess object for easy access in views
            $userAccess->name = $user->name;
            session(['userAccess' => $userAccess]);
            
            // Redirect based on user access type
            if (strpos($userAccess->access, 'teacher') !== false) {
                return redirect()->route('teacher.dashboard');
            } elseif (strpos($userAccess->access, 'parent') !== false) {
                return redirect()->route('parent.dashboard');
            } elseif (strpos($userAccess->access, 'principal') !== false) {
                return redirect()->route('principal.dashboard');
            } else {
                // Default fallback to userDashboard
                return redirect()->route('userDashboard');
            }
        } else {
            // No access found - logout and show error
            Auth::logout();
            return redirect()->route('user')->with('error', 'No access found for this user');
        }
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
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
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
