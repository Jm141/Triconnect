<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\Student;
use App\Models\Family;
use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;


class ParentsController extends Controller
{
    public function index()
    {
        $families = Parents::with(['students', 'family'])->get();
    
        foreach ($families as $family) {
            $family->billing_amount = $family->family ? $family->family->calculateBillingAmount() : 0;
        }
    
        return view('families.index', compact('families'));
    }

   
    
    public function create()
    {
        return view('parents.create');
    }

    public function edit($family_code)
    {
        $family = Family::where('family_code', $family_code)
                        ->with(['parents', 'students']) 
                        ->firstOrFail();
    
        return view('families.edit', compact('family'));
    }

    public function update(Request $request, $family_code)
{
    $family = Family::where('family_code', $family_code)->firstOrFail();

    $family->update([
        'address' => $request->input('parent.address'), 

    ]);

    if ($request->has('parent')) {
        $parent = Parents::where('family_code', $family_code)->first();
        if ($parent) {
            $parent->update($request->input('parent'));
        }
    }

    if ($request->has('students')) {
        foreach ($request->input('students') as $studentId => $studentData) {
            $student = Student::where('id', $studentId)->where('family_code', $family_code)->first();
            if ($student) {
                $student->update([
                    'firstname'   => $studentData['fname'],
                    'middlename'  => $studentData['mname'],
                    'lastname'    => $studentData['lname'],
                    'birth'       => $studentData['birth'],
                    'age'         => $studentData['age'],
                    'grade_level' => $studentData['year'],
                ]);
            }
        }
    }

    return redirect()->route('families.index', $family_code)->with('success', 'Family details updated successfully!');
}

    

public function giveAccess(Request $request)
{
    $request->validate([
        'family_code' => 'required',
        'emailP' => 'required',
        'role' => 'required'
    ]);

    $access = UserAccess::updateorCreate(
       [ 
        'email' => $request->input('emailP')
       ],
        [ 
            'userCode' => $request->input('family_code'),
            'access' => $request->input('role') . ($request->has('canupdate') ? ',canupdate' : ''),
             'email'=> $request->input('emailP'),
            'updated_at' => now()
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Access granted successfully!'
    ]);
}
    


public function store(Request $request)
{
    $familyCode = 'FAM-' . strtoupper(uniqid());
    $validated = $request->validate([
        'subscription' => 'required|string',
    ]);
    
    $subscriptionType = $request->subscription; 
    $family = Family::create([
        'family_code' => $familyCode,
        'subscription' => $subscriptionType,
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
            'name' => $studentData['fname'],
            'email' => $studentData['email'],
            'password' => bcrypt($request->input('parent.password')), 
        ]);

        UserAccess::create([
            'userCode' => $familyCode,
            'access' => 'student',
            'email' => $studentData['email'],
            'updated_at' => now(),
        ]);
    }

    return redirect()->route('parents.index')->with('success', 'Parent and students added successfully with Family Code: ' . $familyCode);
}

    


    // public function edit(Parents $parents)
    // {
    //     return view('parents.edit', compact('parents'));
    // }

    // public function update(Request $request, Parents $parents)
    // {
    //     $parents->update($request->all());
    //     return redirect()->route('parents.index');
    // }

    public function destroy(Parents $parents)
    {
        $studparentsent->delete();
        return redirect()->route('parents.index');
    }
}
