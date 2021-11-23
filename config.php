<?php

//Php version validate
(explode('.', phpversion())[0] < '8') ? die("You need PHP 8.0 or later \n You have PHP " . phpversion()) : true;

//Composer validate
(file_exists('../vendor/autoload.php')) ? require '../vendor/autoload.php' : die("Please run the command: composer install");

/*
|-------------------------------------------------------------------
| (EN) Load environment variables
| (ES) Carga de variables de entorno
|-------------------------------------------------------------------
|
| (EN) Dotenv class instance for using environment variables
| (ES) Instancia de la clase Dotenv para el uso de variables de entorno
*/

$env = Dotenv\Dotenv::createMutable((isset($path_console)) ? $path_console : __DIR__);
$env->load();

/*
|-------------------------------------------------------------------
| (EN) Debug Mode
| (ES) Modo depuración
|-------------------------------------------------------------------
|
| (EN) Validate if debug mode is active 
| (ES) Validar si el modo depuración está activo.
|
*/

if ($_ENV['APP_DEBUG'] == "true") :
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
endif;

/*
|-------------------------------------------------------------------
| (EN) Set memory limit
| (ES) Definir limite de memoria
|-------------------------------------------------------------------
|
*/

if (isset($_ENV['APP_MEMORY_LIMIT'])) :
    ini_set('memory_limit', $_ENV['APP_MEMORY_LIMIT']);
endif;
