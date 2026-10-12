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
        Schema::create('pakets', function (Blueprint $table) {
    $table->id();
    $table->string('nama_paket');
    $table->string('slug')->unique(); // Untuk URL ramah SEO, misal: wedding-silver
    $table->string('kategori');       // Misal: Pernikahan, Ulang Tahun, Seminar
    $table->text('deskripsi');
    $table->decimal('harga', 15, 2);
    $table->string('gambar')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
