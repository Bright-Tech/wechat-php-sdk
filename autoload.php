<?php

function classLoader($class)
{
    $path = str_replace('bright_tech\\wechat\\', '', $class);
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
    $file = __DIR__ . DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR . $path . '.php';
//     echo $file; exit();
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('classLoader');