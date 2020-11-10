<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // fungsi yang digunakan untuk mengambil data produk berdasarkan id, nama, slug
    public function all(Request $request)
    {
        // ambil data berdasarkan id, limit (membatasi data yang dipanggil dalam 1x request), name, slug
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $slug = $request->input('slug');
        $type = $request->input('type');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        // memasktikan apakah id terdapat dalam request atau tdk
        if($id)
        {
            // temukan id product di dalam model product dengan relasi galleries
            $product = Product::with('galleries')->find($id);

            // jika ditemukan kembalikan data product menggunakan response formatter dengan pesan success jika tidak kembalikan dengan pesan error
            if($product)
                return ResponseFormatter::success($product, 'Data produk berhasil diambil');
            else
                return ResponseFormatter::error(null, 'Data produk tidak ada', 404);
        }

         // memasktikan apakah slug terdapat dalam request atau tdk
         if($slug)
        {
             // temukan id product di dalam model product dengan relasi galleries dimana data tsb adalah data slug yang pertama 
            $product = Product::with('galleries')->where('slug', $slug)->first();

            // jika ditemukan kembalikan data product menggunakan response formatter dengan pesan success
            if($product)
                return ResponseFormatter::success($product, 'Data produk berhasil diambil');
            else
                return ResponseFormatter::error(null, 'Data produk tidak ada', 404);
        }

        $product = Product::with('galleries');

        // filter data product berdasarkan name
        if($name)
            $product->where('name', 'LIKE', '%' . $name . '%');

         // filter data product berdasarkan type
        if($type)
            $product->where('type', 'LIKE', '%' . $type . '%');

        // filter data product berdasarkan price dimana price tersebut adalah data yang lebih besar atau sama dengan $price_from
        if($price_from)
            $product->where('price', '>=', $price_from);
        
        // filter data product berdasarkan price dimana price tersebut adalah data yang lebih kecil atau sama dengan $price_from
        if($price_to)
            $product->where('price', '<=', $price_to);

        // kembalikan data menggunakan response formatter success 
        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data List Produk Berhasil Di Ambil'
        );
    }
}
