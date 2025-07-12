<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    MenuController,
    PemesananController,
    TransaksiController,
    PembayaranController,
    RiwayatTransaksiController,
    PengeluaranController,
    ExportController
};

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Menu (public untuk customer)
    Route::apiResource('menu', MenuController::class)->only(['index', 'show']);

    // Pemesanan (public untuk customer)
    Route::apiResource('pemesanan', PemesananController::class)->only(['index', 'show', 'store']);

    Route::post('/checkout', [TransaksiController::class, 'store']);

    //  Route::get('/export-struk/{checkout}', [ExportController::class, 'exportStruk']);

    // Pembayaran (public untuk customer)
    Route::apiResource('pembayaran', PembayaranController::class)->only(['index', 'show', 'store']);

    // Riwayat Transaksi (customer)
    Route::get('/riwayat-transaksi', [RiwayatTransaksiController::class, 'index']);

    // Admin-only group
    Route::middleware('role:admin')->group(function () {
        // Menu (admin)
        Route::apiResource('menu', MenuController::class)->only(['store', 'update', 'destroy']);

        // Pemesanan (admin)
        Route::apiResource('pemesanan', PemesananController::class)->only(['update', 'destroy']);

        // Pembayaran (admin)
        Route::apiResource('pembayaran', PembayaranController::class)->only(['update', 'destroy']);

        // Transaksi/Checkout (admin)
        Route::apiResource('checkout', TransaksiController::class)->only(['update', 'destroy']);


        // Pengeluaran (admin)
        Route::apiResource('pengeluaran', PengeluaranController::class);

        // Dashboard & Export
        Route::get('/dashboard-admin', [DashboardController::class, 'index']);
       
    });

    // Tambahkan rute customer-only di sini jika diperlukan di masa depan
    Route::middleware('role:customer')->group(function () {
        //
    });
});
