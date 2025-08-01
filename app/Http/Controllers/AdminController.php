<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Parents;
use App\Models\Family;
use App\Models\Room;
use App\Models\UserAccess;
use App\Models\BillingLog;
use App\Models\SubscriptionPlan;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function billing()
    {
        $billingLogs = BillingLog::with(['family', 'family.parents', 'family.students', 'subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBilling = $billingLogs->sum('amount_due');
        $pendingBilling = $billingLogs->where('status', 'pending')->sum('amount_due');
        $paidBilling = $billingLogs->where('status', 'paid')->sum('amount_due');
    
        return view('admins.billinglog', compact('billingLogs', 'totalBilling', 'pendingBilling', 'paidBilling'));
    }
    public function studentList(Request $request)
    {
        // $students = Student::all();
        // $students = Student::where('status', 'active')->get();
        $query = $request->input('search'); 

    $students = Student::when($query, function ($q) use ($query) {
        $q->where('firstname', 'like', '%' . $query . '%')
          ->orWhere('email', 'like', '%' . $query . '%')
          ->orWhere('lastname', 'like', '%' . $query . '%')
          ->orWhere('middlename', 'like', '%' . $query . '%')
          ->orWhere('phone', 'like', '%' . $query . '%');
    })->get();
        $students = Student::with(['parents','family'])->get();
    // return view('students.index', compact('students'));
        return view('admins.student', compact('students'));
    }

    public function subscription()
    {
        // Retrieve all the subscription plans for display in the admin view.
        $subscriptionPlans = SubscriptionPlan::all();

        return view('subscription.index', compact('subscriptionPlans'));
    }

    public function recordPayment($familyCode)
    {
        $family = Family::where('family_code', $familyCode)->first();
        
        if (!$family) {
            return redirect()->back()->with('error', 'Family not found');
        }

        $billingAmount = $family->calculateBillingAmount();

        $subscriptionPlan = $family->subscriptionPlan();
        if (!$subscriptionPlan) {
            return redirect()->back()->with('error', 'Subscription plan not found');
        }

        // Create billing log entry
        $billingLog = new BillingLog();
        $billingLog->family_code = $family->family_code;
        $billingLog->subscription_plan = $family->subscription;
        $billingLog->base_amount = $subscriptionPlan->base_amount;
        $billingLog->additional_multiplier = $subscriptionPlan->additional_multiplier;
        $billingLog->amount_due = $billingAmount;
        $billingLog->status = 'paid';
        $billingLog->billing_date = now();
        $billingLog->paid_date = now();
        $billingLog->payment_method = 'manual';
        $billingLog->student_count = $family->students()->count();
        $billingLog->save();

        // Update family status
        $family->status = 'Paid';
        $family->save();

        return redirect()->back()->with('success', "Payment recorded successfully for family {$family->family_name} ({$familyCode})!");
    }

    public function insertTeacher()
    {
        return view('admins.addTeacher');
    }
    public function insertStudent()
    {
        return view('admins.addStudent');
    }
    public function insertRoom()
    {
        return view('admins.addRooms');
    }
    public function insertFamily()
    {
        return view('admins.addFamily');
    }
    
    public function roomList()
    {
        $rooms = Room::all();
        return view('admins.room', compact('rooms'));
    }

    public function teacherList()
    {
        $teachers = Teacher::all();
        return view('admins.teacher', compact('teachers'));
    }
    public function familyList()
    {
        $families = Parents::with(['students', 'family'])->get();
    
        foreach ($families as $family) {
            $family->billing_amount = $family->family ? $family->family->calculateBillingAmount() : 0;
        }

        // Get current user's role
        $userRole = session('userAccess')->access ?? 'admin';
    
        return view('admins.family', compact('families', 'userRole'));
    }


    public function update(Request $request, $id)
    {
        
        $plan = SubscriptionPlan::findOrFail($id);

         $validatedData = $request->validate([
            'name'                  => 'required|string|max:255|unique:subscription_plans,name,' . $plan->id,
            'base_amount'           => 'required|numeric|min:0',
            'additional_multiplier' => 'required|numeric|min:0|max:1',
        ]);

      
        $plan->update($validatedData);

        return redirect()->route('subscription.index')
                         ->with('success', 'Subscription plan updated successfully.');
    }

    

    public function addStudent(Request $request)
    {
        $familyCode = 'FAM-' . strtoupper(uniqid());
        
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'required|string',
            'email' => 'required|email|unique:students,email',  
            'phone' => 'required|numeric|unique:students,phone',
            'birth' => 'required|date',  
            'address' => 'required|string',
            'age' => 'required|string', 
        ]);
        $existingStudent = Student::where('firstname', $request->firstname)
        ->where('middlename', $request->middlename)
        ->where('lastname', $request->lastname)
        ->first();

    if ($existingStudent) {
        session()->flash('error', 'Student already exists!');
        return redirect()->back()->withInput();
    }
    $existingEmail= Student::where('email',$request->email)->first();
    
  if ($existingEmail){
    session()->flash('error', 'Email already exists!');
    return redirect()->back()->withInput();
  }
  $existingPhone= Student::where('phone',$request->phone)->first();
    
  if ($existingPhone){
    session()->flash('error', 'Phone number already exists!');
    return redirect()->back()->withInput();
  }
        try {
            
            $student = new Student;
            $student->firstname = $request->firstname;
            $student->lastname = $request->lastname;
            $student->middlename = $request->middlename;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->birth = $request->birth;
            $student->address = $request->address;
            $student->grade_level = $request->grade; 
            $student->age = $request->age;
            $student->family_code = $familyCode;
            $student->status = "active";
            
            $student->save();
            
            session()->flash('success', 'Student added successfully!');
            return redirect()->route('student-list');
        } catch (Exception $e) {
            // echo $e;
            session()->flash('error', 'An error occurred while adding the student.');
            return redirect()->back()->withInput(); 
        }
    }

    public function addRoom(Request $request)
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
            return redirect()->route('roomList');
        } catch (Exception $e) {
            // echo $e;
            session()->flash('error', 'An error occurred while adding the room.');
            return redirect()->back()->withInput(); 
        }
    }
    public function addFamily(Request $request)
    {
        $familyCode = 'FAM-' . strtoupper(uniqid());
    
        $family = Family::create([
            'family_code' => $familyCode,
            'phone'=> $request->input('parent.number'),
            'status' => 'Subscribe', 
            'address' => $request->input('parent.address'), 
        ]);
        
        $parent = Parents::create([
            'fname' => $request->input('parent.fname'),
            'mname' => $request->input('parent.mname'),
            'lname' => $request->input('parent.lname'),
            'number' => $request->input('parent.number'),
            'email' => $request->input('parent.email'),
            'status' => 'active',
            'family_code' => $familyCode,
        ]);
    
        User::create([
            'userCode' => $familyCode, 
            'name' => $request->input('parent.fname'),
            'email' => $request->input('parent.email'),
            'password' => bcrypt($request->input('parent.password')), 
        ]);
    
        UserAccess::create([
            'userCode' => $familyCode,
            'access' => 'parent',
            'email' => $request->input('parent.email'),
            'updated_at' => now(),
        ]);
    
        foreach ($request->input('students') as $studentData) {
            $student = Student::create([
                'firstname' => $studentData['fname'],
                'middlename' => $studentData['mname'],
                'lastname' => $studentData['lname'],
                'birth' => $studentData['birth'],
                'email' => $studentData['email'],
                'age' => $studentData['age'],
                'grade_level' => $studentData['year'],
                'year' => $studentData['year'],
                'status' => 'active',
                'family_code' => $familyCode,
            ]);
            User::create([
                'userCode' => $familyCode, 
                'name' => $student['fname'],
                'email' => $student['email'],
                'password' => bcrypt($request->input('parent.password')), 
            ]);
    
            UserAccess::create([
                'userCode' => $familyCode,
                'access' => 'student',
                'email' => $studentData['email'],
                'updated_at' => now(),
            ]);
            User::create([
                'userCode' => $familyCode, 
                'name' => $student['fname'],
                'email' => $student['email'],
                'password' => bcrypt($request->input('parent.password')), 
            ]);
        }
    
      
            return redirect()->route('family-list')->with('success', 'Parent and students added successfully with Family Code: ' . $familyCode);
        }
        
     
    public function updateFamilyStatusByParent($parentId, $action)
    {
        $family = Family::where('family_code', $parentId)->first();
    
        if (!$family) {
            return redirect()->route('families.index')->with('error', 'No family found for this family code.');
        }
    
        if ($action == 'Subscribed' || $action == 'UnSubscribed') {
            $family->status = $action;
            $family->save();  
        }
    
        $students = Student::where('family_code', $parentId)->get();
        
        foreach ($students as $student) {
            if ($action == 'UnSubscribed') {
                $student->status = 'inactive';
                session()->flash('success', 'Student De-Activated successfully!');
            } elseif ($action == 'Subscribed') {
                
                $student->status = 'active';
                session()->flash('success', 'Student activated successfully!');
            }
            
            $student->save();
        }
    
        // Redirect with a success message
        return redirect()->route('family-list')->with('success', 'Family status updated.');
    }
    
    public function updateStatus($id, $action)
    {
        $student = Student::findOrFail($id);
    
        if ($action === 'activate') {
            $student->status = 'active'; 
            session()->flash('success', 'Student activated successfully!');
        } elseif ($action === 'deactivate') {
            session()->flash('success', 'Student Deactivated successfully!');
            $student->status = 'inactive';
        } else {
            return back()->with('error', 'Invalid action.');
        }
    
        $student->save();
        return redirect()->route('student-list');
    }
        
    public function addTeacher(Request $request)
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
            'access_level' => 'required|in:super_admin,admin,principal,teacher', // Updated access levels
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

            // Create user access with the selected access level
            UserAccess::create([
                'userCode' => $staffCode,
                'access' => $request->access_level, // Use the selected access level
                'email' => $request->email,
                'updated_at' => now(),
            ]);
            
            session()->flash('success', 'Teacher added successfully with ' . ucfirst($request->access_level) . ' access!');
            return redirect()->route('teacher-list');
        } catch (Exception $e) {
            session()->flash('error', 'An error occurred while adding the teacher.');
            return redirect()->back()->withInput(); 
        }
    }

    public function showTeacher(Teacher $teacher)
    {
        return view('admins.editTeacher', compact('teacher'));
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
