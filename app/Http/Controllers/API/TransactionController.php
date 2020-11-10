<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // fungsi untuk mengambil data dari request berdasarkan id
    public function get(Request $request, $id)
    {
        // ambil data details transaction dengan relasi product di Transaction model
        $product = Transaction::with(['details.product'])->find($id);

        // memastikan jika data nya ada kembalikan response formatter dengan pesan success
        if($product)
            return ResponseFormatter::success($product, 'Data transaksi berhasil diambil');
        else
             return ResponseFormatter::error(null, 'Data transaksi tidak ada', 404);
    }
}
