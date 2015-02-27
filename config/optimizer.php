<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Nothing yet
    |--------------------------------------------------------------------------
    |
    | This is a sample of the config variable description header comment block.
    | I want them all to look like this for consistency with Laravel's native
    | config files.
    |
    */

    'tmpFile'  => array(
        'charset' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
        'length'  => 15,
        'folder'  => 'tmp',
    ),
    
    
    
    'pngquant' => array(
        'skip-if-larger' => true,    // Only save converted files if they're smaller
        'quality'        => '0-100', // Will always attempt to produce max, will fail if it cannot produce min
        'speed'          => 3,       // Ranges from 1 (slowest) to 11 (fastest)
        'nofs'           => false,   // Disable Floyd-Steinberg dithering
        'posterize'      => 0,       // Reduces number of colors used to improve compression
    ),

);