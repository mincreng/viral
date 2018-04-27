<?php

if (function_exists('spl_autoload_register')) {
    spl_autoload_register(function ($class) {
        $class = __DIR__.'/'.str_replace('\\', DIRECTORY_SEPARATOR, str_replace('RSSWriter\\', '', $class)).'.php';
        if (file_exists($class)) {
            require $class;
        }
    });
}
