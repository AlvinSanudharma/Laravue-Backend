<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CheckoutRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(CheckoutRequest $request)
    {
        // deklarasi variabel data yang berisi semua request kecuali transaction_details
        $data = $request->except('transaction_details'); 
        //generate string dan integer untuk uuid yang ada dalam variabel data
        $data['uuid'] = 'TRX' . mt_rand(100000,99999) . mt_rand(100,999);

        // simpan data ke tabel transaction
        $transaction = Transaction::create($data);

        // fungsi looping yang mengambil request dari transaction_details 
        foreach ($request->transaction_details as $product)
        {
            // deklarasi variabel dalam bentuk array yang berisi data transaction_details
            $details[] = new TransactionDetail([
                'transactions_id' => $transaction->id,
                'products_id' => $product,
            ]);
            
            // decrement atau proses untuk mengurangi quantity atau jumlah product
            Product::find($product)->decrement('quantity');
        }

        // simpan data transaction ke dalam relasi details
        $transaction->details()->saveMany($details);

        // kembalikan data transaction menggunakan ResponseFormatter dengan pesan success
        return ResponseFormatter::success($transaction);

    }
}
