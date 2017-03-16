<?php


if(isset($_POST['type']) && $_POST['type'] === "get-responses")
{
    if(isset($_POST['quizId']) && isset($_POST['userId']))
    {
        require_once '_AutoLoadClassAjax.php';

        global $tr;


        $v = new validation();
        $v->addSource($_POST);
        $v->AddRules(array(

            'quizId' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            ),


            'userId' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            )
        ));

        $v->run();



        if ((sizeof($v->errors)) > 0) {
            header("HTTP/1.0 400 Bad Request");

        } else {
            $managerQuiz = new QuizManager();
            if($quiz = $managerQuiz->getById($v->sanitized['quizId']))
            {
                $resultQuiz = $managerQuiz->getResultOfQuizByUser($quiz->getId(),$v->sanitized['userId']);

                if($resultQuiz)
                {
                    $result['body'] ="";
                    foreach ($resultQuiz->getQuestions() as $q) {
                        $result['body'] .= $q->getContentSlideWithErrors();
                    }

                    $classe = ($resultQuiz->getNote()>50)?"green":"red";

                    $result['pourcentage'] = $tr->__("Note")." : <span class='". $classe  ."'>" .$resultQuiz->getNote()."%</span>";
                    $result['quiz'] = $tr->__("Name of quiz")." : " .$quiz->getName();

                    $user = new StudyPressUserWP($v->sanitized['userId']);
                    $result['user'] = $tr->__("User")." : " .$user->displayName();


                    $result['result'] ="true";

                    echo json_encode($result);

                }


            }


        }
    }
}

else
{
    if ( !defined( 'ABSPATH' ) ) exit;



    $managerResult = new QuizManager();



    require_once __ROOT_PLUGIN__ . "Views/admin/resultQuiz.view.php";
}

