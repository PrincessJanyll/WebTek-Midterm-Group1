<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 24/03/2015
 * Time: 11:22
 */
if(isset($_POST['type'])  )
{

    $type = $_POST['type'];
    require_once '../../../../../wp-load.php';
    require_once '../../ClassWP/SpTranslate.class.php';
    $tr = SpTranslate::getInstance();
    switch ($type)
    {
        case "multiple":
            require_once 'quiz/multiple.php';
            break;
        case "unique":
            require_once 'quiz/unique.php';
            break;
        default :
            header("HTTP/1.0 400 Bad Request");
    }

}