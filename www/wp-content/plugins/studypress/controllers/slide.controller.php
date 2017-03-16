<?php

global $tr;

require_once '_AutoLoadClassAjax.php';


if(isset($_POST['type']) && ($_POST['type'] === "add-slide" )) {


    if (isset($_POST['name']) && isset($_POST['content']) && isset($_POST['id_lesson'])) {

        $managerLesson = new LessonManager();
        $managerSlide = new SlideManager();


        $v = new validation();
        $v->addSource($_POST);
        $v->AddRules(array(

            'name' => array(
                'type' => 'string',
                'required' => 'true',
                'min' => '1',
                'max' => '200',
                'trim' => 'true'
            ),


            'id_lesson' => array(
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
            echo $v->getMessageErrors();
        } else {

            if ($managerLesson->getById($v->sanitized['id_lesson'])) {
                $managerSlide->add(new Slide(array(
                        'name' => $v->sanitized['name'],
                        'content' => $_POST['content'],
                        'courseId' => $v->sanitized['id_lesson'],
                    )
                ));

                echo "true";
            }

        }

    } else {
        header("HTTP/1.0 400 Bad Request");
        $tr->_e("Please fill in all fields");

    }
}

if(isset($_POST['type']) && ($_POST['type'] === "remove-slide")) {

    if (isset($_POST['id_lesson']) && isset($_POST['id_slide'])){


        $managerLesson = new LessonManager();
        $managerSlide = new SlideManager();


        $v = new validation();
        $v->addSource($_POST);
        $v->AddRules(array(

            'id_lesson' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            ),


            'id_slide' => array(
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
            $lesson = $managerLesson->getById($v->sanitized['id_lesson']);
            if($lesson){
                foreach ($lesson->getSlides() as $l) {
                    if($l->id() == $v->sanitized['id_slide']){
                        $managerSlide->delete($v->sanitized['id_slide']);
                        echo "true";
                        break;
                    }

                }



            }
        }

    }
}

if(isset($_POST['type']) && ($_POST['type'] === "get-slide")) {

    if (isset($_POST['id_lesson']) && isset($_POST['id_slide'])){


        $managerLesson = new LessonManager();
        $managerSlide = new SlideManager();


        $v = new validation();
        $v->addSource($_POST);
        $v->AddRules(array(

            'id_lesson' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            ),


            'id_slide' => array(
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
            if ($managerLesson->getById($v->sanitized['id_lesson'])) {
                $slide = $managerSlide->getById($v->sanitized['id_slide']);
                $result['nameSlide'] = $slide->name();
                $result['contentSlide'] = $slide->content();
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



if(isset($_POST['type']) && ($_POST['type'] === "update-slide" )) {


    if (isset($_POST['name']) && isset($_POST['content']) && isset($_POST['id_slide']) && isset($_POST['id_slide'])) {




        $managerLesson = new LessonManager();
        $managerSlide = new SlideManager();


        $v = new validation();
        $v->addSource($_POST);
        $v->AddRules(array(

            'name' => array(
                'type' => 'string',
                'required' => 'true',
                'min' => '1',
                'max' => '200',
                'trim' => 'true'
            ),


            'id_lesson' => array(
                'type' => 'numeric',
                'required' => 'true',
                'min' => '1',
                'max' => '999999',
                'trim' => 'true'
            ),
            'id_slide' => array(
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
            echo $v->getMessageErrors();
        } else {
            $lesson = $managerLesson->getById($v->sanitized['id_lesson']);
            $slide = $managerSlide->getById($v->sanitized['id_slide']);
            if($lesson && $slide){
                $slide->setContent($_POST['content']);
                $slide->setName($v->sanitized['name']);


                $managerSlide->update($v->sanitized['id_slide'],$slide);

                echo "true";
            }

        }
    }
}



//Ajouter une note
if(isset($_POST['type']) && ($_POST['type'] === "add-note")) {

    if (isset($_POST['note']) && isset($_POST['id_lesson'])) {


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
            ),


            'note' => array(
                'type' => 'string',
                'required' => 'true',
                'min' => '1',
                'max' => '200',
                'trim' => 'true'
            )
        ));

        $v->run();


        if ((sizeof($v->errors)) > 0) {
            header("HTTP/1.0 400 Bad Request");
            echo $v->getMessageErrors();

        } else {

            $lesson =$managerLesson->getById($v->sanitized['id_lesson']);
            $notes = $lesson->getTags();

        }
    }
}

if(isset($_POST['type']) && ($_POST['type'] === "order-slide")) {

    if (isset($_POST['order']) && !empty($_POST['order'])) {

        $managerSlide = new SlideManager();


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
                $re[$id] = $ordre+1;//+1 pour commencer de 1
            }


            $managerSlide->updateOrders($re);
            echo "true";
        }

    }

}
