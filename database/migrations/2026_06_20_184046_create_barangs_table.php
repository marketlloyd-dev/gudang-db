<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('barangs', function (Blueprint $table) {

        $table->id();

        $table->foreignId('kategori_id')
              ->constrained('kategoris')
              ->onDelete('cascade');

        $table->string('kode_barang')->unique();
        $table->string('nama_barang');

        $table->integer('stok')->default(0);

        $table->decimal('harga_beli',12,2);
        $table->decimal('harga_jual',12,2);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
