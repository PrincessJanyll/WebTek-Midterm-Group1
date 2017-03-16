<?php


global $tr;

require_once  __ROOT_PLUGIN__ ."Views/includeCSS.php";

?>

<h2 style="text-align: center"><?php echo $tr->__("Help"); ?></h2>

<div class="container-fluid">


    <div class="row">
        <div class="col-md-10 col-md-offset-1 panel">
            <h3><?php echo $tr->__("Course"); ?></h3>
            <hr/>
            <ul>
                <li>
                    <h4>1- <?php echo $tr->__("Course list"); ?>:</h4>
                    <p>This page contains the list of all courses that you have the right to access. This page contains also a quick course creator, in which you can fill in the course name and description, associate an image, categories and authors.</p>
                    <p>Categories present in this page are the categories that you have defined as post categories on Wordpress. The link between them and courses can help the creation of the web site menu.</p>
                    <p>Authors list present in this page contains all Wordpress users that have administrator, editor or author rights. Associated authors to the course will have the rights to see, modify, delete and publish lessons and quizzes associated to this course.</p>

                    <div class="sp-access-right">
                        <b><?php echo $tr->__("Right Access"); ?>:</b>
                        <p class="text-help"><img src="<?php echo __ROOT_PLUGIN__2 ?>/images/acces.png" alt="Right acces" /><span>This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.</span></p>
                    </div>
                </li>
                <li>
                    <h4>2- <?php echo $tr->__("Course detail"); ?>:</h4>
                    <p>You can in this page modify the course name and description, associated image, categories and authors. If you have installed buddypress, you can also associate the course to multiple groups. Thus, after each lesson or quiz publication, a message is sent to buddypress groups that are related to their corresponding course.</p>
                    <p>Categories present in this page are the categories that you have defined as post categories on Wordpress. The link between them and courses can help the creation of the web site menu.</p>
                    <p>Authors list present in this page contains all Wordpress users that have administrator, editor or author rights. Associated authors to the course will have the rights to see, modify, delete and publish lessons and quizzes associated to this course.</p>
                    <p>At the right of this page you can organize the order of your activities (lessons and quizzes) related the course.</p>

                    <div class="sp-access-right">
                        <b><?php echo $tr->__("Right Access"); ?>:</b>
                        <p class="text-help"><img src="<?php echo __ROOT_PLUGIN__2 ?>/images/acces.png" alt="Right acces" /><span>This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights. Authors and editors can only modify some information about the course.</span></p>
                    </div>


                </li>
            </ul>
        </div>
    </div>




    <div class="row">
        <div class="col-md-10 col-md-offset-1 panel">
            <h3><?php echo $tr->__("Lesson"); ?></h3>
            <hr/>
            <ul>
                <li>
            <h4>1- <?php echo $tr->__("Lessons list"); ?>:</h4>
            <p>This page contains the list of all lessons that you have the right to access. This page contains also a quick lesson creator, in which you can fill in the lesson name and associate an image, a course and an attached file. Courses present in the drop down list are courses for which you have access.</p>
            <p>This page allows also publishing, removing or deleting a lesson. When you publish a lesson, a post which contains this lesson is created. When you remove a lesson, the associated post is deleted. The click on Delete button will delete the lesson and the associated post.</p>
            <div class="sp-access-right">
                <b><?php echo $tr->__("Right Access"); ?>:</b>
                <p class="text-help"><img src="<?php echo __ROOT_PLUGIN__2 ?>/images/acces.png" alt="Right acces" /><span>This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.</span></p>
            </div>
                </li>
                <li>
                    <h4>2- <?php echo $tr->__("Lesson detail"); ?>:</h4>
                    <p>You can in this page modify the lesson name, duration, description, associated image and associated course. You can also add tags and glossary entries.</p>
                    <p>Courses present in the drop down list are courses for which you have access.</p>
                    <p>If you want to integrate a lesson in a Wordpress page you have to just copy past the short code into this Wordpress page.</p>
                    <p>At the right of this page you can create, delete, modify and organize the slides related to the lesson. A rich text editor (WYSIWYG editor) is integrated in the slide creator. You can add multimedia files to your presentation slides as youtube videos, photos, ...</p>
                    <div class="sp-access-right">
                        <b><?php echo $tr->__("Right Access"); ?>:</b>
                        <p class="text-help"><img src="<?php echo __ROOT_PLUGIN__2 ?>/images/acces.png" alt="Right acces" /><span>This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.</span></p>
                    </div>

                </li>
            </ul>
        </div>
    </div>


    <div class="row">
        <div class="col-md-10 col-md-offset-1 panel">
            <h3><?php echo $tr->__("Quiz"); ?></h3>
            <hr/>
            <ul>
                <li>
                    <h4>1- <?php echo $tr->__("Quiz list"); ?>:</h4>
                    <p>This page contains the list of all quizzes that you have the right to access. This page contains also a quick quiz creator, in which you can fill in the quiz name and associate an image and a course. Courses present in the drop down list are courses for which you have access.</p>
                    <p>This page allows also publishing, removing or deleting a quiz and seeing the results of users that have taken quizzes.</p>
                    <p>When you publish a quiz, a post which contains this quiz is created. When you remove a quiz, the associated post is deleted. The click on Delete button will delete the quiz and the associated post.</p>

                    <div class="sp-access-right">
                        <b><?php echo $tr->__("Right Access"); ?>:</b>
                        <p class="text-help"><img src="<?php echo __ROOT_PLUGIN__2 ?>/images/acces.png" alt="Right acces" /><span>This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.</span></p>
                    </div>
                </li>
                <li>
                    <h4>2- <?php echo $tr->__("Quiz detail"); ?>:</h4>
                    <p>You can in this page modify the quiz name, duration, description, associated image and associated course. You can also add tags and glossary entries.</p>
                    <p>Courses present in the drop down list are courses for which you have access.</p>
                    <p>If you want to integrate a quiz in a Wordpress page you have to just copy past the short code into this Wordpress page.</p>
                    <p>At the right of this page you can create, delete, modify and organize the questions related to the quiz. You can create 2 types of questions: Multiple Choices Questions, and Unique Choice Questions. In both you can specify your question and multiple propositions that will be displayed to your web site user. In the first one you can select multiple possible answers and in the second you can select only one possible answer.</p>

                    <div class="sp-access-right">
                        <b><?php echo $tr->__("Right Access"); ?>:</b>
                        <p class="text-help"><img src="<?php echo __ROOT_PLUGIN__2 ?>/images/acces.png" alt="Right acces" /><span>This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.</span></p>
                    </div>

                </li>
            </ul>
        </div>
    </div>



    <div class="row">
        <div class="col-md-10 col-md-offset-1 panel">
            <h3><?php echo $tr->__("Parameters"); ?></h3>
            <hr/>
            <ul>
                <li>
                    <h4>1- <?php echo $tr->__("Settings"); ?>:</h4>
                    <p>You can use this setting to configure the following options:</p>
                    <ul style='list-style :disc;margin:20px'>
                        <li><b>Show rating:</b><br/> display the rating (5 stars) at the end of each lesson and quiz;</li>
                        <li><b>Responsive player:</b><br/> the lesson or quiz player size follow the width of the page;</li>
                        <li><b>show Tags in player:</b><br/> Display the "Tags" tab in the lesson and quiz player;</li>
                        <li><b>show glossary in player:</b><br/> Display the "Glossary" tab in the lesson ans quiz player;</li>
                        <li><b>Size of player:</b><br/> Set the size of the lesson and quiz player. Three preconfigured sizes are proposed;</li>
                        <li><b>Share automatically lessons and quizzes when published on buddypress:</b><br/> activate the sharing option of lessons and quizzes on buddypress (an integrated social network in wordpress)</li>
                        <li><b>Users can share lessons and quizzes on socials network:</b><br/> activate the sharing option of lessons and quizzes on social networks (facebook, twitter, g+ and linkedin)</li>
                        <li><b>Users can share lessons and quizzes on buddypress:</b><br/> activate the sharing option of quizzes result on buddypress</li>
                    </ul>

                    <div class="sp-access-right">
                        <b><?php echo $tr->__("Right Access"); ?>:</b>
                        <p class="text-help"><img src="<?php echo __ROOT_PLUGIN__2 ?>/images/acces.png" alt="Right acces" /><span>This feature is available for users who have <b>administrator</b> rights.</span></p>
                    </div>
                </li>
                <li>
                    <h4>2- <?php echo $tr->__("Domains"); ?>:</h4>
                    <p>You can use domains to activate multi criteria ratings of lessons and quizzes. If no domain is defined, web site users can only rate the quality of the lesson or the quiz.</p>
                    <div class="sp-access-right">
                        <b><?php echo $tr->__("Right Access"); ?>:</b>
                        <p class="text-help"><img src="<?php echo __ROOT_PLUGIN__2 ?>/images/acces.png" alt="Right acces" /><span>This feature is available for users who have <b>administrator</b> rights.</span></p>
                    </div>

                </li>
            </ul>
        </div>
    </div>

    </div>