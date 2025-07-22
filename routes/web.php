<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserParentController;
use App\Http\Controllers\GeoFenceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('splash');
});
Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('user', [UserController::class, 'showLoginForm'])->name('user');
Route::post('/attendance', [AttendanceController::class, 'scan'])->name('attendance.scan');
Route::post('/insert_location', [GeoFenceController::class, 'insertLocation'])->middleware('auth');
Route::post('/checkGeofence', [GeoFenceController::class, 'checkGeoFence'])->middleware('auth');

Route::get('userDashboard', [UserController::class, 'showDashboard'])->name('userDashboard');
    
Route::post('/userLogin', [UserController::class, 'login'])->name('userLogin');

Route::get('user/registerTeacher', [UserController::class, 'showRegistrationForm'])->name('user.registerTeacher');
Route::post('user/registerTeacher', [UserController::class, 'registerTeacher']);
// Route::post('register', [UserController::class, 'register']);
 
// Route::post('registerTeacher', [UserController::class, 'registerTeacher']);

// Route::resource('user', UserController::class);
Route::resource('admins', AdminController::class);
Route::resource('student', UserParentController::class);
Route::get('teacher-list', [AdminController::class, 'teacherList'])->name('teacher-list');
Route::get('family-list', [AdminController::class, 'familyList'])->name('family-list');
Route::get('roomList', [AdminController::class, 'roomList'])->name('roomList');
Route::get('student-list', [AdminController::class, 'studentList'])->name('student-list');
Route::get('subscription', [AdminController::class, 'subscription'])->name('subscription');
Route::post('/admin/{familyCode}/recordPayment', [AdminController::class, 'recordPayment'])->name('admin.recordPayment');
Route::get('/billing', [AdminController::class, 'billing'])->name('admin.billing');



Route::get('student', [UserParentController::class, 'student'])->name('student');
Route::get('/student/create/{family_code}', [UserParentController::class, 'student'])->name('student');
Route::get('edit-profile', [UserParentController::class, 'showProfileForm'])->name('student');
Route::get('/student/edit/{family_code}', [UserParentController::class, 'edit'])->name('teachers.edit');
Route::post('/insertStudentP', [UserParentController::class, 'insertStudent'])->name('insertStudentP');
  


Route::get('Add_Teacher', [AdminController::class, 'insertTeacher'])->name('Add_Teacher');
Route::get('addFamily', [AdminController::class, 'insertFamily'])->name('addFamily');
Route::get('addRooms', [AdminController::class, 'insertRoom'])->name('addRooms');
Route::get('addStudent', [AdminController::class, 'insertStudent'])->name('addStudent');

Route::post('/createTeacher', [AdminController::class, 'addTeacher'])->name('createTeacher');
Route::post('/insertFamily', [AdminController::class, 'addFamily'])->name('insertFamily');
Route::post('/insertRoom', [AdminController::class, 'addRoom'])->name('insertRoom');
Route::post('/insertStudent', [AdminController::class, 'addStudent'])->name('insertStudent');

Route::get('editTeacher/{id}', [AdminController::class, 'showTeacher'])->name('editTeacher');

Route::post('/admins/updateStatus/{parentId}/{action}', [AdminController::class, 'updateFamilyStatusByParent'])->name('admins.updateStatusByParent');

Route::post('/admins/{id}/{action}', [AdminController::class, 'updateStatus'])->name('admins.updateStatus');



Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/parents/{family_code}/edit', [ParentsController::class, 'edit'])->name('parents.edit');
    Route::get('/teachers/edit/{staff_code}', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::post('/give-access', [ParentsController::class, 'giveAccess'])->name('give.access');


    // Student Routes
    Route::resource('students', StudentController::class);

    // Parent Routes
    Route::resource('parents', ParentsController::class);

    // Teacher Routes
    Route::resource('teachers', TeacherController::class);

    //Room Routes
    Route::resource('rooms', RoomController::class);
    
    //Family Routes
    Route::resource('families', FamilyController::class);

    //GeoFences Routes
    Route::resource('geofences', GeoFenceController::class);

    //Subscription Routes
    Route::resource('subscription', SubscriptionController::class);

  
    //Store GeoFence
    Route::post('/save_geofence', [GeofenceController::class, 'store']);
  
Route::get('/geofence', [GeofenceController::class, 'create']);
Route::post('/update-geofence', [GeofenceController::class, 'update']);
Route::get('/get-geofences', [GeofenceController::class, 'getGeofences']);
    //User Routes
 

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //update status
    Route::post('/students/{id}/{action}', [StudentController::class, 'updateStatus'])->name('students.updateStatus');
    //Search student
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');

    // Route::get('/parents', [ParentsController::class, 'index'])->name('parents.index');
    Route::post('/families/updateStatus/{parentId}/{action}', [FamilyController::class, 'updateFamilyStatusByParent'])->name('families.updateStatusByParent');
    Route::post('/families/{familyCode}/recordPayment', [FamilyController::class, 'recordPayment'])->name('families.recordPayment');
    Route::get('/rooms/qr/{room_code}', [RoomController::class, 'generateQr'])->name('rooms.qr');
   //Billing Logs
    Route::get('/billing-logs', [SubscriptionController::class, 'billingLogsIndex'])->name('billing_logs.index');
});


require __DIR__.'/auth.php';
