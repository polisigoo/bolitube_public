<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo','120');
            $table->integer('temporada');
            $table->integer('episodio');
            $table->longText('resumen', '900')->nullable();
            $table->string('url', '100')->nullable();
            $table->string('video_url', '500');
            $table->string('keywords', '500');
            $table->string('fecha_estreno', '50');
            $table->string('image_path', '200')->nullable();
            $table->integer('id_db', '11')->nullable();

            $table->unsignedInteger('serie_id');
            $table->foreign('serie_id')->references('id')->on('series');


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
        Schema::dropIfExists('episodios');
    }
}
