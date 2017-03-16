<?php



$result = array();
$result['result'] = "false";
$result['content'] = "";

if(isset($_POST['type']) && ($_POST['type'] === "get-groups" )) {
    if( isset($_POST['quizId']) && !empty($_POST['quizId']) ) {

        require_once "_AutoLoadClassAjax.php";
        global $tr;

        $c = new Configuration();
        $c = $c->getConfig();
        if($c['bp_shareResult'] = 'true')
        {
            $v = new validation();
            $v->addSource($_POST);
            $v->addRule('quizId','numeric', true, 1, 9999999, true);
            $v->run();
            if ((sizeof($v->errors)) > 0) {
                header("HTTP/1.0 400 Bad Request");
                echo $v->getMessageErrors();

            } else {
                $manager = new QuizManager();

                $type = "quiz";
                $activity = $manager->getById($v->sanitized['quizId']);
                if(!$activity)
                {
                    $manager = new LessonManager();
                    $type = "lesson";
                    $activity = $manager->getById($v->sanitized['quizId']);
                }
                if($activity){
                    if(StudyPressUserWP::isLoggedIn())
                    {
                        $user  = new StudyPressUserWP();

                        if( function_exists('bp_is_active') && bp_is_active('groups'))
                        {
                            $result['content'] .= "<h3>".$tr->__("Comment:")."</h3>";
                            $result['content'] .= "<textarea name='comment' rows='2' cols='100'></textarea>";
                            if($type === "lesson")
                                $result['content'] .= "<h3>".$tr->__("Share in:")."</h3>";
                            else
                            {
                                $result['content'] .= "<h3>".$tr->__("Share :")."</h3>";
                                $result['content'] .= "<label><input type='radio' value='result' name='t' checked='checked'> ".$tr->__("Result")."</label>";
                                $result['content'] .= "&nbsp;<label><input type='radio' value='quiz' name='t'> ".$tr->__("Quiz")."</label>";
                                $result['content'] .= "<h3>".$tr->__("Share In :")."</h3>";
                            }



                            $result['content'] .= "<label><input type='checkbox' value='0'> ".$tr->__("Personal profile")."</label><div class='separator'></div>";

                            $args = array(
                                'user_id' => $user->id()
                            );
                            if ( bp_has_groups ( $args ) ) {

                                while (bp_groups($args)) {
                                    bp_the_group();

                                    $groupId = bp_get_group_id();

                                    $groupName = bp_get_group_name();

                                    $result['content'] .= "<label><input type='checkbox' value='$groupId'> $groupName</label><br/>";


                                }


                            }
                            else
                            {
                                $result['content'] .= "no groups";
                            }

                            $result['content'] .= "<button type='button' class='button'>".$tr->__("Validate")."</button>";

                            $result['result'] = "true";

                            echo json_encode($result);

                        }

                    }
                }
            }

        }


    }
}



if(isset($_POST['type']) && ($_POST['type'] === "share-groups" )) {
    if (isset($_POST['quizId']) && !empty($_POST['quizId']) && isset($_POST['groups'])) {
        require_once "_AutoLoadClassAjax.php";
        global $tr;

        $c = new Configuration();
        $c = $c->getConfig();
        if ($c['bp_shareResult'] = 'true') {
            $v = new validation();
            $v->addSource($_POST);
            $v->addRule('quizId', 'numeric', true, 1, 9999999, true);
            $v->run();
            if ((sizeof($v->errors)) > 0) {
                header("HTTP/1.0 400 Bad Request");
                echo $v->getMessageErrors();

            } else {
                $manager = new QuizManager();
                $type = "quiz";
                $activity = $manager->getById($v->sanitized['quizId']);

                if (!$activity) {
                    $manager = new LessonManager();
                    $type = "lesson";
                    $activity = $manager->getById($v->sanitized['quizId']);
                }

                if ($activity) {
                    if (StudyPressUserWP::isLoggedIn()) {
                        $user = new StudyPressUserWP();

                        if ($manager->isDone($v->sanitized['quizId'], $user->id())) {

                            if (function_exists('bp_is_active') && bp_is_active('groups')) {

                                $permalink = get_permalink($activity->getPostId());

                                $med_image_url = $activity->getPicture();

                                if ($type === "quiz") {
                                    $resultQuiz = $manager->getResultOfQuizByUser($v->sanitized['quizId'], $user->id());

                                    if(!$resultQuiz) exit;

                                    if (isset($_POST['type_share']) && $_POST['type_share'] === 'result') {

                                        $action = $user->displayName() . " " . $tr->__("obtained") . " " . $resultQuiz->getNote() . "% " .$tr->__("in") ." : <a href='$permalink'>" . $activity->getName() . "</a>"  ;
                                    } else {
                                        $action = $user->displayName() . $tr->__(" shared Quiz") . " : " ."<a href='$permalink' style='display: block'>". $activity->getName() ."</a>";
                                    }

                                }

                                if ($type === "lesson")
                                    $action = $user->displayName() . $tr->__(" shared Lesson") . " : " ."<a href='$permalink' style='display: block'>". $activity->getName() ."</a>";


                                $content = "<a href='$permalink' style='display: block'><img src='$med_image_url' width='150' height='150' /> </a>
                                     " . ((isset($_POST['comment'])) ? $_POST['comment'] : "");



                                if (count($_POST['groups']) === 1 && ($_POST['groups'][0] === '0')) {

                                    $activity_id = bp_activity_add(array(
                                        'user_id' => $user->id(),
                                        'component' => 'groups',
                                        'type' => 'activity_update',
                                        'action' => $action,
                                        'content' => $content,
                                        'item_id' => '0'
                                    ));

                                    echo "true";
                                } else if (count($_POST['groups']) > 1) {

                                    foreach ($_POST['groups'] as $groupId) {

                                        if ($groupId === '0') continue;

                                        $group = new BP_Groups_Group($groupId);
                                        if ($group->id !== '0') {
                                            $activity_id = bp_activity_add(array(
                                                'user_id' => $user->id(),
                                                'component' => 'groups',
                                                'type' => 'activity_update',
                                                'action' => $action,
                                                'content' => $content,
                                                'item_id' => $groupId

                                            ));
                                        }


                                    }

                                    echo "true";


                                }

                            }
                        }


                    }
                }
            }
        }
    }
}