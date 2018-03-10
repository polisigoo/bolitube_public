<?php
namespace App\Library;

use Illuminate\Support\Facades\Storage;

class SubConverter
{
    private $srtfile;
    private $outputfile;

    function __construct($srtfile, $outputvtt)
    {
        $this->srtfile = $srtfile;
        $this->outputfile = $outputvtt;
    }

    private function utf8_fopen_read($fileName) {
        $fc = iconv('windows-1250', 'utf-8', file_get_contents($fileName));
        $handle=fopen("php://memory", "rw");
        fwrite($handle, $fc);
        fseek($handle, 0);
        return $handle;
    }

    function convert(){
        $srtFile = $this->srtfile;
        $webVttFile = $this->outputfile;

        // Read the srt file,in utf8, content into an array of lines
        $fileHandle = $this->utf8_fopen_read($srtFile);
        if ($fileHandle) {
            // Assume that every line has maximum 8192 length
            // If you don't care about line length then you can omit the 8192 param
            $lines = array();
            while (($line = fgets($fileHandle, 8192)) !== false) {
                $lines[] = $line;
            }
            if (!feof($fileHandle)) exit ("Error: unexpected fgets() fail\n");
            else ($fileHandle);
        }

        // Convert all timestamp lines
        // The first timestamp line is 1
        $length = count($lines);
        for ($index = 1; $index < $length; $index++) {
            // A line is a timestamp line if the second line above it is an empty line
            if ($index === 1 || trim($lines[$index - 2]) === '') {
                $lines[$index] = str_replace(',', '.', $lines[$index]);
            }
        }
        // Insert VTT header and concatenate all lines in the new vtt file
        $header = "WEBVTT\n\n";

        Storage::disk('public')->put($webVttFile, $header . implode('', $lines));
        //file_put_contents($webVttFile, $header . implode('', $lines));
    }
}