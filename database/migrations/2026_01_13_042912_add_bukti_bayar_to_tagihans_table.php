<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->string('bukti_bayar')->nullable()->after('keterangan');
            $table->timestamp('tgl_upload')->nullable()->after('bukti_bayar');
        });

        // Update ENUM status column using raw SQL as Doctrine DBAL enum support has limitations
        DB::statement("ALTER TABLE tagihans MODIFY COLUMN status ENUM('belum_lunas', 'menunggu_konfirmasi', 'lunas') DEFAULT 'belum_lunas'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tagihans', function (Blueprint $table) {
            $table->dropColumn(['bukti_bayar', 'tgl_upload']);
        });

        DB::statement("ALTER TABLE tagihans MODIFY COLUMN status ENUM('belum_lunas', 'lunas') DEFAULT 'belum_lunas'");
    }
};
