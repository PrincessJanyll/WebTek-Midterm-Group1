<?php

function studyPressLoadClass($class)
{

        $directories =array(
            __ROOT_PLUGIN__ . "Class/",
            __ROOT_PLUGIN__ . "ClassWP/",
            __ROOT_PLUGIN__ . "validation/",
            __ROOT_PLUGIN__ . "Data/"
        );

     
    foreach ($directories as $directory) {
        if(file_exists($directory . $class .'.class.php'))
        {
            require_once $directory . $class .'.class.php';
            break;
        }

    }


}

spl_autoload_register('studyPressLoadClass');




