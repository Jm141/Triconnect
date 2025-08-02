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
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\UserTeacherController;
use App\Http\Controllers\BillingLogController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\QrCodeController;

// Routes for regular users (admin, teacher, principal, parents) - OUTSIDE auth middleware
Route::get('/', function () {
    return view('splash');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('user', [UserController::class, 'showLoginForm'])->name('user');
Route::post('/userLogin', [UserController::class, 'login'])->name('userLogin');
Route::post('/userLogout', [UserController::class, 'logout'])->name('userLogout');

Route::get('userDashboard', [UserController::class, 'showDashboard'])->name('userDashboard');

// Attendance routes
Route::post('/attendance', [AttendanceController::class, 'scan'])->name('attendance.scan');
Route::post('/insert_location', [GeoFenceController::class, 'insertLocation']);
Route::post('/checkGeofence', [GeoFenceController::class, 'checkGeoFence']);

// Parent Dashboard Routes
Route::get('/parent/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard');
Route::get('/parent/student/{studentCode}/location', [ParentDashboardController::class, 'studentLocationHistory'])->name('parent.student.location');
Route::get('/parent/real-time-locations', [ParentDashboardController::class, 'getRealTimeLocations'])->name('parent.real-time-locations');
Route::get('/parent/address/{lat}/{lng}', [ParentDashboardController::class, 'getAddress'])->name('parent.address');

// Teacher Dashboard Routes
Route::get('/attendance-dashboard', [UserTeacherController::class, 'attendanceDashboard'])->name('attendance.dashboard');
Route::post('/attendance/historical', [UserTeacherController::class, 'getHistoricalAttendance'])->name('attendance.historical');
Route::post('/attendance/class', [UserTeacherController::class, 'getClassAttendance'])->name('attendance.class');
Route::post('/attendance/day-details', [UserTeacherController::class, 'getDayDetails'])->name('attendance.dayDetails');
Route::get('/attendance/export', [UserTeacherController::class, 'exportAttendance'])->name('attendance.export');
Route::get('/attendance/export-csv', [UserTeacherController::class, 'exportAttendanceCSV'])->name('attendance.export-csv');
Route::get('/teacher/dashboard', function () {
    return view('teacher.dashboard');
})->name('teacher.dashboard');

// Notification routes for teachers and parents
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::get('/simple-test', [App\Http\Controllers\NotificationController::class, 'simpleTest'])->name('simple-test');
    Route::get('/debug', [App\Http\Controllers\NotificationController::class, 'debug'])->name('debug');
    Route::get('/test/create', [App\Http\Controllers\NotificationController::class, 'testNotification'])->name('test-create');
    Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('unread-count');
    Route::get('/recent', [App\Http\Controllers\NotificationController::class, 'getRecentNotifications'])->name('recent');
    Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/{id}', [App\Http\Controllers\NotificationController::class, 'show'])->name('show');
    Route::post('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
});

// Teacher registration routes
Route::get('user/registerTeacher', [UserController::class, 'showRegistrationForm'])->name('user.registerTeacher');
Route::post('user/registerTeacher', [UserController::class, 'registerTeacher']);

// Admin Routes (for user admin, teacher, principal, parents)
Route::resource('admins', AdminController::class);
Route::resource('student', UserParentController::class);
Route::get('teacher-list', [AdminController::class, 'teacherList'])->name('teacher-list');
Route::get('family-list', [AdminController::class, 'familyList'])->name('family-list');
Route::get('roomList', [AdminController::class, 'roomList'])->name('roomList');
Route::get('student-list', [AdminController::class, 'studentList'])->name('student-list');
Route::get('/billing', [AdminController::class, 'billing'])->name('billing.index');
Route::post('/admin/{familyCode}/recordPayment', [AdminController::class, 'recordPayment'])->name('admin.recordPayment');

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

// Billing Management Routes
Route::get('/billing/generate-all', [BillingLogController::class, 'generateAllBilling'])->name('billing.generate-all');
Route::get('/billing/family/{familyCode}/generate', [BillingLogController::class, 'generateFamilyBilling'])->name('billing.generate-family');
Route::get('/billing/{billingId}/mark-paid', [BillingLogController::class, 'markAsPaid'])->name('billing.mark-paid');
Route::get('/billing/{billingId}/mark-pending', [BillingLogController::class, 'markAsPending'])->name('billing.mark-pending');
Route::get('/billing/{billingId}/delete', [BillingLogController::class, 'destroy'])->name('billing.delete');
Route::get('/billing/stats', [BillingLogController::class, 'getBillingStats'])->name('billing.stats');
Route::get('/billing/overdue', [BillingLogController::class, 'getOverdueBilling'])->name('billing.overdue');
Route::get('/billing/export', [BillingLogController::class, 'exportBilling'])->name('billing.export');

// Subscription Routes
Route::resource('subscription', SubscriptionController::class);
Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');

//Store GeoFence
Route::post('/save_geofence', [GeoFenceController::class, 'store']);
Route::get('/geofence', [GeoFenceController::class, 'create'])->name('geofence');
Route::post('/update-geofence', [GeoFenceController::class, 'update']);
Route::get('/get-geofences', [GeoFenceController::class, 'getGeofences']);
Route::get('/debug-geofences', [GeoFenceController::class, 'debugGeofences']);

// Profile Routes
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//update status
Route::post('/students/{id}/{action}', [StudentController::class, 'updateStatus'])->name('students.updateStatus');
//Search student
Route::get('/students', [StudentController::class, 'index'])->name('students.index');

Route::post('/families/updateStatus/{parentId}/{action}', [FamilyController::class, 'updateFamilyStatusByParent'])->name('families.updateStatusByParent');
Route::post('/families/{familyCode}/recordPayment', [FamilyController::class, 'recordPayment'])->name('families.recordPayment');
Route::get('/rooms/qr/{room_code}', [RoomController::class, 'generateQr'])->name('rooms.qr');
//Billing Logs
Route::get('/billing-logs', [SubscriptionController::class, 'billingLogsIndex'])->name('billing_logs.index');

//Schedule Routes
Route::resource('schedules', ScheduleController::class);
Route::get('/schedules/weekly', [ScheduleController::class, 'weeklyView'])->name('schedules.weekly');
Route::post('/schedules/{schedule}/toggle-status', [ScheduleController::class, 'toggleStatus'])->name('schedules.toggle-status');

//QR Code Routes
Route::get('/qr/generate', [QrCodeController::class, 'generateQr'])->name('qr.generate');
Route::get('/qr/generate/teacher', [QrCodeController::class, 'generateTeacherQr'])->name('qr.generate.teacher');
Route::get('/qr/generate/{schedule}', [QrCodeController::class, 'generateScheduleQr'])->name('qr.generate.schedule');

// Alternative QR Generation Routes
Route::get('/qr/advanced', [QrCodeController::class, 'generateAdvancedQr'])->name('qr.advanced');
Route::post('/qr/custom', [QrCodeController::class, 'generateCustomQr'])->name('qr.custom');
Route::get('/qr/quick', [QrCodeController::class, 'generateQuickQr'])->name('qr.quick');

Route::post('/qr/scan', [QrCodeController::class, 'scanQr'])->name('qr.scan');
Route::get('/qr/history', [QrCodeController::class, 'attendanceHistory'])->name('qr.history');

// Principal Routes
Route::prefix('principal')->name('principal.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\PrincipalController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [App\Http\Controllers\PrincipalController::class, 'students'])->name('students');
    Route::get('/teachers', [App\Http\Controllers\PrincipalController::class, 'teachers'])->name('teachers');
    Route::get('/schedules', [App\Http\Controllers\PrincipalController::class, 'schedules'])->name('schedules');
    Route::get('/notifications', [App\Http\Controllers\PrincipalController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/create', [App\Http\Controllers\PrincipalController::class, 'createNotification'])->name('notifications.create');
    Route::post('/notifications', [App\Http\Controllers\PrincipalController::class, 'storeNotification'])->name('notifications.store');
    Route::get('/notifications/{id}', [App\Http\Controllers\PrincipalController::class, 'viewNotification'])->name('notifications.view');
    Route::delete('/notifications/{id}', [App\Http\Controllers\PrincipalController::class, 'deleteNotification'])->name('notifications.delete');
    Route::get('/stats/students', [App\Http\Controllers\PrincipalController::class, 'studentStats'])->name('stats.students');
    Route::get('/stats/teachers', [App\Http\Controllers\PrincipalController::class, 'teacherStats'])->name('stats.teachers');
});

// Super Admin Routes - INSIDE auth middleware
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/parents/{family_code}/edit', [ParentsController::class, 'edit'])->name('parents.edit');
    Route::get('/teachers/edit/{staff_code}', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::post('/give-access', [ParentsController::class, 'giveAccess'])->name('give.access');
});

require __DIR__.'/auth.php';
