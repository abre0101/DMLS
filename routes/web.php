<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\LoginController,
    Auth\RegisterController,
    DashboardController,
    ProfileController,
    DocumentController,
    ManagerLetterController,
    LetterController,
    LetterTemplateController,
    RolesController,
    ReportController,
    ApprovalRequestController,
    ApprovalController,
    WorkflowController,
    SettingController,
    AdminController,
    Admin\UserController,
    DirectorController,
    TaskController,
    ManagerDocumentController,
    EmployeeDashboardController,
    ManagerController,
    CustomerController,
    OrderController,
    NotificationController,
    CollaborationController 


    
};

// Public Route
Route::view('/', 'welcome')->name('home');
Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');


// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
   // Approve a document
   Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    
   // Reject a document
   Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
   Route::post('/approvals/{approval}/sign', [ApprovalController::class, 'sign'])->name('approvals.sign');
// Notification (Public)
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

// Public Document Download
Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

// Authenticated & Verified Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Collaboration Routes
    Route::prefix('collaboration')->name('collaboration.')->group(function () {
        Route::get('/', [CollaborationController::class, 'index'])->name('index');
        Route::get('/create', [CollaborationController::class, 'create'])->name('create');
        Route::post('/store', [CollaborationController::class, 'store'])->name('store');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/show', [DashboardController::class, 'showDashboard'])->name('dashboard.show');

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    // Document Routes
    Route::resource('documents', DocumentController::class);
    Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    Route::post('/documents/{document}/restore', [DocumentController::class, 'restore'])->name('documents.restore');
         Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    // Employee-specific routes
    Route::prefix('employee')->name('employee.')->group(function () {
         Route::get('/tasks/assigned-to-me', [TaskController::class, 'tasksAssignedToMe'])->name('tasks.assigned_to_me');


        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}', [EmployeeDashboardController::class, 'employeeShow'])->name('documents.show');
        Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::post('/documents/{document}/request-approval', [DocumentController::class, 'requestApproval'])->name('documents.requestApproval');
        Route::get('/documents/version/{version}/download', [EmployeeDashboardController::class, 'downloadVersion'])->name('documents.download-version');
        Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
Route::post('/documents/restore-version/{id}', [EmployeeDashboardController::class, 'restoreVersion'])
     ->name('documents.restore-version');
Route::get('/letters/{letter}/reply', [EmployeeDashboardController::class, 'replyForm'])
    ->name('letters.replyform');
Route::post('/letters/{letter}/reply', [EmployeeDashboardController::class, 'sendReply'])
    ->name('letters.reply');
        Route::get('/letters', [EmployeeDashboardController::class, 'listLetters'])->name('letters.index');
        Route::get('/letters/{letter}', [EmployeeDashboardController::class, 'showLetter'])->name('letters.show');
   
   
        Route::get('/letters/{letter}/reply/{reply}/edit', [EmployeeDashboardController::class, 'editReply'])->name('letters.reply.edit');
        Route::put('/letters/{letter}/reply/{reply}', [EmployeeDashboardController::class, 'updateReply'])->name('letters.reply.update');
        Route::get('{letter}/export-pdf', [EmployeeDashboardController::class, 'exportPDF'])->name('letters.download');
    });
    Route::prefix('manager/letters/inbox')->name('manager.letters.inbox.')->group(function () {
        Route::get('/', [ManagerLetterController::class, 'inbox'])->name('index');
        // other inbox routes if any...
    });
    
    // Approval Requests
    Route::resource('approval_requests', ApprovalController::class);
    Route::prefix('approval-requests')->name('approval-requests.')->group(function () {
        Route::get('/', [ApprovalRequestController::class, 'index'])->name('index');
        Route::get('/{approval}', [ApprovalRequestController::class, 'show'])->name('show');
        Route::post('/{approval}/approve', [ApprovalRequestController::class, 'approve'])->name('approve');
        Route::delete('/{approval}/reject', [ApprovalRequestController::class, 'reject'])->name('reject');
    });

    // Workflow Routes
    Route::resource('workflows', WorkflowController::class);
    Route::prefix('workflow')->name('workflow.')->group(function () {
        Route::get('/step/{step}/approve', [WorkflowController::class, 'showStepApprovalForm'])->name('step.form');
        Route::post('/step/{step}/approve', [WorkflowController::class, 'submitStepApproval'])->name('step.approve');
        Route::get('/steps', [WorkflowController::class, 'listSteps'])->name('steps');
        Route::get('/step_approval/{stepId}', [WorkflowController::class, 'showStepApprovalForm'])->name('step_approval');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/weekly', [ReportController::class, 'weeklyReport'])->name('weekly');
        Route::get('/monthly', [ReportController::class, 'monthlyReport'])->name('monthly');
        Route::get('/export/{format}', [ReportController::class, 'export'])->name('export');
        Route::post('/approve/{report}', [ReportController::class, 'approve'])->name('approve');
        Route::post('/reject/{report}', [ReportController::class, 'reject'])->name('reject');
        Route::get('/{report}', [ReportController::class, 'show'])->name('show');
        Route::get('/{report}/edit', [ReportController::class, 'edit'])->name('edit');
    });

    // Letters
    Route::resource('letters', LetterController::class);
    Route::post('/letters/{letter}/archive', [LetterController::class, 'archive'])->name('letters.archive');
    Route::post('/letters/generate', [LetterController::class, 'generate'])->name('letters.generate');

    // Letter Templates
    Route::resource('letter-templates', LetterTemplateController::class);

    // Roles & Users
    Route::post('/roles/create', [RolesController::class, 'createRole'])->name('roles.create');
    Route::post('/users/{user}/assign-role', [RolesController::class, 'assignRole'])->name('roles.assign');
    Route::put('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});


Route::resource('collaborations', CollaborationController::class);

Route::prefix('manager')->name('manager.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ManagerController::class, 'index'])->name('dashboard');

  Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
  Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');  // <-- Add this
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

 Route::get('/tasks/assignedme', [TaskController::class, 'assignedByMe'])->name('tasks.assignedme');


    // Templates
    Route::get('/template/{id}/content', [ManagerController::class, 'getContent'])->name('template.content');
Route::get('/reports/export', [ManagerController::class, 'export'])->name('reports.export');
    // Documents
    Route::get('/documents', [ManagerDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [ManagerDocumentController::class, 'show'])->name('documents.show');
    Route::post('/documents/{document}/approve', [ManagerDocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [ManagerDocumentController::class, 'reject'])->name('documents.reject');

    // Approvals
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/create', [ApprovalController::class, 'create'])->name('approvals.create');
    Route::post('/approvals', [ApprovalController::class, 'store'])->name('approvals.store');
 Route::get('/approvals/{id}', [ApprovalController::class, 'show'])->name('approvals.show');


    // Letters (inbox, view, compose, send)
    Route::get('/letters', [ManagerLetterController::class, 'index'])->name('letters.index');
    Route::get('/letters/create', [ManagerLetterController::class, 'create'])->name('letters.create');
    Route::get('/letters/send', [ManagerLetterController::class, 'showSendForm'])->name('letters.send.form');
    Route::post('/letters/send', [ManagerLetterController::class, 'send'])->name('letters.send');
    Route::post('/letters/store-by-email', [ManagerLetterController::class, 'storeByEmail'])->name('letters.storeByEmail');
    Route::get('/letters/{letter}', [ManagerLetterController::class, 'show'])->name('letters.show');

    // Letter templates
    Route::get('/letters/templates', [LetterTemplateController::class, 'index'])->name('letters.templates.index');
    Route::get('/letters/templates/create', [LetterTemplateController::class, 'create'])->name('letters.templates.create');
    Route::post('/letters/templates', [LetterTemplateController::class, 'store'])->name('letters.templates.store');
    Route::get('/letters/templates/{template}', [LetterTemplateController::class, 'show'])->name('letters.templates.show');
    Route::get('/letters/templates/{template}/edit', [LetterTemplateController::class, 'edit'])->name('letters.templates.edit');
    Route::delete('/letters/templates/{template}', [LetterTemplateController::class, 'destroy'])->name('letters.templates.destroy');

    // Store Letter (custom method)
    Route::post('/letters', [LetterController::class, 'storeLetter'])->name('storeLetter');
});



    Route::resource('documents', ManagerDocumentController::class)->except(['store', 'update', 'destroy']); 

    Route::prefix('letters')->name('letters.')->group(function () {
        Route::get('/', [ManagerLetterController::class, 'index'])->name('index');
        Route::get('/create', [ManagerLetterController::class, 'sendLetterForm'])->name('create');
        Route::post('/send', [ManagerLetterController::class, 'sendLetter'])->name('send');
        Route::post('/send-by-email', [ManagerLetterController::class, 'storeByEmail'])->name('sendByEmail');
        Route::get('/incoming', [ManagerLetterController::class, 'incomingLetters'])->name('incoming');
        Route::get('/sent', [ManagerLetterController::class, 'sentLetters'])->name('sent');
        Route::get('/inbox', [ManagerLetterController::class, 'inbox'])->name('inbox.index');
        Route::get('{letter}/export-pdf', [ManagerLetterController::class, 'exportPDF'])->name('download');
     
  
});

    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::get('/{approval}', [ApprovalController::class, 'show'])->name('show');
        Route::post('/{approval}/approve', [ApprovalController::class, 'approve'])->name('approve');
        Route::post('/{approval}/reject', [ApprovalController::class, 'reject'])->name('reject');
    });

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/{report}/approve', [ReportController::class, 'approve'])->name('reports.approve');
    Route::post('/reports/{report}/reject', [ReportController::class, 'reject'])->name('reports.reject');


// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
});
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

// Director Routes
Route::prefix('director')->name('director.')->group(function () {
    Route::get('/dashboard', [DirectorController::class, 'dashboard'])->name('dashboard');

    // Letters
    Route::get('/letters', [DirectorController::class, 'index'])->name('letters.index');
    Route::get('/letters/create', [DirectorController::class, 'createLetter'])->name('letters.create');
    Route::post('/letters', [DirectorController::class, 'storeLetter'])->name('letters.store');
    Route::get('/letters/{letter}', [DirectorController::class, 'showLetter'])->name('letters.show');
    Route::get('/letters/{letter}/edit', [DirectorController::class, 'editLetter'])->name('letters.edit');
    Route::put('/letters/{letter}', [DirectorController::class, 'updateLetter'])->name('letters.update');
    Route::delete('/letters/{letter}', [DirectorController::class, 'deleteLetter'])->name('letters.delete');
    Route::get('/letters/{letter}/pdf', [DirectorController::class, 'generatePdf'])->name('letters.pdf');
    Route::get('/tasks/overdue', [DirectorController::class, 'overdueTasks'])->name('tasks.overdue');


    // Documents
    Route::get('tasks/create', [DirectorController::class, 'createTask'])->name('tasks.create');
    Route::post('tasks', [DirectorController::class, 'storeTask'])->name('tasks.store');
    Route::get('/documents', [DirectorController::class, 'listDocuments'])->name('documents.index');
    Route::get('/documents/{document}', [DirectorController::class, 'showDocument'])->name('documents.show');
    Route::post('/documents/{document}/approve', [DirectorController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [DirectorController::class, 'reject'])->name('documents.reject');

    // Others
    Route::get('/departments/activity', [DirectorController::class, 'departmentActivity'])->name('departments.activity');
    Route::get('/tasks/overdue', [DirectorController::class, 'overdueTasks'])->name('tasks.overdue');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');

    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');

    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
});



Route::get('letters/{id}/export-pdf', [LetterController::class, 'exportPdf'])->name('letters.exportPdf');

