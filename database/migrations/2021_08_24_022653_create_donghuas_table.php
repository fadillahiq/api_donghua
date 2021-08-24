<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonghuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donghuas', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title')->unique();
            $table->string('image');
            $table->text('synopsis');
            $table->enum('status', ['Ongoing', 'Completed']);
            $table->string('network');
            $table->string('studio');
            $table->date('release_date');
            $table->string('duration');
            $table->string('graphic');
            $table->string('country');
            $table->enum('type', ['Donghua', 'Anime']);
            $table->string('translated_by');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donghuas');
    }
}
