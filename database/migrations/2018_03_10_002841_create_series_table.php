<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->increments('id');
            $table->string('show_name', 120);
            $table->integer('temporadas');

            $table->string('poster_path', 100);
            $table->string('fondo_path', 100);

            $table->integer('total_ep')->nullable();

            $table->string('director_creadores', 150)->nullable();

            $table->string('primera_transmision', 80)->nullable();
            $table->string('ultima_transmision', 80)->nullable();

            $table->string('generos', 200);

            $table->string('titulo_esp', 120)->nullable();
            $table->string('descripcion', 900)->nullable();

            $table->integer('id_db');
            $table->string('uri', 80);

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
        Schema::dropIfExists('series');
    }
}
