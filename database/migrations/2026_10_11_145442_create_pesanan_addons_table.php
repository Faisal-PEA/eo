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
        Schema::create('pesanan_addons', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pesanan_id')->constrained()->onDelete('cascade');
    $table->foreignId('addon_id')->constrained()->onDelete('cascade');
    $table->integer('qty')->default(1);
    $table->decimal('subtotal', 15, 2);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_addons');
    }
};
