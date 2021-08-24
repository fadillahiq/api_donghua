<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonghuaGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donghua_genre', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donghua_id');
            $table->unsignedBigInteger('genre_id');
            $table->timestamps();

            $table->foreign('donghua_id')->references('id')->on('donghuas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donghua_genre');
    }
}
