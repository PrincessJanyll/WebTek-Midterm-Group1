<?php

if ( !defined( 'ABSPATH' ) ) exit;


wp_enqueue_media();

global $tr;

$managerQuiz = new QuizManager();
$managerCourse = new CourseManager();


$error_quiz_add = "";


$error_quiz_remove = "";


if((isset($_GET['type'])) && ($_GET['type'] === "delete") && (isset($_GET['id']))){

    $validation = new validation();

    $validation->addSource(array('id' => $_GET['id']));

    $validation->addRule('id','numeric',true,0,1000000,true);

    $validation->run();

    if((sizeof($validation->errors))>0)
        $error_quiz_remove = $validation->getMessageErrors();
    else{
        $quiz = $managerQuiz->getById($validation->sanitized['id']);
        if($quiz != null){
            $managerQuiz->delete($validation->sanitized['id']);

            if($managerQuiz->isError()){
                $error_quiz_remove = $managerQuiz->getMessageError();
            }
        }
    }
}

if(isset($_POST['add'])) {


    if (isset($_POST['quiz']) && !empty($_POST['quiz'])) {

        $v = new validation();


        $v->addSource($_POST['quiz']);


        //rule Name
        $v->addRule('name', 'string', true, 1, 200, true);


        //rule Image
        if (isset($_POST['quiz']['pictureurl']) && !empty($_POST['quiz']['pictureurl'])) {
            $v->addRule('pictureurl', 'numeric', true, 1, 999999, true);

        }


        if (isset($_POST['quiz']['courseId']) && !empty($_POST['quiz']['courseId'])) {
            $v->addRule('courseId', 'numeric', true, 1, 99999, true);
        }
        else
        {
            $v->errors['courseId'] = $tr->__("You must create a course");
        }

        $v->run();

        

        if ((sizeof($v->errors)) > 0)
            $error_quiz_add = $v->getMessageErrors();
        else {


            $currentUser = new StudyPressUserWP();

            $id_quiz = $managerQuiz->add(new Quiz(array(
                'pictureUrl' => (isset($v->sanitized['pictureurl']) ? $v->sanitized['pictureurl'] : ''),
                'name'       => $v->sanitized['name'],
                'author'     => $currentUser->displayName(),
                'authorId'   => $currentUser->id(),
                'courseId'   => $v->sanitized['courseId']
            )));



        }
    }


}


if(isset($_POST['remove'])){
    if(isset($_POST['id']) && !empty($_POST['id']))
    {
        $v = new validation();

        $v->addSource($_POST['id']);

        foreach ($_POST['id'] as $key => $value) {
            $v->addRule($key,'numeric',true,1,9999999,true);
        }

        $v->run();

        if ((sizeof($v->errors)) > 0)
            $error_quiz_remove = $v->getMessageErrors();
        else {
            foreach ($v->sanitized as $value) {
                $managerQuiz->delete($value);
            }
        }



    }
}


require_once __ROOT_PLUGIN__ . "Views/admin/quiz.view.php";
