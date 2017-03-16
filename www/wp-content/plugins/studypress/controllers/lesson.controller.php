<?php

if ( !defined( 'ABSPATH' ) ) exit;


wp_enqueue_media();

global $tr;

$managerLesson = new LessonManager();
$managerCourse = new CourseManager();


$error_lesson_add = "";


$error_lesson_remove = "";


if((isset($_GET['type'])) && ($_GET['type'] === "delete") && (isset($_GET['id']))){

    $validation = new validation();

    $validation->addSource(array('id' => $_GET['id']));

    $validation->addRule('id','numeric',true,0,1000000,true);

    $validation->run();

    if((sizeof($validation->errors))>0)
        $error_lesson_remove = $validation->getMessageErrors();
    else{
        $lesson = $managerLesson->getById($validation->sanitized['id']);
        if($lesson != null){
            $managerLesson->delete($validation->sanitized['id']);

            if($managerLesson->isError()){
                $error_lesson_remove = $managerLesson->getMessageError();
            }
        }
    }
}

if(isset($_POST['add'])) {


    if (isset($_POST['lesson']) && !empty($_POST['lesson'])) {

        $v = new validation();


        $v->addSource($_POST['lesson']);


        $v->addRule('name', 'string', true, 1, 200, true);


        if (isset($_POST['lesson']['pictureurl']) && !empty($_POST['lesson']['pictureurl'])) {
            $v->addRule('pictureurl', 'numeric', true, 1, 999999, true);

        }


        if (isset($_POST['lesson']['file']) && !empty($_POST['lesson']['file'])) {
            $v->addRule('file', 'numeric', true, 1, 99999, true);

        }

        if (isset($_POST['lesson']['courseId']) && !empty($_POST['lesson']['courseId'])) {
            $v->addRule('courseId', 'numeric', true, 1, 99999, true);
        }
        else
        {
            $v->errors['courseId'] = $tr->__("You must create a course");
        }

        $v->run();

        
        if ((sizeof($v->errors)) > 0)
            $error_lesson_add = $v->getMessageErrors();
        else {


            $currentUser = new StudyPressUserWP();

            $id_lesson = $managerLesson->add(new Lesson(array(
                'pictureUrl' => (isset($v->sanitized['pictureurl']) ? $v->sanitized['pictureurl'] : ''),
                'name'       => $v->sanitized['name'],
                'author'     => $currentUser->displayName(),
                'authorId'   => $currentUser->id(),
                'courseId'   => $v->sanitized['courseId']
            )));


            if (isset($v->sanitized['file']) && !empty($v->sanitized['file'])) {

                $urlFile =  wp_get_attachment_url( $v->sanitized['file']);
               
                $managerSlide = new SlideManager();
                $slide = new Slide(array(
                        'courseId' => $id_lesson,
                        'name' => $tr->__("Download the file"),
                        'content' => "<a href='" . $urlFile . "'>" . basename($urlFile, pathinfo($urlFile, PATHINFO_EXTENSION)) . "</a>",
                    )

                );
                $managerSlide->add($slide);

               
                $lesson = $managerLesson->getById($id_lesson);
                $postId = $managerLesson->post($lesson);

                $permalink = get_permalink($postId);
                $action = $lesson->getAuthor() . " " . $tr->__("shared a lesson") . " : " . "<a href='$permalink'>" . $lesson->getName() . "</a>";


                $imageUrl = $lesson->getPicture();
                $content = "<a href='$permalink'><img src='$imageUrl' width='150' height='150' /></a>";

                $managerLesson->shareOnGroupsBP($lesson,$action,$content);
            }



        }
    }


}




/*
|---------------------------------------------------------------------
| Supression de plusieurs Leçon
|---------------------------------------------------------------------
|
|
*/
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
            $error_lesson_remove = $v->getMessageErrors();
        else {
            foreach ($v->sanitized as $value) {
                $managerLesson->delete($value);


            }
        }



    }
    else
    {
        $error_lesson_remove = $tr->__("Please select the fields to delete");
    }
}


require_once __ROOT_PLUGIN__ . "Views/admin/lesson.view.php";
