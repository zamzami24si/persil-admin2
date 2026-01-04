<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sengketa_persil', function (Blueprint $table) {
            $table->id('sengketa_id');
            $table->unsignedBigInteger('persil_id');
            $table->string('pihak_1');
            $table->string('pihak_2');
            $table->text('kronologi')->nullable();
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->text('penyelesaian')->nullable();
            $table->timestamps();

            $table->foreign('persil_id')
                  ->references('persil_id')
                  ->on('persil')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sengketa_persil');
    }
};
