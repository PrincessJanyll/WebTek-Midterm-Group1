<?php


require_once '../../../../wp-load.php';


function studyPressLoadClassAjax($class)
{
    

    $directories =array(
          "../Class/",
         "../ClassWP/",
        "../validation/",
        "../Data/"

    );

   

    foreach ($directories as $directory) {
        if(file_exists($directory . $class .'.class.php'))
        {
            require_once $directory . $class .'.class.php';
            break;
        }

    }


}

spl_autoload_register('studyPressLoadClassAjax');
