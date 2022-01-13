<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama_barang', 'stok', 'foto', 'status', 'harga_barang', 'ongkos_pembuatan'];
}
