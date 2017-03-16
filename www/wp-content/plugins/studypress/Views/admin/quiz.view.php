<?php


global $tr;

require_once  __ROOT_PLUGIN__. 'Views/includeCSS.php';
require_once  __ROOT_PLUGIN__ ."Views/inc/html/help.php";


$confirm = "onclick='return confirm(\"". $tr->__("Do you want to delete this / these Quiz(zes) ?") ."\")' ";



?>
<style>
    .tr-div:hover .tr-div-remove-modife{
        visibility: visible;
    }
    .tr-div-remove-modife
    {
        visibility: hidden;
    }

    .td-center
    {
        text-align: center;
        font-size: 1.2em;
    }



    .red{
        color: #C9302C;
    }
    .red:hover,.red:active{
        color: #A9000C;
    }
    .sp-cat{
        width: 100%;
        height : 140px;
        overflow: auto;
        padding: 0 10px;
    }

</style>

<h1><?php $tr->_e("Quiz"); ?></h1>

<div class="container-fluid">


    <?php

    sp_display_link_help();

    $currentUser = new StudyPressUserWP();
    $courses = $managerCourse->getCoursesByAuthor($currentUser->id());



    if(!$courses): ?>

    <div class="alert alert-danger" role="alert">
    <?php  $tr->_e("Please contact your administrator for created a course")?>
    </div>

    <?php
    endif;
    ?>
    <div class="row">
        <div class="col-md-8">
            <h3><?php $tr->_e("All quizzes"); ?></h3>
            <div class="alert alert-danger" role="alert" <?php echo  ($error_quiz_remove=='')?'style=\'display:none\'':'' ?>> <?php echo  $error_quiz_remove ?> </div>
            <form action="" method="post">

            <table class="table table-hover table-bordered sortable">
                <thead>

                    <tr>
                        <th data-defaultsort='disabled'>#</th>
                        <th><?php $tr->_e("Name"); ?></th>
                        <th><?php $tr->_e("Course"); ?></th>
                        <th><?php $tr->_e("Author"); ?></th>
                        <th><?php $tr->_e("Publication"); ?></th>
                        <th style="text-align: center"><?php $tr->_e("Result"); ?></th>
                    </tr>

                </thead>
                <tbody>
                
                <?php
                $__quizs = array();
                $currentUser = new StudyPressUserWP();
                if($currentUser->isAdministrator())
                {
                    $__quizs = $managerQuiz->getAllWithoutQuestions();
                }
                else
                {
                    $__courses = $managerCourse->getCoursesByAuthor($currentUser->id());
                    foreach ($__courses as $c) {
                        $_quizs = $managerQuiz->getQuizsOfCourse($c->getId());
                        $__quizs = array_merge($__quizs,$_quizs);
                    }
                }



                if(empty($__quizs))
                {
                    echo "<tr><td colspan='6'>".$tr->__("No quizzes")."</td></tr>";
                }
                else {
                    foreach ($__quizs as $row) :
                        $url_mod_quiz = "?page=mod-quiz&id=" . $row->getId();
                        $url_delete_quiz = "?page=quizs&type=delete&id=" . $row->getId();
                        $url_result_quiz = "?page=result-quiz&id=" . $row->getId();

                        ?>
                        <tr class="tr-div" >
                            <td><input type='checkbox' name="id[]" value='<?php echo  $row->getId() ?>'/></td>
                            <td>
                                <a href="<?php echo  $url_mod_quiz ?>"><b><?php echo  $row->getName() ?></b></a>

                                <div class="tr-div-remove-modife">
                                    <a href="<?php echo  $url_mod_quiz ?>"><?php $tr->_e("Edit"); ?></a> |
                                    <a href=<?php echo  "'" .$url_delete_quiz . "' " . $confirm ?>class="red" ><?php $tr->_e("Delete"); ?></a>
                                </div>

                            </td>

                            <td><?php echo $managerCourse->getById($row->getCourseId())->getName();?></td>
                            <td> <?php echo  $row->getAuthor() ?></td>
                            <td class="col-md-1 td-center td-post" >
                            <?php
                            if($row->getPostId() === 0)
                                echo "<button type='button' id='post-quiz' data-id='"  . $row->getId(). "' class='btn btn-primary'>" . $tr->__("Publish") . "</button>";
                            else
                                echo "<button type='button' id='post-quiz' data-id='"  . $row->getId(). "' class='btn btn-danger'>" . $tr->__("Remove") . "</button>";
                                ?>
                            </td>
                            <td class="col-md-1 td-center">
                                <a href="<?php echo  $url_result_quiz ?>">
                                    <span class="glyphicon glyphicon-new-window"  aria-hidden="true" title="Afficher"></span>
                                </a>
                            </td>
                        </tr>

                <?php
                endforeach
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6">
                        <button type="submit" name="remove" class="btn btn-danger" <?php echo  $confirm ?> ><?php $tr->_e("Delete"); ?> </button>
                    </td>
                </tr>
                </tfoot>
                <?php
                }
                ?>

            </table>
            </form>

        </div>
        <div class="col-md-4">
            <h3><?php $tr->_e("Quick creation of Quiz"); ?></h3>
            <form method="post" action="" enctype="multipart/form-data">
            <div class="panel panel-default">

                <div class="panel-body">
                        <div class="alert alert-danger" role="alert"
                        <?php echo  ($error_quiz_add=='')?'style=\'display:none\'':'' ?>"> <?php echo  $error_quiz_add ?> </div>


                        <div class="form-group">
                            <label for="name"><?php $tr->_e("Name of the quiz"); ?>*</label>
                            <input type="text" class="form-control" id="name" name="quiz[name]" required="required" />
                        </div>


                        <div class="form-group">
                            <label for="courseId"><?php $tr->_e("Associate to a course"); ?>*</label>
                            <select name="quiz[courseId]" id="courseId" class="form-control">
                                <?php
                                foreach ($courses as $course) {
                                    echo "<option value='".$course->getId()."'>".$course->getName()."</option>";
                                }

                                ?>


                            </select>
                        </div>


                        <div class="form-group">
                            <label for="picture"><?php $tr->_e("Associate an image"); ?></label>
                            <div>
                                <a href="#" class="button select-picture"><?php $tr->_e("Browse"); ?></a>
                                <input type="text" id="picture" value=""  disabled />
                                <input type="hidden" name="quiz[pictureurl]" value="" />
                            </div>
                        </div>



                    </div>
                <div class="panel-footer">
                    <button type="submit" name="add" class="btn btn-primary center-block" ><?php $tr->_e("Validate"); ?></button>
                </div>
                </form>
                </div>


</div>
</div>


<?php
$content = "<p>This page contains the list of all quizzes that you have the right to access. This page contains also a quick quiz creator, in which you can fill in the quiz name and associate an image and a course. Courses present in the drop down list are courses for which you have access.</p>
<p>This page allows also publishing, removing or deleting a quiz and seeing the results of users that have taken quizzes.</p>
<p>When you publish a quiz, a post which contains this quiz is created. When you remove a quiz, the associated post is deleted. The click on Delete button will delete the quiz and the associated post.</p>";
$msg = "This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.";
sp_display_modal_help($content,$msg);
?>



<script src="<?php echo  __ROOT_PLUGIN__2 . "js/bootstrap-sortable.js" ?>"></script>
<script>
    (function($) {

        $('.select-picture').click(function(e){
            e.preventDefault();
            var uploader=wp.media({
                title : '<?php echo  $tr->__('Upload an image')?>',
                button : {
                    text: '<?php echo  $tr->__('Select an image')?>'
                },
                library :{
                    type : 'image'
                },
                multiple: false
            })
                .on('select',function(){
                    var selection=uploader.state().get('selection');
                    var attachment=selection.first().toJSON();
                    $("input[name='quiz[pictureurl]']").val(attachment.id);
                    $('#picture').val(attachment.url);

                })
                .open();
        });


    function trimStr(str) {
        return str.replace(/^\s+|\s+$/gm,'');
    }



    $(".td-post").on("click","#post-quiz",function() {
        var id = $(this).data("id");

        var td = $(this).parent(".td-post");
        td.html("");
        td.css('background', "url('<?php echo  __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%");

        $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/post-quiz.php",
            {
                type: "post",
                id: id
            }
            
            , function (data) {
                
                console.log(data);
                if (data.result === "true") {


                }
                else {

                }

            }, 'json').error(function (data) {


            }).always(function (data) {
                td.html(data.value);
                td.css('background', '');
            });
    });

    })(jQuery);
</script>