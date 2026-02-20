<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LandingController;

use App\Http\Controllers\Users\JobSeekerController;
use App\Http\Controllers\Users\ApplicationController as UserApplicationController;
use App\Http\Controllers\Users\JobController as UserJobController;
use App\Http\Controllers\Users\InterviewController as UsersInterviewController;
use App\Http\Controllers\Users\NotificationController as UserNotificationController;


use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

use App\Http\Controllers\Employer\NotificationController as EmployerNotificationController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;
use App\Http\Controllers\Employer\InterviewController;







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






// redirects to specific dashboard based on the role of the user 
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware(['auth'])->group(function () {
    // Account Settings
    Route::get('/account/settings', [ProfileController::class, 'accountSettings'])->name('account.settings');
    Route::put('/account/update-email', [ProfileController::class, 'updateEmail'])->name('account.update.email');
    Route::put('/account/update-password', [ProfileController::class, 'updatePassword'])->name('account.update.password');
    Route::delete('/account/delete', [ProfileController::class, 'deleteAccount'])->name('account.delete');

});

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');


// admin routes here 
Route::
        namespace('App\Http\Controllers\Admin')->prefix('admin')->name('admin.')->middleware('can:admin-access')->group(function () {

            // add routes here for admin 
            Route::resource('users', UserController::class)->except(['create', 'store', 'destroy']);

            // Custom route (fix controller method syntax)
            Route::get('/userfeedbacks', [UserController::class, 'userFeedback'])->name('userFeedback');

            Route::get('/users/{user}', [UserController::class, 'viewUser'])->name('users.view');

            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
                Route::get('/{id}/mark-read', [AdminNotificationController::class, 'markAsRead'])->name('mark-read');
                Route::post('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
                Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
                Route::delete('/clear-read', [AdminNotificationController::class, 'clearRead'])->name('clear-read');

                // AJAX endpoints
                Route::get('/unread-count', [AdminNotificationController::class, 'getUnreadCount'])->name('unread-count');
                Route::get('/latest', [AdminNotificationController::class, 'getLatest'])->name('latest');

            });

        });





// users routes here 
Route::
        namespace('App\Http\Controllers\Users')->prefix('users')->name('users.')->middleware('can:user-access')->group(function () {

            // add routes here for users 
            Route::resource('/feedback', 'UserCTRLFeedbacks', ['except' => ['update', 'edit', 'destroy']]);

            Route::get('/myfeedbacks', 'UserCTRLFeedbacks@myfeedback')->name('myfeedback');

            Route::get('/job-seeker/dashboard', [UserApplicationController::class, 'dashboard'])
                ->name('jobseeker.dashboard');

            Route::get('/jobs', [UserJobController::class, 'index'])->name('jobs.index');
            Route::get('/jobs/{job}', [UserJobController::class, 'show'])->name('jobs.show');

            Route::get('user/profile', [JobSeekerController::class, 'userEditProfile'])->name('profile.edit');

            Route::put('/jobseeker/profile', [JobSeekerController::class, 'updateProfile'])
                ->name('jobseeker.profile.update');

            // Delete resume
            Route::delete('/resume/delete', [JobSeekerController::class, 'deleteResume'])
                ->name('resume.delete');


            // Download resume with original filename
            Route::get('/resume/download', [JobSeekerController::class, 'downloadResume'])
                ->name('resume.download');

            Route::post('/jobs/{job}/apply', [UserApplicationController::class, 'store'])
                ->name('jobs.apply');

            Route::get('/jobs/{job}/check-reapply', [UserApplicationController::class, 'checkReapply'])
                ->name('jobs.check-reapply');

            Route::get('/my-applications', [UserApplicationController::class, 'trackApplications'])->name('applications');

            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [UserNotificationController::class, 'index'])->name('index');
                Route::get('/{id}/mark-read', [UserNotificationController::class, 'markAsRead'])->name('mark-read');
                Route::post('/mark-all-read', [UserNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
                Route::delete('/{id}', [UserNotificationController::class, 'destroy'])->name('destroy');
                Route::delete('/clear-read', [UserNotificationController::class, 'clearRead'])->name('clear-read');

                // AJAX endpoints
                Route::get('/unread-count', [UserNotificationController::class, 'getUnreadCount'])->name('unread-count');
                Route::get('/latest', [UserNotificationController::class, 'getLatest'])->name('latest');

            });

            Route::get('/interviews/{interview}', [UsersInterviewController::class, 'show'])
                ->name('interviews.show');
            Route::get('/interviews', [UsersInterviewController::class, 'index'])
                ->name('interviews.index');



        });


Route::
        namespace('App\Http\Controllers\Emplpoyer')->prefix('employer')->name('employer.')->middleware('can:emplpoyer-access')->middleware('auth')->group(function () {

            // add routes here for users 
            Route::resource('/feedback', 'CTRLFeedbacks', ['except' => ['update', 'edit', 'destroy']]);

            Route::get('/myfeedbacks', 'CTRLFeedbacks@myfeedback')->name('myfeedback');

            Route::get('/job-seeker/dashboard', [EmployerApplicationController::class, 'dashboard'])
                ->name('jobseeker.dashboard');

            // Accept / Reject applicant
            Route::post('/applications/{application}/update-status', [EmployerApplicationController::class, 'updateStatus'])
                ->name('applications.updateStatus');

            Route::get('/jobs/{job}/edit', [EmployerJobController::class, 'edit'])
                ->name('jobs.edit');

            Route::put('/jobs/{job}', [EmployerJobController::class, 'update'])
                ->name('jobs.update');

            Route::delete('/jobs/{job}', [EmployerJobController::class, 'destroy'])
                ->name('jobs.destroy');

            Route::get('/jobs/{job}/applicants', [EmployerJobController::class, 'applicants'])
                ->name('jobs.applicants');

            Route::get('/jobs/create', [EmployerJobController::class, 'create'])
                ->name('jobs.create');

            Route::post('/jobs', [EmployerJobController::class, 'store'])
                ->name('jobs.store');

            Route::get('/templates/{template}/view', [EmployerJobController::class, 'viewTemplate'])
                ->name('templates.view');


            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [EmployerNotificationController::class, 'index'])->name('index');
                Route::get('/{id}/mark-read', [EmployerNotificationController::class, 'markAsRead'])->name('mark-read');
                Route::post('/mark-all-read', [EmployerNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
                Route::delete('/{id}', [EmployerNotificationController::class, 'destroy'])->name('destroy');
                Route::delete('/clear-read', [EmployerNotificationController::class, 'clearRead'])->name('clear-read');

                // AJAX endpoints
                Route::get('/unread-count', [EmployerNotificationController::class, 'getUnreadCount'])->name('unread-count');
                Route::get('/latest', [EmployerNotificationController::class, 'getLatest'])->name('latest');
            });

            Route::post('/applications/{application}/schedule-interview', [InterviewController::class, 'scheduleInterview'])
                ->name('interviews.schedule');
            Route::post('/interviews/{interview}/status', [InterviewController::class, 'updateInterviewStatus'])
                ->name('interviews.updateStatus');
            Route::delete('/interviews/{interview}/cancel', [InterviewController::class, 'cancelInterview'])
                ->name('interviews.cancel');
        });

















require __DIR__ . '/auth.php';
