<?php

use App\Http\Controllers\Web\BarangWebController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/admin/barangs');
});

// Admin Login Routes
Route::get('/admin/login', function () {
    if (Auth::check()) {
        return redirect('/admin/barangs');
    }
    return view('admin.login');
})->name('login');

Route::post('/admin/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/barangs');
    }

    return back()->withErrors([
        'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
    ]);
});

Route::post('/admin/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/admin/login');
})->name('logout');

// Protected Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Tambahkan route POST khusus untuk update agar support file upload dengan stabil
    Route::post('barangs/{barang}/update', [BarangWebController::class, 'update'])->name('barangs.update_web');
    Route::resource('barangs', BarangWebController::class)->except(['update']);
});