<?php


global $tr;

require_once '_AutoLoadClassAjax.php';


if(isset($_POST['type']) && ($_POST['type'] === "add-question" )) {

    if (isset($_POST['id_quiz']) && isset($_POST['value']) && isset($_POST['checked']) && isset($_POST['question']) && isset($_POST['typeQcm']) ) {



        $managerQuiz = new QuizManager();
        $managerQuestion = new QuestionManager();
        $managerProp= new PropositionManager();


        $vq = new validation();
        $vv = new validation();


        $vq->addSource($_POST);
        $vv->addSource($_POST['value']);


        $vq->AddRules(array(

            'question' => array(
                'type' => 'string',
                'required' => 'true',
                'min' => '1',
                'max' => '9999',
                'trim' => 'true'
            ),


            'id_quiz' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            )
        ));


        foreach ($_POST['value'] as $key => $value) {
            if (preg_match('/^[0-9]{1,}$/', $key)) $vv->addRule($key . "", 'string', true, 1, 99999, true);
        }


        $checked= array();
    
        foreach ($_POST['checked'] as $key => $value) {
            if (preg_match('/^[0-9]{1,}$/', $key))
            {
                if($value === "false")
                    $checked[] = "false";
                else
                    $checked[] = "true";
            }
        }


        $vq->run();
        $vv->run();


        if (((sizeof($vv->errors)) > 0) || (sizeof($vq->errors)) > 0) {

            header("HTTP/1.0 400 Bad Request");
            $tr->_e("Please fill in all fields");
            exit;
        }

        if(count($vv->sanitized) !== count($checked) || count($vv->sanitized) < 2 || !in_array("true",$checked,true)) {

            header("HTTP/1.0 400 Bad Request");
            $tr->_e("Please select at least one correct answer");
            exit;

        }






        $questionId = "";
        if ($managerQuiz->getById($vq->sanitized['id_quiz'])) {
            $questionId = $managerQuestion->add(new Question(array(
                    'content' => $vq->sanitized['question'],
                    'quizId' => $vq->sanitized['id_quiz'],
                    'type' => ($_POST['typeQcm']==="unique")?"unique":"multiple",
                )
            ));

            foreach ($vv->sanitized as $key => $value) {
                $managerProp->add(new Proposition(array(
                        'content' => $vv->sanitized[$key],
                        'questionId' => $questionId,
                        'type' => $checked[$key]
                    )
                ));
            }


            echo "true";
        }



    } else {
        header("HTTP/1.0 400 Bad Request");
        $tr->_e("Please fill in all fields");

    }
    exit;
}


if(isset($_POST['type']) && ($_POST['type'] === "remove-question")) {

    if (isset($_POST['id_question']) && isset($_POST['id_quiz'])){


        $managerQuiz = new QuizManager();
        $managerQuestion = new QuestionManager();


        $v = new validation();
        $v->addSource($_POST);
        $v->AddRules(array(

            'id_question' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            ),


            'id_quiz' => array(
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
            $quiz = $managerQuiz->getById($v->sanitized['id_quiz']);
            if($quiz){
                foreach ($quiz->getQuestions() as $q) {
                    if($q->getId() == $v->sanitized['id_question']){
                        $managerQuestion->delete($v->sanitized['id_question']);
                        echo "true";
                        break;
                    }

                }



            }
        }

    }
    exit;
}


if(isset($_POST['type']) && ($_POST['type'] === "get-question")) {

    if (isset($_POST['id_question']) && isset($_POST['id_quiz'])){


        $managerQuestion = new QuestionManager();
        $managerQuiz = new QuizManager();


        $v = new validation();
        $v->addSource($_POST);
        $v->AddRules(array(

            'id_question' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            ),


            'id_quiz' => array(
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
            if ($managerQuiz->getById($v->sanitized['id_quiz'])) {
                $question = $managerQuestion->getById($v->sanitized['id_question']);
                $result['content'] = $question->getContent();
                foreach ($question->getPropositions() as $key => $prop) {
                    $result['propositions'][$key]['content'] = $prop->getContent();
                    $result['propositions'][$key]['true'] = $prop->getType();
                }


                $result['result'] = "true";

                echo json_encode($result);
            }
            else
            {
                $result['result'] = "false";
                echo json_encode($result);
            }


        }
    }
}



if(isset($_POST['type']) && ($_POST['type'] === "update-question" )) {


    if (isset($_POST['id_quiz']) && isset($_POST['value']) && isset($_POST['checked']) && isset($_POST['question']) && isset($_POST['id_question'])) {

        $managerQuiz = new QuizManager();
        $managerQuestion = new QuestionManager();
        $managerProp= new PropositionManager();


        $vq = new validation();
        $vv = new validation();


        $vq->addSource($_POST);
        $vv->addSource($_POST['value']);


        $vq->AddRules(array(

            'question' => array(
                'type' => 'string',
                'required' => 'true',
                'min' => '1',
                'max' => '9999',
                'trim' => 'true'
            ),


            'id_quiz' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            ),

            'id_question' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            )


        ));


        foreach ($_POST['value'] as $key => $value) {
            if (preg_match('/^[0-9]{1,}$/', $key)) $vv->addRule($key . "", 'string', true, 1, 99999, true);
        }


        $checked= array();
        //rules checked
        foreach ($_POST['checked'] as $key => $value) {
            if (preg_match('/^[0-9]{1,}$/', $key))
            {
                if($value === "false")
                    $checked[] = "false";
                else
                    $checked[] = "true";
            }
        }


        $vq->run();
        $vv->run();



        if (((sizeof($vv->errors)) > 0) || (sizeof($vq->errors)) > 0) {

            header("HTTP/1.0 400 Bad Request");
            $tr->_e("Please fill in all fields");
            exit;
        }

        if(count($vv->sanitized) !== count($checked) || count($vv->sanitized) < 2 || !in_array("true",$checked,true)) {

            header("HTTP/1.0 400 Bad Request");
            $tr->_e("Please select at least one correct answer");
            exit;

        }







        if ($managerQuiz->getById($vq->sanitized['id_quiz']) && $question = $managerQuestion->getById($vq->sanitized['id_question'])) {

            $question->setContent($vq->sanitized['question']);
            $managerQuestion->update($vq->sanitized['id_question'],$question);
            foreach ($question->getPropositions() as $prop) {
                $managerProp->delete($prop->getId());
            }


            foreach ($vv->sanitized as $key => $value) {
                $managerProp->add(new Proposition(array(
                        'content' => $vv->sanitized[$key],
                        'questionId' => $question->getId(),
                        'type' => $checked[$key]
                    )
                ));
            }


            echo "true";
        }



    } else {
        header("HTTP/1.0 400 Bad Request");
        $tr->_e("Please fill in all fields");


    }
    exit;
}


if(isset($_POST['type']) && ($_POST['type'] === "order-question")) {

    if (isset($_POST['order']) && !empty($_POST['order'])) {

        $managerQuestion = new QuestionManager();


        $v = new validation();
        $v->addSource($_POST['order']);
        foreach ($_POST['order'] as $key => $value) {
            if (preg_match('/^[0-9]{1,}$/', $key)) {
                $v->addRule($key, 'numeric', true, 1, 9999999, true);
            }
        }
        $v->run();

        if ((sizeof($v->errors)) > 0) {
            header("HTTP/1.0 400 Bad Request");
            echo $v->getMessageErrors();

        } else {
            $re = array();
            foreach ($v->sanitized as $ordre => $id) {
                $re[$id] = $ordre+1;
            }


            $managerQuestion->updateOrders($re);
            echo "true";
        }

    }

}
