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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('tarif_id')->constrained('tarifs')->onDelete('restrict');
            $table->string('bulan'); // 01, 02, etc.
            $table->year('tahun');
            $table->decimal('jumlah', 15, 0); // Using 15,0 for IDR usually integer but decimal safe
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
