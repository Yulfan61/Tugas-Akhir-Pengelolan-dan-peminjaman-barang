<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\CalendarController;
use App\Models\User;
use App\Notifications\NewBorrowingNotification;
use App\Models\Borrowing;
use App\Models\Location;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\EmailOtpController;
use App\Http\Controllers\BlogController;
use App\Models\Blog;


Route::get('/', function () {
    $locations = Location::with(['items' => function ($query) {
        $query->where('condition', 'Baik')->orderBy('name');
    }])->paginate(3);

    $latestBlogs = Blog::latest()->take(3)->get(); // ambil 3 artikel terbaru

    return view('welcome', compact('locations', 'latestBlogs'));
});

Route::get('/artikel/{blog:slug}', [BlogController::class, 'show'])->name('blogs.show');

// Dashboard (authenticated & verified)
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Halaman Akses Ditolak
Route::get('/akses', function () {
    return view('akses');
})->name('akses');

Route::get('/test-broadcast', function () {
    $user = auth()->user(); // login dulu
    $borrowing = \App\Models\Borrowing::latest()->first();
    $user->notify(new NewBorrowingNotification($borrowing));
    return 'Notifikasi dikirim';
});

Route::get('/otp-verify', [OTPController::class, 'showForm'])->name('otp.verify.form');
Route::post('/otp-verify', [OTPController::class, 'verify'])->name('otp.verify.submit');
Route::get('/verify-email-otp', [EmailOTPController::class, 'form'])->name('email.otp.form');
Route::post('/verify-email-otp', [EmailOTPController::class, 'verify'])->name('email.otp.verify');


// Authenticated routes
Route::middleware(['auth','update-last-seen'])->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/photo', [ProfilePhotoController::class, 'update'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfilePhotoController::class, 'destroy'])->name('profile.photo.delete');
    Route::get('/profile/verify-email-otp', [EmailOTPController::class, 'form'])->name('email.otp.form');
    Route::post('/profile/verify-email-otp', [EmailOtpController::class, 'verify'])->name('email.otp.verify');


    /**
     * ADMIN ROUTES
     * Admin memiliki akses penuh ke semua resource: categories, locations, users, items
     */
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('locations', LocationController::class);
        Route::resource('users', UserController::class);
        Route::put('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');
        Route::resource('items', ItemController::class);
        Route::get('/items/damaged', [ItemController::class, 'damaged'])->name('items.damaged');
    });

    /**
     * ITEMS ROUTES (Staff & Admin)
     */
    Route::middleware(['role:Admin|Staff'])->group(function () {
        Route::resource('blogs', BlogController::class)->except(['show']);
        Route::resource('items', ItemController::class);
        Route::get('/items/damaged', [ItemController::class, 'damaged'])->name('items.damaged');
        Route::get('/announcement/edit', [AnnouncementController::class, 'edit'])->name('announcement.edit');
        Route::post('/announcement/update', [AnnouncementController::class, 'update'])->name('announcement.update');
        
    });
    

    /**
     * BORROWINGS ROUTES (Admin, Staff, Peminjam)
     */
    Route::middleware(['role:Admin|Staff|Peminjam'])->group(function () {
        Route::get('/borrowings/history/pdf', [BorrowingController::class, 'downloadHistoryPDF'])->name('borrowings.history.pdf');
        Route::get('/borrowings/history', [BorrowingController::class, 'history'])->name('borrowings.history');
        Route::resource('borrowings', BorrowingController::class);
        Route::get('borrowings/{borrowing}/return', [BorrowingController::class, 'returnForm'])->name('borrowings.return.form');
        Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'returnProcess'])->name('borrowings.return.process');
        Route::get('/borrowings/{borrowing}/pdf', [BorrowingController::class, 'downloadPDF'])->name('borrowings.downloadPDF');
        Route::patch('/borrowings/{borrowing}/approve-return', [BorrowingController::class, 'approveReturn'])->name('borrowings.approveReturn');
        Route::post('/borrowings/{borrowing}/penalty/pay', [BorrowingController::class, 'payPenalty'])->name('borrowings.penalty.pay')->middleware(['auth']);
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    });

    /**
     * REPORT ROUTES (Admin, Staff)
     */
    Route::middleware(['role:Admin|Staff'])->group(function () {
        Route::get('/reports', [ReportController::class, 'damageReports'])->name('reports.damage_reports');
        Route::get('/reports/damage/create', [ReportController::class, 'createDamageReport'])->name('reports.create_damage_report');
        Route::post('/reports/damage', [ReportController::class, 'storeDamageReport'])->name('reports.store_damage_report');
        Route::patch('/reports/damage/{report}', [ReportController::class, 'updateDamageReport'])->name('reports.update_damage_report');
        Route::get('/reports/items/by-location/{locationId}', [ReportController::class, 'getItemsByLocation']);
        Route::patch('/reports/{id}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');
        Route::get('/reports/locations', [\App\Http\Controllers\ReportController::class, 'selectLocation'])->name('reports.select_location');
        Route::get('/reports/locations/{location}/export', [\App\Http\Controllers\ReportController::class, 'exportByLocation'])->name('reports.export_by_location');
        Route::get('/reports/damages/export', [ReportController::class, 'exportDamageReports'])->name('reports.damage_reports.export');
    });
});

// Auth routes (Breeze)
require __DIR__ . '/auth.php';
