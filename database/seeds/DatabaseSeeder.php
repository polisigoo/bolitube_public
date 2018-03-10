<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SerieSeeder::class);

        /*$this->call(VideosSeeder::class);

        $this->call(UserSeeder::class);*/
    }
}
