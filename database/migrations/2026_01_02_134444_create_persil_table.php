<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('persil', function (Blueprint $table) {
            $table->id('persil_id');
            $table->string('kode_persil')->unique();
            $table->unsignedBigInteger('pemilik_warga_id')->nullable();
            $table->decimal('luas_m2', 10, 2);
            $table->string('penggunaan')->nullable();
            $table->text('alamat_lahan');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->timestamps();

            $table->foreign('pemilik_warga_id')
                  ->references('warga_id')
                  ->on('warga')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('persil');
    }
};
