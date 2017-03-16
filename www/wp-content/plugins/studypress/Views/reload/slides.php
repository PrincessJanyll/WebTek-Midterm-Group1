<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 03/02/2015
 * Time: 21:16
 */


if(isset($_POST['id_lesson'])) {

    require_once '_AutoLoadClassAjax.php';

    global $tr;



    $managerLesson = new LessonManager();

    $v = new validation();
    $v->addSource($_POST);
    $v->AddRules(array(

        'id_lesson' => array(
            'type' => 'numeric',
            'required' => 'true',
            'min' => '1',
            'max' => '999999',
            'trim' => 'true'
        )));

    $v->run();

    /*
        * Si un/des champs ne est/sont pas valide(s)...
        *
        */

    if ((sizeof($v->errors)) > 0) {
        header("HTTP/1.0 400 Bad Request");

    } else {
        $lesson = $managerLesson->getById($v->sanitized['id_lesson']);
        if($lesson) {
            $result = "";
            if (count($lesson->getSlides())) {


                foreach ($lesson->getSlides() as $slide) : ?>

                    <li id="li-sotable" class="ui-state-default btn btn-default"
                        data-id="<?php echo  $slide->id() ?>">
                                <span class="float-left">
                                    <span class="glyphicon glyphicon-resize-vertical " aria-hidden="true"></span>
                                     <?php echo  $slide->getNiceName() ?>
                                </span>
                        <a href=""  ><span class="glyphicon glyphicon-remove float-right" id="red" aria-hidden="true" data-id="<?php echo  $slide->id() ?>" title="<?php $tr->_e("Delete"); ?>" ></span></a>
                        <a href="" data-toggle="modal" data-target="#myModal"
                           data-id="<?php echo  $slide->id() ?>"><span class="glyphicon glyphicon-pencil float-right" data-id="<?php echo  $slide->id() ?>" aria-hidden="true" title="<?php $tr->_e("Edit"); ?>"></span></a>
                    </li>
                <?php
                endforeach;
            } else {
                echo "<i>" . $tr->__('No slides...') . "</i>";
            }


        }
    }
}

