<?php

if ( !defined( 'ABSPATH' ) ) exit;

$spConfiguration = new Configuration();
$spConfiguration = $spConfiguration->getConfig();

global $tr;

function slide_presentation_quiz(Quiz $quiz,$name){
    global $tr;
    $manageCourse = new CourseManager();
    $c = $manageCourse->getById($quiz->getCourseId());
    return
        "<div class='sp-presentation-content'>
            <div>
                <h4><strong>". $tr->__("Author")."</strong>: ".$name."</h4>
                <h4><strong>".$tr->__("Course")."</strong>: ".$c->getName()."</h4>
                <h4><strong>".$tr->__("Duration")."</strong>: ".$quiz->getDuration()." min</h4>
            </div>
            <h2>".$quiz->getName()."</h2>

        </div>";



}

if($id !== null){
    $currentUser = new StudyPressUserWP();

    $v = new validation();
    $v->addSource(array('id' => $id));
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
        $tr->_e("The value of the identifier of the shortcode is incorrect");

    } else {
        $managerQuiz = new QuizManager();

        $quiz = $managerQuiz->getById($v->sanitized['id']);
        if($quiz){
            $sp_btn_share = "<button class='btn-share' title='".$tr->__("Share")."'>".$tr->__("Share")."</button>";
            $btn_buddypress_share ="";
            $btn_social_share ="";




            $v = ($currentUser->isLoggedIn())?sha1($currentUser->id()):"";

            $path_json = "Public/Quiz/". $quiz->getId().$v.".json";;

            $json_file =__ROOT_PLUGIN__ . $path_json;


            $sp_user =  new StudyPressUserWP($quiz->getAuthorId());


            $sp_userName = ($sp_user->firstName() . ' ' . $sp_user->lastName());


            $sp_userLink = StudyPressUserWP::getUserPostsLink( $quiz->getAuthorId() );


            $items =array();
            $owl['items'][] = array(

                'name' => $tr->__('Presentation'),
                'content' => slide_presentation_quiz($quiz,$sp_userName),
            );






            $resultContent = "";

            if ($spConfiguration['share_socialNetwork']=== 'true'){
                $btn_social_share = "<button class='btn-facebook' id='btn-social' title='Facebook'> <span>facebook</span ></button>";
                $btn_social_share .=  "<button class='btn-twitter' id='btn-social' title='Twitter'> <span>Twitter</span></button>";
                $btn_social_share .= "<button class='btn-google' id='btn-social'  title='Google+'> <span>Google+</span></button>";
                $btn_social_share .= "<button class='btn-linkedin' id='btn-social' title='LinkedIn'> <span>LinkedIn</span></button>";
            }

            $result = $managerQuiz->getResultOfQuizByUser($id,$currentUser->id()) ;
            if($result && $result->isValide() )
            {



                if( function_exists('bp_is_active')&& (bp_is_active('groups')) && ($spConfiguration['bp_shareResult']=== 'true') && (StudyPressUserWP::isLoggedIn()))
                {

                    $btn_buddypress_share = "<button class='btn-buddypress' id='btn-social' title='BuddyPress'><span>Buddypress</span></button>";
                }




                $class = ((int) $result->getNote()>=50)?"green":"red";
                $resultContent = "<div class='sp-result'><div class='sp-postit'><p>".$tr->__("You obtained").":</p><strong class='" . $class ."'>" . $result->getNote() . "% </strong></div></div>";


                $i = 0;
                foreach ($result->getQuestions() as $question)
                {

                    $content = $question->getContentSlideWithErrors();
                    $name = "Question N°" . ($i+1);

                    $owl['items'][] = array(

                        'name' => $name,
                        'content' => $content,
                    );

                    $i++;

                }


            }
        
            else
            {

                $i = 0;
                foreach ($quiz->getQuestions() as $question)
                {

                    $content = $question->getContentSlide();
                    $name = "Question N°" . ($i+1);

                    $owl['items'][] = array(

                        'name' => $name,
                        'content' => $content,
                    );

                    $i++;

                }


                $resultContent ="<div class='sp-result'><div class='loading hide'></div><button type='button' id='sp-validate'>". $tr->__("Validate") . "</button> </div>";
            }







            $owl['items'][] = array(

                'name' => "Validation",
                'content' => $resultContent,
            );

            if($spConfiguration['showRate']=== 'true')
            {
                $owl['items'][] = array(

                    'name' => "",
                    'content' => "",
                );
            }


            $owl['title'] = $quiz->getName();
            $owl['authorName'] = $sp_userName;
            $owl['authorImg'] = StudyPressUserWP::getAvatar($quiz->getAuthorId(),30);
            $owl['authorLink'] = StudyPressUserWP::getUserPostsLink($quiz->getAuthorId());



            $fp = fopen($json_file, 'w');
            fwrite($fp, json_encode($owl));
            fclose($fp);




            require_once __ROOT_PLUGIN__ . "Views/player/player-quiz.php";
        }
        else
            $tr->_e("The value of the identifier of the shortcode is incorrect");

    }



}

else
    $tr->_e("Please indicate the identifier of the shortcode");