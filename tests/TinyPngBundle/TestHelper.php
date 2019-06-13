<?php

namespace Tests\TinyPngBundle;

class TestHelper
{
    public static function cleanConfig()
    {
        $file = PIMCORE_CONFIGURATION_DIRECTORY . '/tinypng.php';
        if(file_exists($file)){
            unlink($file);
        }
    }
}