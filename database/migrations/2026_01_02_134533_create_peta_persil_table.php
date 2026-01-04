<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peta_persil', function (Blueprint $table) {
            $table->id('peta_id');
            $table->unsignedBigInteger('persil_id');
            $table->json('geojson')->nullable();
            $table->decimal('panjang_m', 10, 2)->nullable();
            $table->decimal('lebar_m', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('persil_id')
                  ->references('persil_id')
                  ->on('persil')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('peta_persil');
    }
};
