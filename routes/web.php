<?php

use App\Models\TaskTimeTracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectCommentController;
use App\Http\Controllers\TaskTimeTrackingController;
use App\Http\Controllers\ProjectAttachmentController;
use App\Http\Controllers\JdTaskController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\NoteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::group(['middleware' => ['auth']], function() {

    
// });
Route::get('/forget_password', [LoginController::class, 'forget_password'])->name('forget_password');
Route::post('/send_otp', [LoginController::class, 'send_otp'])->name('send_otp');
Route::get('/verify_otp', [LoginController::class, 'verify_otp'])->name('verify_otp');
Route::post('/verify_otp', [LoginController::class, 'verify_otp_post'])->name('verify_otp_post');
Route::get('/password_reset_form', [LoginController::class, 'password_reset_form'])->name('password_reset_form');
Route::post('/change_password', [LoginController::class, 'change_password'])->name('change_password');

Auth::routes();

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('roles', RoleController::class);

// User Routes
Route::get('users', [UserController::class, 'index'])->name('users.list');
Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users/store', [UserController::class, 'store'])->name('users.store');
Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::post('users/update', [UserController::class, 'update'])->name('users.update');
Route::delete('users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('users/show/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('users/users_by_role', [UserController::class, 'users_by_role'])->name('users.by.role');
Route::get('users/department', [UserController::class, 'users_by_department'])->name('users.by.department');
Route::post('filter_by_date', [UserController::class, 'filter_by_date'])->name('users.by.filter_by_date');

// User Profile Routes
Route::get('users/profile', [UserController::class, 'profile'])->name('users.profile');
Route::post('users/profile_update', [UserController::class, 'profile_update'])->name('users.profile_update');

// Company Routes
Route::get('companies', [CompanyController::class, 'index'])->name('companies.list');
Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
Route::post('companies/store', [CompanyController::class, 'store'])->name('companies.store');
Route::get('companies/edit/{id}', [CompanyController::class, 'edit'])->name('companies.edit');
Route::post('companies/update', [CompanyController::class, 'update'])->name('companies.update');
Route::delete('companies/destroy/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');


// Task Routes
Route::get('tasks', [TaskController::class, 'index'])->name('tasks.list');
Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('tasks/store', [TaskController::class, 'store'])->name('tasks.store');
Route::get('tasks/show/{id}', [TaskController::class, 'show'])->name('tasks.show');
Route::post('tasks/update', [TaskController::class, 'update'])->name('tasks.update');
Route::get('tasks/report', [TaskController::class, 'report'])->name('tasks.report');
Route::post('tasks/export', [TaskController::class, 'export'])->name('tasks.export');
Route::post('tasks/update_deadline', [TaskController::class, 'update_task_deadline'])->name('tasks.update.deadline');
Route::delete('tasks/destroy/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

// Task Comments
Route::post('comments/store', [CommentController::class, 'store'])->name('comments.store');
Route::post('comments/store/images', [CommentController::class, 'store_images'])->name('comments.store.images');

// Task Attachments
Route::post('attachments/store', [AttachmentController::class, 'store'])->name('attachments.store');

// Task Time Tracking
Route::post('tracking/store', [TaskTimeTrackingController::class, 'store'])->name('tracking.store');


// Project Routes
Route::get('projects', [ProjectController::class, 'index'])->name('projects.list');
Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::post('projects/store', [ProjectController::class, 'store'])->name('projects.store');
Route::get('projects/edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');
Route::post('projects/update', [ProjectController::class, 'update'])->name('projects.update');
Route::delete('projects/destroy/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
Route::get('projects/show/{id}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('projects/comments/store', [ProjectCommentController::class, 'store'])->name('projects.comments.store');
Route::post('projects/attachments/store', [ProjectAttachmentController::class, 'store'])->name('projects.attachments.store');

// Department Routes
Route::get('departments', [DepartmentController::class, 'index'])->name('departments.list');
Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create');
Route::post('departments/store', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('departments/edit/{id}', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::post('departments/update', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('departments/destroy/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

// Notification Routes
Route::get('notifications/read/{id}', [NotificationController::class, 'read'])->name('notifications.read');
Route::post('notifications/read_all/', [NotificationController::class, 'read_all'])->name('notifications.read_all');
Route::get('notifications/list', [NotificationController::class, 'list'])->name('notifications.list');

// dashboard filter
Route::get('dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');

// JD Task Routes
Route::get('jd', [JdTaskController::class, 'index'])->name('jd.list');
Route::get('jd/create', [JdTaskController::class, 'create'])->name('jd.create');
Route::get('jd/edit/{id}', [JdTaskController::class, 'edit'])->name('jd.edit');
Route::post('jd/update', [JdTaskController::class, 'update'])->name('jd.update');
Route::post('jd/store', [JdTaskController::class, 'store'])->name('jd.store');
Route::delete('jd/destroy/{id}', [JdTaskController::class, 'destroy'])->name('jd.destroy');

// Cronjob
Route::get('cronJobDaily', [CronJobController::class, 'cronJobDaily']);
Route::get('cronJobWeekly', [CronJobController::class, 'cronJobWeekly']);
Route::get('cronJobMonthly', [CronJobController::class, 'cronJobMonthly']);

// Notes
Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');

// Temporary Routes Goes Here
Route::get('/assign-task', function () {
    return view('task.assign-task');
})->name('assign-task');
