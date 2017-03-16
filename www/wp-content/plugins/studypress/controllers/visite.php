<?php



require_once '_AutoLoadClassAjax.php';

if(isset($_POST['id']) && !empty($_POST['id']))
{
    $v = new validation();

    $v->addSource($_POST);

    $v->addRule('id', 'numeric', true, 1, 99999, true);

    $v->run();

    if ((sizeof($v->errors)) === 0)
    {

        $managerLesson = new LessonManager();
        $managerQuiz = new QuizManager();

        $activity = $managerLesson->getById($v->sanitized['id']);
        if(!$activity)
            $activity = $managerQuiz->getById($v->sanitized['id']);

        if($activity)
        {
            
            if(StudyPressUserWP::isLoggedIn())
            {
                $currentUserId = new StudyPressUserWP();
                $currentUserId = $currentUserId->id();
                $managerLesson->setVisitedActivity($currentUserId,$activity->getId());
            }
        }

    }

}





