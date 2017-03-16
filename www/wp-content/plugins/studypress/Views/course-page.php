<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 26/02/2015
 * Time: 21:42
 */

// Interdire l'accée direct...
if ( !defined( 'ABSPATH' ) ) exit;


global $tr;


$config = new Configuration();
$config = $config->getConfig();


wp_register_style( "display_css_course_page", plugins_url("../css/course-page.css",__FILE__) );
wp_enqueue_style('display_css_course_page');

wp_register_style( "display_css_rating", plugins_url("../css/player/rating.css",__FILE__) );
wp_enqueue_style('display_css_rating');

?>

<style>
    #sp-rate-id
    {
        margin: 0;
        padding: 0;

    }
</style>

<?php
$managerCourse = new CourseManager();
$course = $managerCourse->getById($id);
if($course)
{

    if($course->getPictureId())
    {
        $courseImageUrl = wp_get_attachment_image_src($course->getPictureId(), $size = 'thumbnail');
    }
    else
        $courseImageUrl[0] = __ROOT_PLUGIN__2 . "images/course.png";

    ?>


    <div class='course-description'>
        <p>

            <img src="<?php echo $courseImageUrl[0] ?>" alt="<?php echo $course->getName() ?>" />
            <?php echo $course->getDescription() ?>
        </p>
    </div>


    <?php


    $activities = $managerCourse->getActivitiesOfCourse($id);

    if($activities)
    {
        $nbrActivities = 0;
        $nbrDone = 0;

        ?>
        <div>
            <span class="progress-text"></span>
        <div class="progress">
            <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" >
                <span class="sr-only"></span>
            </div>
        </div>
        </div>
<?php
//$get_children_array = PostWP::getChildrenPost($args);

        echo "<div class='sp-courses'><h2>".$tr->__("Activities:")."</h2><ul>";
        $type = "";
        foreach ($activities as $activity):

            if($activity->getPostId() !== 0) {
                $nbrActivities++;
                $managerLesson = new LessonManager();
                $managerQuiz = new QuizManager();
                $managerRate = new RateQualityManager();
                $link = get_permalink($activity->getPostId());


                if ($activity instanceof Lesson) {
                    $icon = "glyphicon-book";
                    $type = $tr->__("Lesson");
                    $image = "lesson";
                    $manager = $managerLesson;
                } else {
                    $icon = "glyphicon-check";
                    $image = "quiz";
                    $type = $tr->__("Quiz");
                    $manager = $managerQuiz;
                }

                $med_image_url = wp_get_attachment_image_src($activity->getPictureUrl(), $size = 'thumbnail');
                if (!$med_image_url[0]) {
                    $med_image_url[0] = __ROOT_PLUGIN__2 . "images/" . $image . ".png";
                }

                ?>


                <li>
                    <div>
                        <?php
                        if (StudyPressUserWP::isLoggedIn()):

                            $user = new StudyPressUserWP();
                            if ($manager->isDone($activity->getId(), $user->id())):
                                $nbrDone++;
                                ?>
                                <!--<div class="border-green"></div>--><p class="done" title="<?php echo $tr->__("Done") ?>">
                                <span class="glyphicon glyphicon-ok"></span></p>
                            <?php
                            endif;
                        endif;
                        ?>


                        <div class="thumb">
                            <img src="<?php echo $med_image_url[0] ?>" width="150" height="150"/>
                        </div>

                        <div class="details">
                            <a href="<?php echo $link ?>"><?php echo $activity->getName() ?></a>

                            <p class="description"><?php echo $activity->getNiceDescription() ?></p>
                            <?php
                            if($config['showRate'] === 'true') :
                            ?>
                            <span>Rater(s) : <?php echo $managerRate->countRate($activity->getId()) ?></span>

                            <div id="sp-rate-id" class="sp-rate-quality"
                                 data-average="<?php echo $managerRate->AVG($activity->getId()) ?>"></div>
                                <?php endif; ?>
                        </div>

                        <div class="sp-details-bottom">
                        <p class="sp-duration"><span class="glyphicon glyphicon-time" title="<?php echo $tr->__("Duration") ?>"></span> <?php echo ($activity->getDuration()==="0")?"?":$activity->getDuration() ?> min</p>
                            <p class="sp-type"><span class="glyphicon <?php echo $icon ?>" title="<?php echo $type ?>"></span> <?php echo $type; ?></p>
                        </div>
                    </div>
                </li>






            <?php
            }
        endforeach;

        echo "</ul></div>";
        ?>

        <script src="<?php echo  __ROOT_PLUGIN__2 . "js/jquery.rateyo.js" ?>"></script>
        <script src="<?php echo  __ROOT_PLUGIN__2 . "js/rating-function.js" ?>"></script>
        <script>
            $(function () {


                <?php if($config['showRate'] === 'true') : ?>

                    $(".sp-rate-quality").each(function() {
                        var item = $(this);
                        item.rateYo({
                            starWidth: "15px",
                            rating: $(this).data("average"),
                            readOnly: true
                        });
                    });

                <?php endif; ?>




            });


            /*
            progress bar
             */

            var classProgress = "progress-bar-success";
            <?php
            if($nbrActivities!==0)
                echo "var progress =  ". round(($nbrDone/$nbrActivities)*100) .";";
            else
                echo "var progress = 0;";
            ?>
            $('.sr-only').html(progress + " %");

            if(progress<25) classProgress ="progress-bar-danger"; //rouge
            else
            if(progress<50) classProgress ="progress-bar-warning"; //orange
            else
            if(progress<100) classProgress="progress-bar-info"; //bleu



            $('.progress-bar').css("width" , (progress===0)?"5%":progress+"%").addClass(classProgress);
            $('.progress-text').html("Currently completed <?php echo $nbrDone ?> activity(ies) of <?php echo  $nbrActivities ?> in total");





        </script>
    <?php

    }
    if($nbrActivities===0)
    {
        echo "<h3>";
        $tr->_e("No activities available for the moment !");
        echo "</h3>";
    }
}

?>

