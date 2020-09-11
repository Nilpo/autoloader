<?php

spl_autoload_register(function ($class) {
    $separator = '\\';

    $prefix = "SP4E" . $separator;

    // base directory for the namespace prefix
    $base_dir = SP4E_PLUGIN_PATH . '/src/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    $sep_index = strrpos( $class, $separator) + 1;
    $file_path = substr( $class, 0, $sep_index);
    
    $relative_class = substr( $class,  $sep_index ) ;
    $relative_class = str_replace( '_', '.' , $relative_class );

    $filename = 'class.' . strtolower( $relative_class ) . '.php';

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . $file_path . $filename;

    // if the file exists, require it
    if (file_exists( $file ) ) {
        require_once $file;
    }
});