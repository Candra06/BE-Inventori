<?php

namespace App\Http\Controllers\API;

use App\Barang;
use App\Http\Controllers\Controller;
use App\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function store(Request $request)
    {
        try {
            $input['barang_id'] = $request->id_barang;
            $input['jumlah'] = $request->jumlah;
            $input['created_at'] = Carbon::now();
            Pengeluaran::create($input);
            $barang = Barang::where('id', $request->id_barang)->first();
            $stokBaru = $barang->stok - $request->jumlah;
            Barang::where('id', $request->id_barang)->update(['stok' => $stokBaru]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status_code' => 401,
                'message' => 'Failed create data'
            ]);
        }
    }
}
