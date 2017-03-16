<?php

if ( !defined( 'ABSPATH' ) ) exit;

wp_enqueue_media();



global $tr;


$managerCourse = new CourseManager();


$error_course_add = "";


$error_course_remove = "";


$user = new StudyPressUserWP();
if($user->isAdministrator()) {


    if (isset($_POST['add'])) {


        if (isset($_POST['course']) && !empty($_POST['course'])) {

            $v1 = new validation();
            $v2 = new validation();


            $v1->addSource($_POST['course']);


            $v1->addRule('name', 'string', true, 1, 200, true);

            $v1->addRule('desc', 'string', true, 0, 999999, true);

            if (isset($_POST['course']['pictureId']) && !empty($_POST['course']['pictureId'])) {
                $v1->addRule('pictureId', 'numeric', true, 1, 999999, true);
            }

            foreach ($_POST['course'] as $key => $value) {
                if (preg_match('/^[0-9]{1,}$/', $key)) $v1->addRule($key . "", 'numeric', true, 1, 200, true);
            }

            if (isset($_POST['course']['users'])) {
                $v2->addSource($_POST['course']['users']);


                foreach ($_POST['course']['users'] as $key => $value) {
                    if (preg_match('/^[0-9]{1,}$/', $key)) $v2->addRule($key . "", 'numeric', true, 1, 200, true);

                }


            }


            $v1->run();
            $v2->run();


            if (((sizeof($v1->errors)) > 0) || (sizeof($v2->errors))) {
                $error_course_add = $v1->getMessageErrors() . "<br/>";
                $error_course_add .= $v2->getMessageErrors();
            } else {
                $cats = array();
                foreach ($v1->sanitized as $key => $value) {
                    if ((preg_match('/^[0-9]{1,}$/', $key)) && (isIdCategoryWpExist($value)))
                        $cats[] = $value;
                }

                $users = array();
                foreach ($v2->sanitized as $key => $authorId) {

                    if ((preg_match('/^[0-9]{1,}$/', $key)) && (StudyPressUserWP::exist($authorId)))
                        $users[] = $authorId;
                }
                if ($cats) {
                    if ($users) {


                        $managerCourse->add(new
                        Course(array(
                                'name' => $v1->sanitized['name'],
                                'description' => $v1->sanitized['desc'],
                                'pictureId' => isset($v1->sanitized['pictureId']) ? $v1->sanitized['pictureId'] : '',
                                'categories' => $cats,
                                'authors' => $users
                            )));


                    } else {
                        $error_course_add = $tr->__("Please select at least one author");
                    }
                } else {
                    $error_course_add = $tr->__("Please select at least one category");
                }
            }


        } else {
            $error_course_add = $tr->__("Please select a category");
        }
    }


    if (isset($_POST['remove'])) {
        if ((isset($_POST['id'])) && (!empty($_POST['id']))) {

            $v1 = new validation();
            $rules = array();
            
            $v1->addSource($_POST['id']);
            for ($i = 0; $i < count($_POST['id']); ++$i) {


                $rules[] = array(
                    'type' => 'numeric', "required" => true, 'min' => '0', 'max' => '10000', 'trim' => true
                );

            }

            $v1->AddRules($rules);


            $v1->run();
           
            foreach ($v1->sanitized as $id) {

                if ($managerCourse->hasActivities($id)) {
                    $v1->errors['HasLesson'] = $tr->__('The course you want to remove is attached to one or more lessons. Please, first delete these lessons');
                    break;
                }

            }
            if ((sizeof($v1->errors)) > 0)
                $error_course_remove = $v1->getMessageErrors();
            else {
                foreach ($v1->sanitized as $id) {
                    $managerCourse->delete($id);

                }

            }
            
        } else {
            $error_course_remove = $tr->__("Please select the fields to delete");
        }

    }
}


require_once __ROOT_PLUGIN__ . "Views/admin/course.view.php";
