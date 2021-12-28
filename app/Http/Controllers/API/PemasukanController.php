<?php

namespace App\Http\Controllers\API;

use App\Barang;
use App\Http\Controllers\Controller;
use App\Pemasukan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function store(Request $request)
    {
        try {
            $input['barang_id'] = $request->id_barang;
            $input['jumlah'] = $request->jumlah;
            $input['created_at'] = Carbon::now();
            Pemasukan::create($input);
            $barang = Barang::where('id', $request->id_barang)->first();
            $stokBaru = $barang->stok + $request->jumlah;
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

    public function index()
    {
        try {
            $data = Pemasukan::leftJoin('barang', 'barang.id', 'pemasukan.barang_id')
                ->select('barang.nama_barang', 'pemasukan.*')->get();
            return response()->json([
                'status_code' => 200,
                'message' => 'Success',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status_code' => 401,
                'message' => 'Failed create data'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $dt = Pemasukan::where('id', $id)->first();
            $input['barang_id'] = $request->id_barang;
            $input['jumlah'] = $request->jumlah;
            $input['updated_at'] = Carbon::now();
            Pemasukan::where('id', $id)->update($input);
            $barang = Barang::where('id', $request->id_barang)->first();
            $tmpStok = $barang->stok - $dt->jumlah;
            $stokBaru = $tmpStok + $request->jumlah;
            $t['stok_lama'] = $barang->stok;
            $t['stok_update_to'] = $request->jumlah;
            $t['stok_awal'] = $dt->jumlah;
            $t['jadi'] = $stokBaru;
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
