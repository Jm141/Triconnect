<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
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
        $students = Student::with(['family','parents'])->get();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $familyCode = 'FAM-' . strtoupper(uniqid());
        
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'required|string',
            'email' => 'required|email|unique:students,email',  
            'phone' => 'required|numeric|unique:students,phone',
            'birth' => 'required|date',  
            // 'address' => 'required|string',
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
            // $student->address = $request->address;
            $student->grade_level = $request->grade; 
            $student->age = $request->age;
            $student->family_code = $familyCode;
            $student->status = "active";
            
            $student->save();
            
            session()->flash('success', 'Student added successfully!');
            return redirect()->route('students.index');
        } catch (Exception $e) {
            // echo $e;
            session()->flash('error', 'An error occurred while adding the student.');
            return redirect()->back()->withInput(); 
        }
    }
    
    public function show(Student $student)
    {
       return view('students.edit', compact('student'));
    }
    public function edit(Student $student)
    {
        // student->update($request->all());
     
        // return redirect()->route('students.index');

        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $student->update($request->all());
         User::updateorCreate([
            'userCode' => $request->input('code'),
            'name' =>$request->input('firstname'),
            'email' =>$request->input('email'),
            'password' => bcrypt($request->input('password')), 
        ]);
        $access = UserAccess::updateOrCreate(
            [
                'email' =>$request->input('email')
            ],
            [
                'userCode' =>$request->input('code'),
                'access' => 'student' ,
                'email' => $request->input('email'),
                'updated_at' => now()
            ]
        );

        session()->flash('success', 'Student data updated successfully!');
        return redirect()->route('students.index');
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
    // session()->flash('success', 'Student updated successfully!');
    return redirect()->route('students.index');
}


    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index');
    }
}

