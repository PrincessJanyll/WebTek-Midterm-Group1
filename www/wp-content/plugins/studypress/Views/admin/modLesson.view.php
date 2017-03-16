<?php




require_once __ROOT_PLUGIN__ ."Views/includeCSS.php";
require_once  __ROOT_PLUGIN__ ."Views/inc/html/help.php";


global $tr;


$confirm = "onclick='return confirm(\"". $tr->__("Do you want to delete this slide?") ."\")'";

?>
<style>
    .loading{
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 1;
        z-index: 1060;
        background: url('<?php echo  __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%,#FFF;
    }
    .float-left{
        float: left;
    }
    .float-right{
        float: right;
        margin: 0 0 0 8px;

    }
    .ui-state-default{
        overflow: hidden;
        position: relative;
    }

    #li-sotable a {
        outline: none;
    }
    #li-sotable,
    #li-non-sortable{
        display: block;
        width: 100%;
        cursor: auto;
        text-align: left;
        margin: 5px 0;

    }

    #li-sotable{
        display: block;
        width: 100%;
        cursor: pointer;
        text-align: left;
        box-shadow: 1px 1px 1px #CCC,0 0 1px #eee;
        border-radius: 0;
        margin: 5px 0;
    }

    #li-sotable:active
    {
        box-shadow: 4px 4px 3px #CCC,0 0 1px #eee;
    }

    #li-sotable{
        cursor: move;
        background: #FAFAFA;
    }


    #li-non-sortable:hover,
    #li-non-sortable:active{
        background: #FFFFFF;

    }


    #sortable-slide{
        min-height: 200px;
    }
    .ui-sortable-placeholder {
        margin: 5px 0;
        border: 2px dashed #CCCCCC;
        height: 34px;
        border-radius: 2px;
        width: 100%;
        background: #EFEFEF;
    }
    .sp-cat{
        width: 100%;
        max-height : 200px;
        overflow: auto;
        padding: 0 10px;
    }
</style>

<h1><?php $tr->_e("Edit the lesson"); ?></h1>

<div class="container-fluid">

    <?php
    sp_display_link_help();
    ?>

    <div class="row">
        <div class="col-md-8">
            <h3><?php echo  $lesson->getName() ?></h3>

            <form action="" method="post" enctype="multipart/form-data" >
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="alert alert-danger" role="alert"
                        <?php echo  ($error_lesson_update=='')?"style=\"display:none\"":"" ?>> <?php echo  $error_lesson_update ?> </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                <label for="name"><?php $tr->_e("Name of the lesson"); ?>* :  </label>
                                <input type="text" autocomplete="off" class="form-control" id="name" name="lesson[name]" required="required"
                                       value="<?php echo  $lesson->getName() ?>"/>
                                    </div>
                                <div class="form-group">
                                    <label for="duree"><?php $tr->_e("Duration (Min)"); ?></label>
                                    <input type="number" class="form-control" id="duree" name="lesson[duree]"
                                           value="<?php echo  $lesson->getDuration() ?>"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><?php $tr->_e("Shortcode"); ?></div>
                                    <div class="panel-body">
                                        [studypress_lesson id=<?php echo $lesson->getId() ?>]
                                        </div>
                            </div>
                            </div>
                        </div>



                    <div class="form-group">
                        <label for="description"><?php $tr->_e("Description"); ?> </label>
                        <textarea  class="form-control" id="description" name="lesson[description]"><?php echo   trim($lesson->getDescription()) ?></textarea>
                    </div>


                    <div class="form-group">
                        <label for="picture"><?php $tr->_e("Associate an image"); ?></label>
                        <div>
                            <a href="#" class="button select-picture"><?php $tr->_e("Browse"); ?></a>
                            <input type="text" id="picture" value="<?php echo  wp_get_attachment_url( $lesson->getPictureUrl() )?>"  size="45" tabindex="1" autocomplete="off" data-toggle="popover" data-trigger="hover"/>

                            <input type="hidden" name="lesson[pictureurl]" value="<?php echo  $lesson->getPictureUrl()?>"/>

                        </div>




                    </div>
                    <div class="form-group">
                        <label for="courseId"><?php $tr->_e("Associate to a course"); ?>*</label>
                        <select name="lesson[courseId]" id="courseId" class="form-control">
                            <?php
                            $userCourse = new StudyPressUserWP($lesson->getAuthorId());
                            $courses = $managerCourse->getCoursesByAuthor($userCourse->id());
                            foreach ($courses as $course) {
                                $selected = ($course->getId()===$lesson->getCourseId())?"selected":"";
                                echo "<option value='".$course->getId()."' " .$selected.">".$course->getName()."</option>";
                            }

                            ?>

                        </select>
                    </div>



                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php $tr->_e("Tags"); ?></div>
                            <div class="panel-heading">
                                <div class="form-group">
                                    <input type="text" autocomplete="off" class="form-control" id="note" name="note" placeholder="Tag..." />
                                </div>
                                <div class="form-group">
                                    <button id="add-new-note" type="button" class="btn btn-success"><?php $tr->_e("Add"); ?></button>
                                </div>
                            </div>
                            <div class="panel-body">

                                <ul id="sortable-note">

                                    <?php

                                    foreach ($lesson->getTags() as $tag) : ?>
                                        <li id='li-non-sortable' class='ui-state-default btn btn-default sp-note'> <span class='float-left' title="<?php echo  str_replace('"',' ',$tag)?>"><?php echo  substr($tag,0,35)?>...</span><a href=''><span class='glyphicon glyphicon-remove float-right delete-note' id="red" aria-hidden='true' title='Supprimer'></span></a><input type='hidden' name='lesson[note][]' value="<?php echo  str_replace('"',' ',$tag)?>" /></li>
                                    <?php endforeach;  ?>


                                </ul>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php $tr->_e("Glossary"); ?></div>
                            <div class="panel-heading">
                                <div class="form-group form-inline">
                                    <input type="text" class="form-control" autocomplete="off" id="glossary" name="glossary-name" placeholder="<?php $tr->_e("Term"); ?>" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" autocomplete="off" id="glossary" name="glossary-desc" placeholder="<?php $tr->_e("Description"); ?>" />
                                </div>
                                <div class="form-group">
                                    <button type="button" id="add-new-glossary" class="btn btn-success"><?php _e("Add"); ?></button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <ul id="sortable-glossary">
                                    <?php
                                    $g = $lesson->getGlossary();
                                    for ($i=0;$i<count($g->name);$i++) : ?>
                                        <li id='li-non-sortable' class='ui-state-default btn btn-default sp-glossary'>
                                            <span class='float-left' title="<?php echo  str_replace('"',' ', $g->name[$i]. " : ".$g->desc[$i])?>"><?php echo  substr("<b>" . $g->name[$i]. "</b>" .": ".$g->desc[$i],0,35)?>...</span>
                                            <a href=''><span class='glyphicon glyphicon-remove float-right delete-glossary' id="red" aria-hidden='true' title='<?php $tr->_e("Delete"); ?>'></span></a>
                                            <input type='hidden' name='lesson[glossary][name][]' value="<?php echo  str_replace('"',' ',$g->name[$i])?>" />
                                            <input type='hidden' name='lesson[glossary][desc][]' value="<?php echo  str_replace('"',' ',$g->desc[$i])?>" />
                                        </li>
                                    <?php endfor;  ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <input type="hidden" name="lesson[id]" value="<?php echo  $_GET['id'] ?>" />
                    <button type="submit" name="update" class="btn btn-primary center-block"><?php $tr->_e("Save changes"); ?></button>
                </div>
        </div>
        </form>

    </div>




    <div class="col-md-4">
        <h3><?php $tr->_e("The slides"); ?></h3>
        <form method="post" action="">
            <div class="panel panel-default">

                <div class="panel-body">
                    <div class="alert alert-danger" role="alert" <?php echo  ($error_lesson_add_slide=='')?"style=\"display:none\"":"" ?>> <?php echo  $error_lesson_add_slide ?> </div>

                <ul id="sortable-slide">

                    <!-- load Slides -->


                </ul>

            </div>
            <div class="panel-footer">
                <button type="button" name="add-new-slide" id="add-new-slide" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><?php $tr->_e("Create a new slide"); ?></button>
                <button type="button" name="update-position" class="btn btn-default" id="update-order" data-loading-text="<?php $tr->_e("Loading..."); ?>" disabled><?php $tr->_e("Save"); ?></button>
            </div>


    </div>
    </form>

</div>
</div>
</div>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="loading hide"></div>

                <div class="alert alert-danger alert-dismissible hide" role="alert">
                    <p><!-- Le message d'erreur --></p>
                </div>

                <div class="form-group">
                    <label for="name"><?php $tr->_e("Name"); ?></label>
                    <input type="text" class="form-control" id="name" name="name" required="required" />
                </div>


                <div class="form-group">
                    <label for="name"><?php $tr->_e("Content"); ?></label>
                    <?php wp_editor("", $id ='content-slide') ?>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php $tr->_e("Close"); ?></button>
                <button type="button" data-loading-text="<?php $tr->_e("Loading..."); ?>" class="btn btn-primary"><?php $tr->_e("Save"); ?></button>
            </div>
        </div>
    </div>
</div>



<?php
$content = "<p>You can in this page modify the lesson name, duration, description, associated image and associated course. You can also add tags and glossary entries.</p>
<p>Courses present in the drop down list are courses for which you have access.</p>
<p>If you want to integrate a lesson in a Wordpress page you have to just copy past the short code into this Wordpress page.</p>
<p>At the right of this page you can create, delete, modify and organize the slides related to the lesson. A rich text editor (WYSIWYG editor) is integrated in the slide creator. You can add multimedia files to your presentation slides as youtube videos, photos, ...</p>";
$msg ="This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.";
sp_display_modal_help($content,$msg);

?>


<script>
var sp_tr_modal_new_slide= "<?php echo $tr->__("Create a new slide"); ?>";
var sp_tr_modal_time_out= "<?php echo $tr->__("Time out ! please retry"); ?>";
var sp_tr_alert_delete_slide= "<?php echo $tr->__("Do you want to delete this slide?"); ?>";
var sp_tr_alert_delete_tag= "<?php echo $tr->__("Do you want to delete this tag ?"); ?>";
var sp_tr_alert_delete_glossary= "<?php echo $tr->__("Do you want to delete this glossary ?"); ?>";
var sp_tr_modal_edit_slide= "<?php echo $tr->__("Edit slide"); ?>";
var sp_tr_tiny_upload_img= "<?php echo $tr->__("Upload an image"); ?>";
var sp_tr_tiny_select_img= "<?php echo $tr->__("Select an image"); ?>";
var sp_picture_url= "<?php echo wp_get_attachment_url( $lesson->getPictureUrl() ) ?>";
var sp_root_plugin= "<?php echo __ROOT_PLUGIN__2 ?>";
</script>

<?php
add_action( 'after_wp_tiny_mce', 'STPRS_after_wp_tiny_mce' );
function STPRS_after_wp_tiny_mce() {
    printf( '<script type="text/javascript" src="%s"></script>',  __ROOT_PLUGIN__2.'js/modLesson.js' );
}

?>
