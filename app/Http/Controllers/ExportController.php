<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportStruk(Transaksi $checkout)
    {
        $checkout->load([
            'user',
            'pemesanans.menu',
            'pembayaran'
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.struk', [
            'transaksi' => $checkout,
            'pemesanans' => $checkout->pemesanans,
            'pembayaran' => $checkout->pembayaran,
            'user' => $checkout->user
        ]);

        return $pdf->download('struk_transaksi_' . $checkout->id . '.pdf');
    }
}
