<?php

namespace App\Http\Controllers;


use App\Models\Parents;
use App\Models\Student;
use App\Models\Family;
use App\Models\User;
use App\Models\UserAccess;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class UserParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
    
        $familyCode = $user->userCode; 
        $students = Student::where('family_code', $familyCode)
                    ->with(['parents'])
                    ->with(['family'])
                    ->get();
    
        return view('student.index', compact('students'));
    }

    public function student()
    {
        $user = Auth::user();
    
        $familyCode = $user->userCode; 
        $students = Student::where('family_code', $familyCode)
                    ->with(['parents'])
                    ->with(['family'])
                    ->get();
    
        return view('student.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $family = Family::where('family_code', $family_code)
        ->with(['parents', 'students']) 
        ->firstOrFail();

return view('student.create', compact('family'));
    }

    public function insertStudent(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'middlename' => 'required|string',
            'birth' => 'required|date',
            'age' => 'required|numeric',
            'grade' => 'required|string',
            'email' => 'required|string',
            'famCode' => 'required|string',
            'subscription' => 'required|string',
            'parent.password' => 'required|string',
        ]);
    
        try {
            $student = new Student;
            $student->firstname = $request->firstname;
            $student->lastname = $request->lastname;
            $student->middlename = $request->middlename;
            $student->birth = $request->birth;
            $student->email = $request->email;
            $student->age = $request->age;
            $student->grade_level = $request->grade;
            $student->family_code = $request->famCode;
            $student->status = 'active';
            $student->save();
    
            $subscriptionType = $request->subscription; 
            Family::where('family_code', $request->famCode)->update([
                'subscription' => $subscriptionType,
            ]);
    
            $familyCode = $request->famCode;
            User::create([
                'userCode' => $familyCode,
                'name' => $request->firstname, 
                'email' => $student->email,    
                'password' => bcrypt($request->input('parent.password')), 
            ]);
    
            UserAccess::create([
                'userCode' => $familyCode,
                'access' => 'student', 
                'email' => $student->email, 
                'updated_at' => now(),
            ]);
    
            session()->flash('success', 'Student added successfully, subscription updated, and user accounts created!');
            return redirect()->route('student');
        } catch (Exception $e) {
            Log::error('Error while inserting student or creating user records: ' . $e->getMessage());
            session()->flash('error', 'An error occurred. Please try again.');
            return redirect()->back()->withInput();
        }
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
    public function show(string $id)
    {
        //
    }

    public function edit(string $family_code)
    {
        $family = Family::where('family_code', $family_code)
                        ->with(['parents', 'students']) 
                        ->firstOrFail();
    
        return view('student.parentProfile', compact('family'));
    }

    
    public function update(Request $request, string $family_code)
{
    DB::beginTransaction(); 

    try {
        DB::enableQueryLog();

        $family = Family::where('family_code', $family_code)->firstOrFail();

        if ($request->has('parent.address')) {
            $family->address = $request->input('parent.address');
            $family->save();
        }

        if ($request->has('parent')) {
            $parent = Parents::where('family_code', $family_code)->first();
            if ($parent) {
                $parent->fill($request->input('parent'));
                $parent->save();
            }
        }

        if ($request->has('students')) {
            foreach ($request->input('students') as $studentId => $studentData) {
                $student = Student::where('family_code', $family_code)->first();
                if ($student) {
                    $student->fill([
                        'firstname'   => $studentData['fname'] ?? $student->firstname,
                        'middlename'  => $studentData['mname'] ?? $student->middlename,
                        'lastname'    => $studentData['lname'] ?? $student->lastname,
                        'birth'       => $studentData['birth'] ?? $student->birth,
                        'age'         => $studentData['age'] ?? $student->age,
                        'grade_level' => $studentData['year'] ?? $student->grade_level,
                    ]);
                    $student->save();
                }
            }
        }

        DB::commit(); 
        // return redirect()->route('student.index');
        return view('userDashboard');
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
