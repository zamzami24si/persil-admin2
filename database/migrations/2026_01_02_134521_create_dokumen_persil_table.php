<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dokumen_persil', function (Blueprint $table) {
            $table->id('dokumen_id');
            $table->unsignedBigInteger('persil_id');
            $table->string('jenis_dokumen');
            $table->string('nomor')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('persil_id')
                  ->references('persil_id')
                  ->on('persil')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_persil');
    }
};
