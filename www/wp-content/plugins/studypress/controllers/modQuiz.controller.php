<?php


if ( !defined( 'ABSPATH' ) ) exit;


wp_enqueue_media();

global $tr;

$user = new StudyPressUserWP();


$managerQuiz = new QuizManager();
$managerCourse = new CourseManager();


$quiz = null;

$error_quiz_update = "";


$error_quiz_add_question = "";



if(isset($_GET['id']) && !empty($_GET['id']))
{
    $v = new validation();
    $v->addSource($_GET);
    $v->addRule('id','numeric',true,1,9999999,true);
    $v->run();

   
    if((!sizeof($v->errors))>0) {

        $quiz = $managerQuiz->getById($v->sanitized['id']);


        if ($quiz) {
            if (isset($_POST['update'])) {
                //var_dump($_POST);
                $v = new validation();
                $v->addSource($_POST['quiz']);
                $v->AddRules(
                    array(
                        'id' => array(
                            'type' => 'numeric',
                            "required" => true,
                            'min' => '1',
                            'max' => '999999',
                            'trim' => true
                        ),
                        'name' => array(
                            'type' => 'string',
                            "required" => true,
                            'min' => '1',
                            'max' => '400',
                            'trim' => true
                        ),
                        'description' => array(
                            'type' => 'string',
                            "required" => true,
                            'min' => '0',
                            'max' => '999999',
                            'trim' => true
                        ),
                        'duree' => array(
                            'type' => 'numeric',
                            "required" => true,
                            'min' => '0',
                            'max' => '999999',
                            'trim' => true
                        ),
                        'pictureurl' => array(
                            'type' => 'numeric',
                            "required" => false,
                            'min' => '0',
                            'max' => '999999',
                            'trim' => true
                        ),
                        'courseId' => array(
                            'type' => 'numeric',
                            "required" => false,
                            'min' => '0',
                            'max' => '999999',
                            'trim' => true
                        )
                    ));



                $v->run();



                $notes = (isset($_POST['quiz']['note']))?json_encode($_POST['quiz']['note']):"";
                $glossaires = (isset($_POST['quiz']['glossary']))?json_encode($_POST['quiz']['glossary']):"";






                if ((sizeof($v->errors)) > 0)
                    $error_quiz_update = $v->getMessageErrors();
                else {


                        $currentUser = new StudyPressUserWP();

                        if ($managerCourse->getCoursesByAuthor($currentUser->id())) {
                            $quiz = $managerQuiz->getById($v->sanitized['id']);





                            $quiz->setName($v->sanitized['name']);
                            $quiz->setCourseId($v->sanitized['courseId']);
                            $quiz->setDescription($v->sanitized['description']);
                            $quiz->setDuration($v->sanitized['duree']);
                            $quiz->setPictureUrl($v->sanitized['pictureurl']);
                            $quiz->setTags($notes);
                            $quiz->setGlossary($glossaires);


                            $managerQuiz->update($v->sanitized['id'], $quiz);


                            $quiz = $managerQuiz->getById($v->sanitized['id']);

                        }

                    }


                }
            }
        else{
            wp_die("Access denied !");
        }


        $course = $managerCourse->getById($quiz->getCourseId());
        if($user->isAdministrator() || in_array($user->id(),$course->getAuthors()))
            require_once __ROOT_PLUGIN__ . "Views/admin/modQuiz.view.php";
        else
            wp_die("Access denied !");
        } else

            echo "<h3>" .$tr->__("Page not found") ." !!</h3>";







}













