<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 01/03/2015
 * Time: 14:30
 */

//Wordpress...
require_once '../../../../../wp-load.php';

/**
 * @param string $class
 */
function studyPressLoadClassAjax($class)
{
    /*
    |---------------------------------------------------------------------
    | La variable oû les classes a chargées se trouvent
    |---------------------------------------------------------------------
    |
    |
    */

    $directories =array(
          "../Class/",
        "../validation/",
        "../ClassWP/",
        "../Data/"
    );

    ///--------------------------------------------------------------------


    foreach ($directories as $directory) {
        if(file_exists($directory . $class .'.class.php'))
        {
            require_once $directory . $class .'.class.php';
            break;
        }

    }


}

spl_autoload_register('studyPressLoadClassAjax');