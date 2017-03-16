<?php

global $tr;

if(isset($_POST['type']) && ($_POST['type'] === "post")) {

    if (isset($_POST['id'])) {


        require_once '_AutoLoadClassAjax.php';




        $managerLesson = new LessonManager();



        $v = new validation();
        $v->addSource($_POST);
        $v->AddRules(array(

            'id' => array(
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

            $lesson = $managerLesson->getById($v->sanitized['id']);
            if($lesson){


                $btn_post = "<button type='button' id='post-lesson' data-id='"  . $lesson->getId(). "' class='btn btn-primary'>" . $tr->__("Publish") . "</button>";

                $btn_remove = "<button type='button' id='post-lesson' data-id='"  . $lesson->getId(). "' class='btn btn-danger'>" . $tr->__("Remove") . "</button>";


                if($lesson->getPostId() === 0) {

                    $postId = $managerLesson->post($lesson);


                    if (AbstractActivityManager::isBuddyPressActive()) {


                        $permalink = get_permalink($postId);
                        $action = $lesson->getAuthor() . " " . $tr->__("shared a lesson") . " : " . "<a href='$permalink'>" . $lesson->getName() . "</a>";

                        $imageUrl = $lesson->getPicture();
                        $content = "<a href='$permalink'><img src='$imageUrl' width='150' height='150' /></a>";

                        $managerLesson->shareOnGroupsBP($lesson,$action,$content);


                    }
                    $result['result'] = "true";
                    $result['value'] = $btn_remove;

                    echo json_encode($result);
                }

                else
                {
                    $managerLesson->unPost($lesson);


                    $result['result'] = "true";
                    $result['value'] = $btn_post;

                    echo json_encode($result);

                }

            }

        }
    }
}