<?php

namespace App\Http\Controllers\API;

use App\Barang;
use App\Http\Controllers\Controller;
use App\Pemasukan;
use App\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    public function index()
    {
        try {
            $barang = Barang::where('status', 'Tersedia')->orderBy('created_at', 'DESC')->get();
            $data = [];

            foreach ($barang as $b) {
                $pemasukan = Pemasukan::where('barang_id', $b->id)->orderBy('created_at', 'DESC')->first();
                $pengeluaran = Pengeluaran::where('barang_id', $b->id)->orderBy('created_at', 'DESC')->first();
                $tmp['id'] = $b->id;
                $tmp['nama_barang'] = $b->nama_barang;
                $tmp['foto'] = $b->foto;
                $tmp['stok'] = $b->stok;
                $tmp['keterangan'] = $b->keterangan;
                if ($pemasukan) {
                    $tmp['jumlah_masuk'] = $pemasukan->jumlah;
                    $tmp['tanggal_masuk'] = $pemasukan->created_at;
                } else {
                    $tmp['jumlah_masuk'] = "0";
                    $tmp['tanggal_masuk'] = '-';
                }
                if ($pengeluaran) {
                    $tmp['jumlah_keluar'] = $pengeluaran->jumlah;
                    $tmp['tanggal_keluar'] = $pengeluaran->created_at;
                } else {
                    $tmp['jumlah_keluar'] = "0";
                    $tmp['tanggal_keluar'] = '-';
                }
                array_push($data, $tmp);
            }
            return response()->json([
                'status_code' => 200,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'message' => $th
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $fileType = $request->file('foto')->extension();
            $name = Str::random(8) . '.' . $fileType;
            $input['foto'] = Storage::putFileAs('foto', $request->file('foto'), $name);
            $input['nama_barang'] = $request->nama_barang;
            $input['stok'] = $request->stok;
            $input['keterangan'] = $request->keterangan;
            $input['status'] = $request->status;
            $input['harga_barang'] = $request->harga_barang;
            $input['ongkos_pembuatan'] = $request->ongkos_pembuatan;


            Barang::create($input);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            return $th;
            return response()->json([
                'status_code' => 401,
                'message' => $th,
            ]);
        }
    }

    public function show($id)
    {
        try {
            $data = [];
            $barang = Barang::where('id', $id)->first();
            $masuk = Pemasukan::where('barang_id', $id)->get();
            $keluar = Pengeluaran::where('barang_id', $id)->get();
            $data['detail'] = $barang;
            $data['pemasukan'] = $masuk;
            $data['pengeluaran'] = $keluar;
            return response()->json([
                'status_code' => 200,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'message' => $th,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if ($request->foto) {
                $fileType = $request->file('foto')->extension();
                $name = Str::random(8) . '.' . $fileType;
                $input['foto'] = Storage::putFileAs('foto', $request->file('foto'), $name);
            }

            $input['nama_barang'] = $request->nama_barang;
            $input['stok'] = $request->stok;
            $input['status'] = $request->status;
            $input['keterangan'] = $request->keterangan;
            $input['harga_barang'] = $request->harga_barang;
            $input['ongkos_pembuatan'] = $request->ongkos_pembuatan;

            Barang::where('id', $id)->update($input);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'message' => $th,
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            Barang::where('id', $id)->update(['status' => 'Habis']);
            return response()->json([
                'status_code' => 200,
                'message' => 'Success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status_code' => 401,
                'message' => $th,
            ]);
        }
    }
}
