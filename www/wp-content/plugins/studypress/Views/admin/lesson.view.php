<?php

global $tr;

require_once  __ROOT_PLUGIN__. 'Views/includeCSS.php';
require_once  __ROOT_PLUGIN__ ."Views/inc/html/help.php";


$confirm = "onclick='return confirm(\"". $tr->__("Do you want to delete this / these Lesson(s)?") ."\")'";



?>
<style>
    .tr-div:hover .tr-div-remove-modife{
        visibility: visible;
    }
    .tr-div-remove-modife
    {
        visibility: hidden;


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

<h1><?php $tr->_e("Lessons"); ?></h1>

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
            <h3><?php $tr->_e("All lessons"); ?></h3>
            <div class="alert alert-danger" role="alert" <?php echo  ($error_lesson_remove=='')?'style=\'display:none\'':'' ?>"> <?php echo  $error_lesson_remove ?> </div>
            <form action="" method="post">

            <table class="table table-hover table-bordered sortable">
                <thead>

                    <tr>
                        <th data-defaultsort='disabled'>#</th>
                        <th><?php $tr->_e("Name"); ?></th>
                        <th><?php $tr->_e("Course"); ?></th>
                        <th><?php $tr->_e("Author"); ?></th>
                        <th><?php $tr->_e("Publication"); ?></th>
                    </tr>

                </thead>
                <tbody>
                
                <?php
                $__lessons = array();
                $currentUser = new StudyPressUserWP();
                if($currentUser->isAdministrator())
                {
                    $__lessons = $managerLesson->getAllWithoutSlides();
                }
                else
                {
                    $__courses = $managerCourse->getCoursesByAuthor($currentUser->id());
                    foreach ($__courses as $c) {
                        $_lessons = $managerLesson->getLessonsOfCourse($c->getId());
                        $__lessons = array_merge($__lessons,$_lessons);
                    }
                }



                if(empty($__lessons))
                {
                    echo "<tr><td colspan='5'>".$tr->__("No lessons")."</td></tr>";
                }
                else {
                    foreach ($__lessons as $row) :
                        $url_mod_lesson = "?page=mod-lesson&id=" . $row->getId();
                        $url_delete_lesson = "?page=id_Cours&type=delete&id=" . $row->getId();

                        ?>
                        <tr class="tr-div" >
                            <td><input type='checkbox' name="id[]" value='<?php echo  $row->getId() ?>'/></td>
                            <td>
                                <a href="<?php echo  $url_mod_lesson ?>"><b><?php echo  $row->getName() ?></b></a>

                                <div class="tr-div-remove-modife">
                                    <a href="<?php echo  $url_mod_lesson ?>"><?php $tr->_e("Edit"); ?></a> | <a  class="red" href=<?php echo  "'" .$url_delete_lesson . "' " . $confirm ?>><?php $tr->_e("Delete"); ?></a>
                                </div>

                            </td>

                            <td><?php echo $managerCourse->getById($row->getCourseId())->getName();?></td>
                            <td> <?php echo  $row->getAuthor() ?></td>
                            <td class="col-md-1 td-post">
                            <?php
                            if($row->getPostId() === 0)
                                echo "<button type='button' id='post-lesson' data-id='"  . $row->getId(). "' class='btn btn-primary'>" . $tr->__("Publish") . "</button>";
                            else
                                echo "<button type='button' id='post-lesson' data-id='"  . $row->getId(). "' class='btn btn-danger'>" . $tr->__("Remove") . "</button>";
                                ?>
                            </td>
                        </tr>

                <?php
                endforeach
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
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
            <h3><?php $tr->_e("Quick creation of Lesson"); ?></h3>
            <form method="post" action="" enctype="multipart/form-data">
            <div class="panel panel-default">

                <div class="panel-body">
                        <div class="alert alert-danger" role="alert"
                        <?php echo  ($error_lesson_add=='')?'style=\'display:none\'':'' ?>"> <?php echo  $error_lesson_add ?> </div>


                        <div class="form-group">
                            <label for="name"><?php $tr->_e("The name of the lesson"); ?>*</label>
                            <input type="text" class="form-control" id="name" name="lesson[name]" required="required" />
                        </div>


                        <div class="form-group">
                            <label for="courseId"><?php $tr->_e("Associate to a course"); ?>*</label>
                            <select name="lesson[courseId]" id="courseId" class="form-control">
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
                                <input type="hidden" name="lesson[pictureurl]" value="" />
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="fileUrl"><?php $tr->_e("Associate a file (PDF, PTTX ...)"); ?></label>
                            <div>
                                <a href="#" class="button select-file"><?php $tr->_e("Browse"); ?></a>
                                <input type="text" id="fileUrl" value="" />
                                <input type="hidden" id="file" value="" name="lesson[file]" />
                            </div>
                        </div>


                    </div>
                <div class="panel-footer">
                    <button type="submit" name="add" class="btn btn-primary center-block" ><?php $tr->_e("OK"); ?></button>
                </div>
                </form>
                </div>


</div>
</div>


<?php
$content = "<p>This page contains the list of all lessons that you have the right to access. This page contains also a quick lesson creator, in which you can fill in the lesson name and associate an image, a course and an attached file. Courses present in the drop down list are courses for which you have access.</p>
<p>This page allows also publishing, removing or deleting a lesson. When you publish a lesson, a post which contains this lesson is created. When you remove a lesson, the associated post is deleted. The click on Delete button will delete the lesson and the associated post.</p>";
$msg = "This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.";
sp_display_modal_help($content,$msg);
?>

</div>



<script src="<?php echo  __ROOT_PLUGIN__2 . "js/bootstrap-sortable.js" ?>"></script>
<script>
    (function($) {
        $(document).ready(function() {

            $('.select-picture').click(function (e) {
                e.preventDefault();
                var uploader = wp.media({
                    title: '<?php echo  $tr->__('Upload an Image')?>',
                    button: {
                        text: '<?php echo  $tr->__('Select an Image')?>'
                    },
                    library: {
                        type: 'image'
                    },
                    multiple: false
                })
                    .on('select', function () {
                        var selection = uploader.state().get('selection');
                        var attachment = selection.first().toJSON();
                        $("input[name='lesson[pictureurl]']").val(attachment.id);
                        $('#picture').val(attachment.url);

                    })
                    .open();
            });


            $('.select-file').click(function (e) {
                e.preventDefault();
                var uploader = wp.media({
                    title: '<?php echo  $tr->__('Send a file')?>',
                    button: {
                        text: '<?php echo  $tr->__('Select a file')?>'
                    },
                    multiple: false
                })
                    .on('select', function () {
                        var selection = uploader.state().get('selection');
                        var attachment = selection.first().toJSON();
                        $('#file').val(attachment.id);
                        $('#fileUrl').val(attachment.url);

                    })
                    .open();
            });

            function trimStr(str) {
                return str.replace(/^\s+|\s+$/gm, '');
            }


            $(".td-post").on("click", "#post-lesson", function () {
                var id = $(this).data("id");

                var td = $(this).parent(".td-post");
                td.html("");
                td.css('background', "url('<?php echo  __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%");

                $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/post-lesson.php",
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
        });

    })(jQuery);
</script>
