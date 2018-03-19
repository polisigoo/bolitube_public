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
            $table->longText('resumen')->nullable();
            $table->longText('video_url');
            $table->string('director', '100')->nullable();
            $table->string('fecha_estreno', '50')->nullable();
            $table->string('duracion', '15')->nullable();
            $table->longText('keywords');
            $table->string('generos', '200');
            $table->string('poster_path', '100');
            $table->string('fondo_path', '150')->nullable();
            $table->string('pais_origen', '50')->nullable();
            $table->integer('voto_imdb')->nullable();
            $table->integer('id_db')->nullable();
            $table->string('uri', '150');

            $table->integer('views', '11')->default(1);
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
