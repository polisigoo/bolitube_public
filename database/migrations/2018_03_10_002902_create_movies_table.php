<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo', '100');
            $table->string('titulo_original', '100')->nullable();
            $table->string('video_url', '100');
            $table->string('director', '100');
            $table->string('fecha_estreno', '50');
            $table->string('duracion', '15');
            $table->string('keywords', '500');
            $table->string('generos', '200');
            $table->string('poster_path', '100');
            $table->string('pais_origen', '50')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
