<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 19/03/2015
 * Time: 19:49
 */



if(isset($_POST['courseId'])) {

    require_once '_AutoLoadClassAjax.php';

    global $tr;



    $managerCourse = new CourseManager();

    $v = new validation();
    $v->addSource($_POST);
    $v->AddRules(array(

        'courseId' => array(
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
        $activities = $managerCourse->getActivitiesOfCourse($v->sanitized['courseId']);
        if($activities) {
            $result = "";




                foreach ($activities as $activity) :
                    $glyphicon = ($activity instanceof Lesson)?"book":"check";
                    $type = ($activity instanceof Lesson)?"Lesson":"Quiz";
                    ?>

                    <li id="li-sotable" class="ui-state-default btn btn-default"
                        data-id="<?php echo  $activity->getId() ?>">
                                <span class="float-left">
                                    <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                                    <?php echo  "<b>" . $activity->getNiceName() ."</b>" ?>
                                </span>


                        <span class="glyphicon glyphicon-<?php echo $glyphicon ?> float-right" title="<?php $tr->_e($type); ?>"></span>
                    </li>
                <?php
                endforeach;
            } else {
                echo "<i>" . $tr->__('No activities...') . "</i>";
            }


        }

}