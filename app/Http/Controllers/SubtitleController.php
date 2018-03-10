<?php

namespace App\Http\Controllers;

use App\Library\SubConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class SubtitleController extends Controller
{

    public function upload(Request $request){
        if ($request->hasFile('file')) {
            if($request->file('file')->getClientSize() <= 2000000) {

                $file = $request->file('file');

                $nombre = $file->getClientOriginalName();

                $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

                if ($ext == "vtt") {

                    $name = basename($nombre, "." . $ext);
                    $name = ltrim($name, '.');

                    $newFileName = $name . rand(1, 100) . "." . $ext;

                    //Lo alamcanamos en el directorio public con el nuevo nombre y le ponemos el contenido
                    Storage::disk('public')->put($newFileName, file_get_contents($file->getRealPath()));

                }elseif ($ext == "srt") {
                    //Obtenemos el nombre solo, sin la extension
                    $name = basename($_FILES['file']['name'], "." . $ext);
                    $name = ltrim($name, '.');

                    //le agregamos un numero random y le damos la extension .vtt
                    $newFileName = $name . rand(1, 100) . ".vtt";

                    try {
                        //Le damos la ruta del archivo y el nuevo nombre, y convertimos
                        $convert = new SubConverter($file->getRealPath(), $newFileName);
                        $convert->convert();

                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage() . "\n";
                    }

                }else {
                    die('{Not allowed file type}');
                }

                echo json_encode($newFileName);
            }
        }
    }

    /** Este es el ejemplo */
    public function save(Request $request)
    {
        //obtenemos el campo file definido en el formulario
        $file = $request->file('file');

        //obtenemos el nombre del archivo
        $nombre = $file->getClientOriginalName();

        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($nombre,  \File::get($file));

        return "archivo guardado";
    }
}
