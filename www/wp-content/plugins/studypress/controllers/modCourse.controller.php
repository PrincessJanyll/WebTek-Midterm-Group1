<?php


if(isset($_POST['type']) && ($_POST['type'] === "order-activities")) {

    if (isset($_POST['order']) && !empty($_POST['order'])) {

        require_once '_AutoLoadClassAjax.php';

        $managerLesson = new LessonManager();


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


            $managerLesson->updateOrders($re);
            echo "true";
        }

    }

}
else
{

    if ( !defined( 'ABSPATH' ) ) exit;



    wp_enqueue_media();

    global $tr;


    $user = new StudyPressUserWP();

    $managerCourse = new CourseManager();


    $course = null;
    
    $error_course_update = "";


    $error_course_add = "";



    if(isset($_GET['id']) && !empty($_GET['id']))
    {
        
        $v = new validation();
        $v->addSource($_GET);
        $v->addRule('id','numeric',true,1,9999999,true);
        $v->run();

        if((!sizeof($v->errors))>0) {

            $course = $managerCourse->getById($v->sanitized['id']);


            if ($course) {
                if (isset($_POST['update']) ) {
                    if ($user->isAdministrator()) {


                        if (isset($_POST['course']) && isset($_POST['course']['categories']) && isset($_POST['course']['users'])) {
                            $v1 = new validation();
                            $v2 = new validation();
                            $v3 = new validation();


                            $v1->addSource($_POST['course']);
                            $v2->addSource($_POST['course']['categories']);
                            $v3->addSource($_POST['course']['users']);


                            //rule Image
                            if (isset($_POST['course']['pictureId']) && !empty($_POST['course']['pictureId'])) {
                                $v1->addRule('pictureId', 'numeric', true, 1, 999999, true);
                            }


                            $v1->AddRules(array(
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
                                        'max' => '200',
                                        'trim' => true
                                    ),
                                    'description' => array(
                                        'type' => 'string',
                                        "required" => true,
                                        'min' => '0',
                                        'max' => '999999',
                                        'trim' => true
                                    )
                                )
                            );

                            foreach ($_POST['course']['categories'] as $key => $value) {
                                if (preg_match('/^[0-9]{1,}$/', $key)) $v2->addRule($key . "", 'numeric', true, 1, 99999, true);
                            }

                            if (!count($_POST['course']['categories'])) $v2->errors['categories'] = $tr->__("Please select at least one category");

                            foreach ($_POST['course']['users'] as $key => $value) {
                                if (preg_match('/^[0-9]{1,}$/', $key)) $v3->addRule($key . "", 'numeric', true, 1, 99999, true);
                            }

                            if (!count($_POST['course']['users'])) $v3->errors['categories'] = $tr->__("Please select at least one author");


                            if (isset($_POST['course']['groupsBP'])) {
                                $v4 = new validation();
                                $v4->addSource($_POST['course']['groupsBP']);

                                foreach ($_POST['course']['groupsBP'] as $key => $value) {
                                    if (preg_match('/^[0-9]{1,}$/', $key)) $v4->addRule($key . "", 'numeric', true, 1, 99999, true);
                                }
                                $v4->run();
                            }


                            $v1->run();
                            $v2->run();
                            $v3->run();

                            
                            if ((sizeof($v1->errors)) > 0 || (sizeof($v2->errors)) > 0 || (sizeof($v3->errors)) > 0) {
                                $error_course_update = $v1->getMessageErrors() . $v2->getMessageErrors() . $v3->getMessageErrors();
                            } else {
                                if ($course = $managerCourse->getById($v1->sanitized['id'])) {
                                    $course->setName($v1->sanitized['name']);
                                    $course->setDescription($v1->sanitized['description']);
                                    $course->setCategories($v2->sanitized);
                                    $course->setAuthors($v3->sanitized);
                                    if (isset($_POST['course']['groupsBP'])) {
                                        $course->setGroupsBP($v4->sanitized);
                                    }

                                    if (isset($v1->sanitized['pictureId'])) {
                                        $course->setPictureId($v1->sanitized['pictureId']);
                                    }

                                    $managerCourse->update($course->getId(), $course);

                                }
                            }


                        } else {
                            $error_course_update =
                                $tr->__("Please select at least one author") . "<br/>" .
                                $tr->__("Please select at least one category") . "<br/>" .
                                $tr->__("Please enter a valid name");
                        }
                    }
                    else{ // if is an author
                        $v= new validation();
                        $v->addSource($_POST['course']);

                        $v->AddRules(array(
                                'id' => array(
                                    'type' => 'numeric',
                                    "required" => true,
                                    'min' => '1',
                                    'max' => '999999',
                                    'trim' => true
                                ),
                                'description' => array(
                                    'type' => 'string',
                                    "required" => true,
                                    'min' => '0',
                                    'max' => '999999',
                                    'trim' => true
                                )
                            )
                        );

                        if (isset($_POST['course']['pictureId']) && !empty($_POST['course']['pictureId'])) {
                            $v->addRule('pictureId', 'numeric', true, 1, 999999, true);
                        }

                        $v->run();

                        if (sizeof($v->errors))
                            $error_course_update = $v->getMessageErrors();
                        else{
                            if ($course = $managerCourse->getById($v->sanitized['id'])) {
                                $course->setDescription($v->sanitized['description']);
                                if (isset($v->sanitized['pictureId'])) {
                                    $course->setPictureId($v->sanitized['pictureId']);
                                }

                                $managerCourse->update($course->getId(), $course);
                            }
                        }


                    }
                }
            }
            else{
                wp_die("Access denied !");
            }

            if($user->isAdministrator() || in_array($user->id(),$course->getAuthors()))
                require_once __ROOT_PLUGIN__ . "Views/admin/modCourse.view.php";
            else
                wp_die("Access denied !");
        } else

            echo "<h3>" .$tr->__("Page not found") ." !!</h3>";







    }

}

