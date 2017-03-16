<?php


if(isset($_POST['type']) && $_POST['type'] === "validate") {


    if (isset($_POST['question']['accounting']) && isset($_POST['id_quiz'])) {


        require_once '_AutoLoadClassAjax.php';

        global $tr;




            $questions = $_POST['question']['accounting'];
            $quizId = $_POST['id_quiz'];
            $quizId = $_POST['id_quiz'];


            $managerQuiz = new QuizManager();
            $managerQuestion = new QuestionManager();
            $managerProp = new PropositionManager();

            $quiz = $managerQuiz->getById((int)$quizId);

            $origin = array();


            $response = array();

            $ResponseSave = array();


            if ($quiz) {

                $i = 0;
                foreach ($quiz->getQuestions() as $q) {
                    foreach ($q->getPropositions() as $key => $prop) {
                        $origin[$i]['idQuestion'] = $q->getId();
                        $origin[$i]['idProp'] = $prop->getId();
                        $origin[$i]['true'] = $prop->getType();
                        $i++;
                    }

                    $response[$q->getId()] = "true";

                }

                if (count($origin) === count($questions)) {

                    $i = 0;
                    foreach ($origin as $key => $q) {
                        
                        if (($q['idQuestion'] !== (int)$questions[$key]['idQuestion']) || ($q['idProp'] !== (int)$questions[$key]['idProp']) || ($q['true'] !== trim($questions[$key]['true']))) {
                            $response[$origin[$key]['idQuestion']] = "false";

                        }


                        if (($i === 0) || ((int)$questions[$key]['idQuestion'] !== $ResponseSave[$i - 1]['id'])) {

                            $ResponseSave[] = array(
                                'id' => (int)$questions[$key]['idQuestion'],

                                'title' => $quiz->getQuestionById($questions[$key]['idQuestion'])->getContent(),

                                'props' => array(
                                    array(
                                        'id' => $questions[$key]['idProp'],

                                        'trueUser' => $questions[$key]['true'],

                                        'true' => $q['true'],

                                        'title' => $quiz->getQuestionById($questions[$key]['idQuestion'])->getPropositionById($questions[$key]['idProp'])->getContent()

                                    )));
                            $i++;
                        } else {
                            $ResponseSave[$i - 1]['props'][] = array(
                                'id' => $questions[$key]['idProp'],

                                'trueUser' => $questions[$key]['true'],

                                'true' => $q['true'],

                                'title' => $quiz->getQuestionById($questions[$key]['idQuestion'])->getPropositionById($questions[$key]['idProp'])->getContent()

                            );
                        }

                       

                    }

                   
                    $a = array_count_values($response);


                    $result['connected'] = 'false';

                    $nbrQuestions = count($response);
                    $nbrCorrects = isset($a['true']) ? $a['true'] : 0;

                    $poucentage = round(($nbrCorrects / $nbrQuestions) * 100);

                    if (StudyPressUserWP::isLoggedIn()) {
                      
                        $user = new StudyPressUserWP();
                        $managerQuiz->saveResult($quiz->getId(), $user->id(), $poucentage, $ResponseSave, $nbrQuestions, $nbrCorrects, '', "true");

                        $result['connected'] ='true';
                    }

                        $questionsNoConnected = $managerQuiz->returnedResponseToQuestions($ResponseSave);

                        $responseHtml = "";
                        foreach ($questionsNoConnected as $q) {
                            $responseHtml[] = $q->getContentSlideWithErrors();
                        }

                        $result['qcm'] = $responseHtml;
                    }

                        $class = ((int)$poucentage >= 50) ? "green" : "red";
                        $result['content'] =  "<div class='sp-postit'><p>".$tr->__("You obtained").":</p><strong class='" . $class . "'>" . $poucentage . "% </strong></div>";

                    $result['result'] = 'true';




                    echo json_encode($result);



                }


            }




}


if(isset($_POST['type']) && $_POST['type'] === "start") {
    if (isset($_POST['quizId']) && isset($_POST['date']))
    {
        require_once '_AutoLoadClassAjax.php';

        global $tr;

        if(StudyPressUserWP::isLoggedIn())
        {
            $date = $_POST['date'];
            $quizId = (int) $_POST['quizId'];

            $managerQuiz = new QuizManager();

            if($quiz = $managerQuiz->getById($quizId))
            {
                $user = new StudyPressUserWP();

                $managerQuiz->saveResult($quizId,$user->id(),null,null,null,null,$date,"false");

                echo "true";
            }

        }
        else
            echo "true";
    }
}
