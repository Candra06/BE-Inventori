<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHargaDanOngkos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->integer('harga_barang')->after('stok')->default(0);
            $table->integer('ongkos_pembuatan')->after('harga_barang')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang', function (Blueprint $table) {
            //
        });
    }
}
