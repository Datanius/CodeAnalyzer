<?php

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function(string $class) {
            require_once(__DIR__."/{$class}.php");
        });
    }
}