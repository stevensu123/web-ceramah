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
        Schema::create('ceritas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori_pagi')->nullable();
            $table->string('gambar_pagi')->nullable();
            $table->string('keterangan_pagi')->nullable();
            $table->text('text_cerita_pagi')->nullable();

            $table->string('nama_kategori_siang')->nullable();
            $table->string('gambar_siang')->nullable();
            $table->string('keterangan_siang')->nullable();
            $table->text('text_cerita_siang')->nullable();

            $table->string('nama_kategori_sore')->nullable();
            $table->string('gambar_sore')->nullable();
            $table->string('keterangan_sore')->nullable();
            $table->text('text_cerita_sore')->nullable();

            $table->unsignedTinyInteger('status')->default(1);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tanggal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ceritas');
    }
};
