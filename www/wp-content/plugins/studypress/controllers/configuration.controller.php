<?php


$managerConfig = new  Configuration();



if(isset($_POST['validate'])) {

    $newConfig= array();
    $newConfig['version'] = "1.1";
    $newConfig['showRate'] = (isset($_POST['showRate']))? "true": "false";
    $newConfig['responsive'] = (isset($_POST['responsive']))? "true": "false";
    $newConfig['bp_shareActivity'] = (isset($_POST['bp_shareActivity']))? "true": "false";
    $newConfig['bp_shareResult'] = (isset($_POST['bp_shareResult']))? "true": "false";
    $newConfig['share_socialNetwork'] = (isset($_POST['share_socialNetwork']))? "true": "false";
    $newConfig['showTag'] = (isset($_POST['showTag']))? "true": "false";
    $newConfig['showGlossary'] = (isset($_POST['showGlossary']))? "true": "false";
    $sizes = array("small","medium","large");
    $newConfig['sizePlayer'] = (isset($_POST['sizePlayer']) && (in_array($_POST['sizePlayer'],$sizes)))? $_POST['sizePlayer']: $sizes[1];

    $managerConfig->InsertConfig($newConfig);

}


$configuration = $managerConfig->getConfig();

require_once __ROOT_PLUGIN__ . 'Views/admin/configuration.view.php';

?>