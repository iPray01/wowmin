<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\TitheController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PrayerRequestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PledgeController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SmsMessageController;
use App\Http\Controllers\SmsGroupController;
use App\Http\Controllers\SmsTemplateController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Http\Request;
use App\Http\Controllers\SmsController;

Route::get('/', function () {
    return view('welcome');
});

// Static Pages
Route::middleware('auth')->group(function () {
    Route::get('/about', function () {
        return view('pages.about');
    })->name('about');

    Route::get('/contact', function () {
        return view('pages.contact');
    })->name('contact');

    Route::get('/privacy', function () {
        return view('pages.privacy');
    })->name('privacy');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
    
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])
      ->name('verification.send');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('show');
        Route::get('/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/', [UserController::class, 'update'])->name('update');
        Route::put('/password', [UserController::class, 'updatePassword'])->name('password.update');
        Route::delete('/', [UserController::class, 'destroy'])->name('destroy');
    });
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
    });

    // Services Management
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::get('/{service}', [ServiceController::class, 'show'])->name('show');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
        Route::get('/{service}/check-in', [ServiceController::class, 'checkIn'])->name('check-in');
        Route::post('/{service}/check-in', [ServiceController::class, 'processCheckIn'])->name('process-check-in');
        Route::post('/{service}/check-out/{member}', [ServiceController::class, 'processCheckOut'])->name('process-check-out');
    });

    // Member Management
    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', [MemberController::class, 'index'])->name('index');
        Route::get('/create', [MemberController::class, 'create'])->name('create');
        Route::post('/', [MemberController::class, 'store'])->name('store');
        Route::get('/{member}', [MemberController::class, 'show'])->name('show');
        Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('edit');
        Route::put('/{member}', [MemberController::class, 'update'])->name('update');
        Route::delete('/{member}', [MemberController::class, 'destroy'])->name('destroy');
    });

    // Family Management
    Route::prefix('families')->name('families.')->group(function () {
        Route::get('/', [FamilyController::class, 'index'])->name('index');
        Route::get('/create', [FamilyController::class, 'create'])->name('create');
        Route::post('/', [FamilyController::class, 'store'])->name('store');
        Route::get('/{family}', [FamilyController::class, 'show'])->name('show');
        Route::get('/{family}/edit', [FamilyController::class, 'edit'])->name('edit');
        Route::put('/{family}', [FamilyController::class, 'update'])->name('update');
        Route::delete('/{family}', [FamilyController::class, 'destroy'])->name('destroy');
    });

    // Attendance Management
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/create', [AttendanceController::class, 'create'])->name('create');
        Route::post('/', [AttendanceController::class, 'store'])->name('store');
        Route::get('/{attendance}', [AttendanceController::class, 'show'])->name('show');
        Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('edit');
        Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('update');
        Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
    });

    // Financial Management
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');

        // Tithe Management
        Route::prefix('tithe')->name('tithe.')->group(function () {
            Route::get('/', [TitheController::class, 'index'])->name('index');
            Route::get('/create', [TitheController::class, 'create'])->name('create');
            Route::post('/', [TitheController::class, 'store'])->name('store');
            Route::get('/{tithe}', [TitheController::class, 'show'])->name('show');
            Route::get('/{tithe}/edit', [TitheController::class, 'edit'])->name('edit');
            Route::put('/{tithe}', [TitheController::class, 'update'])->name('update');
            Route::delete('/{tithe}', [TitheController::class, 'destroy'])->name('destroy');
        });

        // Harvest Management
        Route::prefix('harvest')->name('harvest.')->group(function () {
            Route::get('/', [HarvestController::class, 'index'])->name('index');
            Route::get('/create', [HarvestController::class, 'create'])->name('create');
            Route::post('/', [HarvestController::class, 'store'])->name('store');
            Route::get('/{harvest}', [HarvestController::class, 'show'])->name('show');
            Route::get('/{harvest}/edit', [HarvestController::class, 'edit'])->name('edit');
            Route::put('/{harvest}', [HarvestController::class, 'update'])->name('update');
            Route::delete('/{harvest}', [HarvestController::class, 'destroy'])->name('destroy');
        });

        // Transactions Management
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::get('/create', [TransactionController::class, 'create'])->name('create');
            Route::post('/', [TransactionController::class, 'store'])->name('store');
            Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
            Route::get('/{transaction}/edit', [TransactionController::class, 'edit'])->name('edit');
            Route::put('/{transaction}', [TransactionController::class, 'update'])->name('update');
            Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
        });

        // Donations Management
        Route::prefix('donations')->name('donations.')->group(function () {
            Route::get('/', [DonationController::class, 'index'])->name('index');
            Route::get('/create', [DonationController::class, 'create'])->name('create');
            Route::post('/', [DonationController::class, 'store'])->name('store');
            Route::get('/{donation}', [DonationController::class, 'show'])->name('show');
            Route::get('/{donation}/edit', [DonationController::class, 'edit'])->name('edit');
            Route::put('/{donation}', [DonationController::class, 'update'])->name('update');
            Route::delete('/{donation}', [DonationController::class, 'destroy'])->name('destroy');
            Route::post('/{donation}/process-payment', [DonationController::class, 'processPayment'])->name('process-payment');
        });

        // Expenses Management
        Route::prefix('expenses')->name('expenses.')->group(function () {
            Route::get('/', [ExpenseController::class, 'index'])->name('index');
            Route::get('/create', [ExpenseController::class, 'create'])->name('create');
            Route::post('/', [ExpenseController::class, 'store'])->name('store');
            Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show');
            Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit');
            Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
            Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('delete');
        });

        // Pledges Management
        Route::prefix('pledges')->name('pledges.')->group(function () {
            Route::get('/', [PledgeController::class, 'index'])->name('index');
            Route::get('/create', [PledgeController::class, 'create'])->name('create');
            Route::post('/', [PledgeController::class, 'store'])->name('store');
            Route::get('/{pledge}', [PledgeController::class, 'show'])->name('show');
            Route::get('/{pledge}/edit', [PledgeController::class, 'edit'])->name('edit');
            Route::put('/{pledge}', [PledgeController::class, 'update'])->name('update');
            Route::delete('/{pledge}', [PledgeController::class, 'destroy'])->name('destroy');
            Route::get('/{pledge}/schedule', [PledgeController::class, 'paymentSchedule'])->name('schedule');
            Route::post('/{pledge}/payments', [PledgeController::class, 'recordPayment'])->name('payments.store');
            Route::delete('/{pledge}/payments/{payment}', [PledgeController::class, 'deletePayment'])->name('payments.destroy');
        });

        // Campaigns Management
        Route::prefix('campaigns')->name('campaigns.')->group(function () {
            Route::get('/', [CampaignController::class, 'index'])->name('index');
            Route::get('/create', [CampaignController::class, 'create'])->name('create');
            Route::post('/', [CampaignController::class, 'store'])->name('store');
            Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
            Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('edit');
            Route::put('/{campaign}', [CampaignController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
            Route::get('/{campaign}/progress', [CampaignController::class, 'progress'])->name('progress');
            Route::get('/dashboard', [CampaignController::class, 'dashboard'])->name('dashboard');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/generate', [ReportController::class, 'generate'])->name('generate');
            Route::post('/download', [ReportController::class, 'download'])->name('download');
        });
    });

    // Events Management
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
    });

    // Reports Management
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('/finance', [ReportController::class, 'finance'])->name('finance');
        Route::get('/members', [ReportController::class, 'members'])->name('members');
    });

    // Communication Management
    Route::prefix('communication')->name('communication.')->group(function () {
        // Messages Management
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [MessageController::class, 'index'])->name('index');
            Route::get('/create', [MessageController::class, 'create'])->name('create');
            Route::post('/', [MessageController::class, 'store'])->name('store');
            Route::get('/{message}', [MessageController::class, 'show'])->name('show');
            Route::get('/{message}/edit', [MessageController::class, 'edit'])->name('edit');
            Route::put('/{message}', [MessageController::class, 'update'])->name('update');
            Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
        });
    });

    // SMS Management
    Route::prefix('sms')->name('sms.')->group(function () {
        // Main SMS routes
        Route::get('/', [SmsMessageController::class, 'index'])->name('index');
        Route::get('/create', [SmsMessageController::class, 'create'])->name('create');
        Route::post('/', [SmsMessageController::class, 'store'])->name('store');
        Route::get('/{message}', [SmsMessageController::class, 'show'])->name('show');
        Route::post('/{message}/send', [SmsMessageController::class, 'send'])->name('send');
        Route::post('/{message}/cancel', [SmsMessageController::class, 'cancel'])->name('cancel');

        // SMS Groups
        Route::prefix('groups')->name('groups.')->group(function () {
            Route::get('/', [SmsGroupController::class, 'index'])->name('index');
            Route::get('/create', [SmsGroupController::class, 'create'])->name('create');
            Route::post('/', [SmsGroupController::class, 'store'])->name('store');
            Route::get('/{group}', [SmsGroupController::class, 'show'])->name('show');
            Route::get('/{group}/edit', [SmsGroupController::class, 'edit'])->name('edit');
            Route::put('/{group}', [SmsGroupController::class, 'update'])->name('update');
            Route::delete('/{group}', [SmsGroupController::class, 'destroy'])->name('destroy');
            Route::post('/{group}/members', [SmsGroupController::class, 'addMembers'])->name('members.add');
            Route::delete('/{group}/members', [SmsGroupController::class, 'removeMembers'])->name('members.remove');
        });

        // SMS Templates
        Route::prefix('templates')->name('templates.')->group(function () {
            Route::get('/', [SmsTemplateController::class, 'index'])->name('index');
            Route::get('/create', [SmsTemplateController::class, 'create'])->name('create');
            Route::post('/', [SmsTemplateController::class, 'store'])->name('store');
            Route::get('/{template}/edit', [SmsTemplateController::class, 'edit'])->name('edit');
            Route::put('/{template}', [SmsTemplateController::class, 'update'])->name('update');
            Route::delete('/{template}', [SmsTemplateController::class, 'destroy'])->name('destroy');
            Route::get('/{template}/preview', [SmsTemplateController::class, 'preview'])->name('preview');
        });

        // SMS Messages
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [SmsMessageController::class, 'index'])->name('index');
            Route::get('/create', [SmsMessageController::class, 'create'])->name('create');
            Route::post('/', [SmsMessageController::class, 'store'])->name('store');
            Route::get('/{message}', [SmsMessageController::class, 'show'])->name('show');
            Route::post('/{message}/cancel', [SmsMessageController::class, 'cancel'])->name('cancel');
            Route::post('/{message}/send', [SmsMessageController::class, 'send'])->name('send');
            Route::get('/{message}/status', [SmsMessageController::class, 'status'])->name('status');
            Route::post('/bulk-action', [SmsMessageController::class, 'bulkAction'])->name('bulk-action');
        });
    });

    // Prayer Requests Management
    Route::prefix('prayer-requests')->name('prayer-requests.')->group(function () {
        Route::get('/', [PrayerRequestController::class, 'index'])->name('index');
        Route::get('/create', [PrayerRequestController::class, 'create'])->name('create');
        Route::post('/', [PrayerRequestController::class, 'store'])->name('store');
        Route::get('/{prayer}', [PrayerRequestController::class, 'show'])->name('show');
        Route::put('/{prayer}', [PrayerRequestController::class, 'update'])->name('update');
        Route::delete('/{prayer}', [PrayerRequestController::class, 'destroy'])->name('destroy');
    });

    // Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/create', [MessageController::class, 'create'])->name('create');
        Route::post('/', [MessageController::class, 'store'])->name('store');
        Route::get('/{message}', [MessageController::class, 'show'])->name('show');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/update', [SettingController::class, 'update'])->name('update');
    });
});

Route::post('/webhooks/twilio', [SmsController::class, 'handleTwilioWebhook'])->name('twilio.webhook');
