<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Transaksi;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportStruk(Transaksi $transaksi)
    {
        $transaksi->load([
            'user',
            'pemesanans.menu',
            'pembayaran'
        ]);

        $pdf = Pdf::loadView('pdf.struk', [
            'transaksi' => $transaksi,
            'pemesanans' => $transaksi->pemesanans,
            'pembayaran' => $transaksi->pembayaran,
            'user' => $transaksi->user
        ]);

        return $pdf->download('struk_transaksi_' . $transaksi->id . '.pdf');
    }

}
