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
    Schema::create('barang_masuks', function (Blueprint $table) {

        $table->id();

        $table->foreignId('barang_id')
              ->constrained('barangs')
              ->onDelete('cascade');

        $table->integer('jumlah');

        $table->date('tanggal_masuk');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuks');
    }
};
