<?php
session_start();

// Register all controllers in folder app/
spl_autoload_register(function ($class) {
    $class = explode('\\', $class);
    $class = end($class);

    if (!file_exists('./app/controllers/' . $class . '.php')) {
        require_once '../../controllers/' . $class . '.php';
    } else {
        require_once './app/controllers/' . $class . '.php';
    }
});


/**
 * All Functions Register Here
 * Copyright Siwa 2022
 * ! please don't change below this without permit from Siwa
 * ? if you have question, ask to Siwa :)
 */

/**
 * Easily require file
 * @param string $requireFilePath
 * 
 * @return require_once path
 */
function reqFile($requireFilePath)
{
    require_once $requireFilePath;
}

// easily var_dump
function dump($dump)
{
    var_dump($dump);
    echo '<hr>';
}

// easily var_dump with die
function dd($dumpdie)
{
    var_dump($dumpdie);
    die;
}

// easily redirect
function redirect($path)
{
    header("Location: " . $path);
}

// refresh page
function refresh()
{
    header("Refresh:0");
}

// easily know current url for app\views
function currentApp()
{
    $currentPATH = $_SERVER['REQUEST_URI'];
    $currentPATH = explode('/', $currentPATH);
    $currentPATH = array_splice($currentPATH, -2);
    $currentPATH = $currentPATH[0] . '/' . $currentPATH[1];
    $currentPATH = explode('.', $currentPATH);
    $currentPATH = $currentPATH[0];
    return $currentPATH;
}
