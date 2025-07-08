<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportStruk(Pemesanan $pemesanan)
    {
        $pemesanan->load([
            'user', 
            'menu', 
            'transaksi.pembayaran'
        ]);

        // Pastikan data dikirim sebagai associative array
        $data = [
            'pemesanan' => $pemesanan,
            'transaksi' => $pemesanan->transaksi,
            'pembayaran' => $pemesanan->transaksi->pembayaran ?? null
        ];

        $pdf = Pdf::loadView('pdf.struk', $data);
        
        return $pdf->download('struk_pemesanan_'.$pemesanan->id.'.pdf');
    }
}
