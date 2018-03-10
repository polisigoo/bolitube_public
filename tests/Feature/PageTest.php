<?php

namespace Tests\Feature;

use App\Video;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PageTest extends TestCase
{

    /**
     * @test
     */
    public function welcome_should_give_code_200()
    {
        $this->get('/')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function list_should_give_code_200()
    {
        $this->get('/list')
        ->assertStatus(200)
        ->assertSee('Inicio');
    }

    /**
     * @test
     */
    public function watch_should_give_code_200()
    {
        $this->get('/watch/')
            ->assertStatus(302);
    }

    /**
     * @test
     */
    /*public function upload_should_give_code_200()
    {
        $this->post('/upload')
            ->assertStatus(200);
    }*/

    /**
     * @test
     */
    public function uploads_should_give_code_200()
    {
        $this->get('/uploads')
            ->assertStatus(200)
            ->assertSee('Upload Form');
    }

    /**
     * @test
     */
    public function tag_should_give_code_200()
    {
        $this->get('/tag/php')
            ->assertStatus(200)
            ->assertSee('Inicio');
    }

    /**
     * @test
     */
    public function save_should_give_code_200()
    {
        $this->post('/edit/video/6ty4azf1a884/save')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function fields_are_required_upload()
    {
        $cant = Video::count();

        $this->from('/uploads')->post('/upload', [
            'titulo' => 'tituloTEST',
            'mime_type' => 'test/test',
            'file_name' => 'test.test',
            'categoria' => '',
            'carpeta' => '',
            'unique_id' => 'mytest',
            'tags' => 'test,test,test'
        ])
            ->assertRedirect('/uploads')
            ->assertSessionHasErrors(['categoria' => 'El campo categoria es obligatorio',
                                        'carpeta' => 'El campo carpeta es obligatorio']);

        $this->assertEquals($cant, Video::count()); //Esto quiere decir que no se agrego

//        $this->assertDatabaseMissing('videos', [
//            'unique_id' => 'mytest'
//        ]);
    }


    /**
     * @test
     */
    public function fields_are_required_edit()
    {
        $this->from('/edit/video/69t5pmk66xkw')->post('/save/69t5pmk66xkw', [
            'titulo' => '',
            'rel' => '22',
            'tags' => ''
        ])
            ->assertRedirect('/edit/video/69t5pmk66xkw')
            ->assertSessionHasErrors(['titulo' => 'Es necesario especificar un titulo',
                                      'tags' => 'Es necesario completar el campo tags']);


    }
}
