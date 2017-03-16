<?php




require_once __ROOT_PLUGIN__ ."Views/includeCSS.php";
require_once  __ROOT_PLUGIN__ ."Views/inc/html/help.php";


$user = new StudyPressUserWP();

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
        opacity: 0.9;
        z-index: 1060;
        background: url('<?php echo  __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%,#FFF;
    }
    .float-left{
        float: left;
    }
    .float-right{
        float: right;
        margin: 0 0 0 8px;
        color: #CCC;

    }
    .ui-state-default{
        overflow: hidden;
        position: relative;
    }

    .sp-cat{
        width: 100%;
        height : 140px;
        overflow: auto;
        padding: 0 10px;
        box-shadow: inset 0 0 3px #777;
        border-radius: 5px;
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
        box-shadow: 1px 1px 1px #CCC,0 0 1px #eee;
        border-radius: 0;
        margin: 5px 0;

    }

    #li-sotable:active,
    #li-non-sortable:active{
        box-shadow: 4px 4px 3px #CCC,0 0 1px #eee;
    }

    #li-sotable{
        cursor: move;
        background: #FAFAFA;
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

<h1><?php $tr->_e("Edit the Course"); ?></h1>

<div class="container-fluid">

<?php

sp_display_link_help();

?>


    <div class="row">
        <div class="col-md-8">
            <h3><?php echo  $course->getName() ?></h3>

            <form action="" method="post" enctype="multipart/form-data" >
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="alert alert-danger" role="alert"
                        <?php echo  ($error_course_update=='')?'style=\'display:none\'':'' ?>"> <?php echo  $error_course_update ?> </div>

                    <?php if($user->isAdministrator()) : ?>
                    <div class="form-group">
                        <label for="name"><?php $tr->_e("The name of the course"); ?>* :  </label>
                        <input type="text" autocomplete="off" class="form-control" id="name" name="course[name]" required="required"
                               value="<?php echo  $course->getName() ?>"/>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="description"><?php $tr->_e("Description"); ?> </label>
                        <textarea  class="form-control" id="description" name="course[description]"><?php echo   trim($course->getDescription()) ?></textarea>
                    </div>


                    <div class="form-group">
                        <label for="picture"><?php $tr->_e("Associate an image"); ?></label>
                        <div>
                            <a href="#" class="button select-picture"><?php $tr->_e("Browse"); ?></a>
                            <input type="text" id="picture" value="<?php echo  wp_get_attachment_url( $course->getPictureId() )?>"  size="45" tabindex="1" autocomplete="off" data-toggle="popover" data-trigger="hover"/>
                            <input type="hidden" name="course[pictureId]" value="<?php echo  $course->getPictureId() ?>"/>

                        </div>




                    </div>




                    <?php if($user->isAdministrator()) : ?>
                    <div class="form-group">
                        <label for=""><?php $tr->_e("Categories"); ?>*</label>
                        <?php
                        $all_cat = get_categories(
                            array(
                                'type'                     => 'post',
                                'child_of'                 => 0,
                                'parent'                   => '',
                                'orderby'                  => 'name',
                                'order'                    => 'ASC',
                                'hide_empty'               => 0,
                                'hierarchical'             => 1,
                                'exclude'                  => '',
                                'include'                  => '',
                                'number'                   => '',
                                'taxonomy'                 => 'category',
                                'pad_counts'               => false

                            )
                        );
                        echo "<div class='sp-cat'>";
                        $droite = "";
                        $gauche = "";
                        $content ="";
                        $i = 0;
                        foreach ($all_cat as $c) {

                            $content  = "<div class='checkbox'>";
                            $content .= "<label>";
                            $content .= "<input type='checkbox' value='".$c->cat_ID."' ";
                            if(in_array($c->cat_ID,$course->getCategories()))
                                $content .=" checked='checked' ";
                            $content .= "name='course[categories][]' /> ";
                            $content .= $c->name;
                            $content .= "</label>" ." </div> ";

                            if($i % 2) $gauche .= $content;
                            else $droite .= $content;
                            $i++;
                        }
                        echo "<div class'row'>";
                        echo "<div class='col-md-6'>" . $droite . "</div>";
                        echo "<div class='col-md-6'>" . $gauche . "</div>";
                        echo "</div>";
                        echo "</div>";

                        ?>
                    </div>





                    <div class="form-group">
                        <label for=""><?php $tr->_e("Authors"); ?>*</label>
                        <?php
                        $args = array(
                            'blog_id'      => $GLOBALS['blog_id'],
                            'role'         => '',
                            'meta_key'     => '',
                            'meta_value'   => '',
                            'meta_compare' => '',
                            'meta_query'   => array(),
                            'include'      => array(),
                            'exclude'      => array(),
                            'orderby'      => 'login',
                            'order'        => 'ASC',
                            'offset'       => '',
                            'search'       => '',
                            'number'       => '',
                            'count_total'  => false,
                            'fields'       => 'all',
                            'who'          => 'authors'
                        );
                        $blogusers = get_users($args);
                        // Array of WP_User objects.
                        echo "<div class='sp-cat'>";
                        $droite = "";
                        $gauche = "";
                        $content ="";
                        $i = 0;
                        foreach ( $blogusers as $user ) {
                            $content  =  "<div class='checkbox'>";
                            $content .= "<label>";
                            $content .= "<input type='checkbox' value='".$user->ID."' ";
                            if(in_array($user->ID,$course->getAuthors()))
                                $content .=" checked='checked' ";
                            $content .= "name='course[users][]' /> ";
                            $content .= $user->display_name ;
                            $content .= "</label>" ." </div> \n";
                            if($i % 2) $gauche .= $content;
                            else $droite .= $content;
                            $i++;
                        }
                        echo "<div class'row'>";
                        echo "<div class='col-md-6'>" . $droite . "</div>";
                        echo "<div class='col-md-6'>" . $gauche . "</div>";
                        echo "</div>";
                        echo "</div>";
                        ?>
                    </div>


                    <div class="form-group">
                        <?php
                        
                        if( function_exists('bp_is_active')  && bp_is_active('groups'))
                        {
                        ?>
                        <label for=""><?php $tr->_e("Groups BuddyPress"); ?>*</label>
                        <?php
                        if ( bp_has_groups() ) : ?>

                            <div class='sp-cat'>
                                <?php
                                $droite ="";
                                $gauche ="";
                                $content ="";
                                $i = 0;
                                while ( bp_groups() ) :
                                    bp_the_group();
                                    $groupId= bp_get_group_id();

                                    $groupName = bp_get_group_name();

                                    $content  =  "<div class='checkbox'>";
                                    $content .= "<label>";
                                    $content .= "<input type='checkbox' value='".$groupId."' ";
                                    if(in_array($groupId,$course->getGroupsBP()))
                                        $content .=" checked='checked' ";
                                    $content .= "name='course[groupsBP][]' /> ";
                                    $content .= $groupName;
                                    $content .= "</label>" ." </div> \n";
                                    if($i % 2) $gauche .= $content;
                                    else $droite .= $content;
                                    $i++;
                                endwhile;
                                echo "<div class'row'>";
                                echo "<div class='col-md-6'>" . $droite . "</div>";
                                echo "<div class='col-md-6'>" . $gauche . "</div>";
                                echo "</div>";

                                ?>
                            </div>

                        <?php else: ?>

                            <div id="message" class="info">
                                <p><?php $tr->_e( 'There were no groups found.') ?></p>
                            </div>

                        <?php endif;?>

                        <?php
                        }
                        ?>
                    </div>


                    <?php else : ?>

                        <div class="row">
                            <div class="col-md-4">
                                <h4><?php echo $tr->__("Authors"); ?>:</h4>
                                <ul class="list-group">
                                    <?php
                                    foreach ($course->getAuthors() as $authorId) {
                                        $userNicename =new StudyPressUserWP($authorId);
                                        echo "<li class='list-group-item'>" .$userNicename->displayName() ."</li>";
                                    }

                                    ?>
                                </ul>
                            </div>



                            <div class="col-md-4">

                                <h4><?php echo $tr->__("Categories"); ?>:</h4>
                                <ul class="list-group">
                                    <?php
                                    foreach ($course->getCategories() as $catId) {
                                        $catName = isIdCategoryWpExist($catId);
                                        echo "<li class='list-group-item'>" .$catName->name ."</li>";
                                    }

                                    ?>
                                </ul>

                            </div>

                            <?php if(AbstractActivityManager::isBuddyPressActive()) : ?>

                            <div class="col-md-4">

                                <h4><?php echo $tr->__("Groups BuddyPress"); ?>:</h4>
                                <ul class="list-group">
                                    <?php
                                    if ( count($course->getGroupsBP()) > 0 ) {
                                        foreach ($course->getGroupsBP() as $groupId) {
                                            $group = $group = groups_get_group( array( 'group_id' => $groupId) );
                                            echo "<li class='list-group-item'>" . $group->name . "</li>";
                                        }
                                    }
                                    else
                                        echo "<li><i>" . $tr->__("No group") . "</i></li>";

                                    ?>
                                </ul>

                            </div>
                            <?php endif; ?>

                        </div>


                    <?php endif; ?>





                </div>

                <div class="panel-footer">
                    <input type="hidden" name="course[id]" value="<?php echo  $_GET['id'] ?>" />
                    <button type="submit" name="update" class="btn btn-primary center-block"><?php $tr->_e("Save changes"); ?></button>
                </div>
        </div>
        </form>

    </div>




    <div class="col-md-4">
        <h3><?php $tr->_e("Order of activities"); ?></h3>
        <form method="post" action="">
            <div class="panel panel-default">

                <div class="panel-body">
                    <div class="alert alert-danger" role="alert" <?php echo  ($error_course_add=='')?'style=\'display:none\'':'' ?>"> <?php echo  $error_course_add ?> </div>

                <ul id="sortable-slide">

                    <!-- load Slides -->


                </ul>

            </div>
            <div class="panel-footer">
                <button type="button" name="update-position" class="btn btn-default center-block" id="update-order" data-loading-text="<?php $tr->_e("Loading..."); ?>" disabled><?php $tr->_e("Save"); ?></button>
            </div>


    </div>
    </form>

</div>
</div>


<?php
$content = "<p>You can in this page modify the course name and description, associated image, categories and authors. If you have installed buddypress, you can also associate the course to multiple groups. Thus, after each lesson or quiz publication, a message is sent to buddypress groups that are related to their corresponding course.</p>
<p>Categories present in this page are the categories that you have defined as post categories on Wordpress. The link between them and courses can help the creation of the web site menu.</p>
<p>Authors list present in this page contains all Wordpress users that have administrator, editor or author rights. Associated authors to the course will have the rights to see, modify, delete and publish lessons and quizzes associated to this course.</p>
<p>At the right of this page you can organize the order of your activities (lessons and quizzes) related the course.</p>";
$msg = "This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights. Authors and editors can only modify some information about the course.";
sp_display_modal_help($content,$msg);
?>


</div>









<script>
    var sp_picture_url= "<?php echo wp_get_attachment_url( $course->getPictureId() ) ?>";
</script>



<script>
(function($) {
    $(document).ready(function(){


        var pictureUrl = sp_picture_url;


        $('#picture').popover({
            placement : 'bottom',
            content : function() {
                return "<img src='" + pictureUrl + "' width='100' height='100'/>";
            },
            html  : true
        });



        function addslashes(str) {
            return str.replace(/\"/g," ");
        }

        var id_course = $('input[name="course[id]"]').val();

        reload_activities();


        $( "#sortable-slide" ).sortable({
            placeholder: "ui-sortable-placeholder"
        });
        $( "#sortable-slide" ).disableSelection();
        $( "#sortable-slide" ).on( "sortupdate", function( event, ui ) {
            $("#update-order").prop("disabled", false);

        } );



        function trimStr(str) {
            return str.replace(/^\s+|\s+$/gm,'');
        }

        $("#update-order").on("click", function ()
        {
            var $btn = $(this);
            $btn.attr('disabled','disabled');
            var order =[];
            $("#sortable-slide li" ).each(function( index,element ) {
                order[index]  = $(element).data("id");
                console.log(index);
            });
            $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/modCourse.controller.php",
                {
                    type: "order-activities",
                    order: order

                }
                //Si y pas d'erreur:
                , function (data) {
                    console.log(data);
                    if(trimStr(data) === "true") {
                        reload_activities();
                    }
                }
            ).error(function(){

                }).always(function(){
                    $btn.removeAttr('disabled');


                });

            return false;

        });


        function reload_activities(){
            var ul =$("#sortable-slide");
            ul.css('background',"url('<?php echo  __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%");
            ul.html("");

            $.post("<?php echo  __ROOT_PLUGIN__2 ?>Views/reload/activities.php",
                {
                    courseId: id_course
                }

                ,function(data){

                    ul.html(data);

                }


            ).error(function(data){



                }).always(function(){
                    ul.css('background',"#FFF");
                    $("#update-order").prop("disabled", true);
                });
        };





        $('.select-picture').click(function(e){
            var $el=$(this).parent();
            e.preventDefault();
            console.log('test');
            var uploader=wp.media({
                title : '<?php echo   $tr->__('Upload an image') ?>',
                button : {
                    text: '<?php echo  $tr->__('Select an image') ?>'
                },
                library :{
                    type : 'image'
                },
                multiple: false
            })
                .on('select',function(){
                    var selection=uploader.state().get('selection');
                    var attachment=selection.first().toJSON();
                    $("input[name='course[pictureId]']").val(attachment.id);
                    $(document.getElementById('picture'),$el).val(attachment.url);
                    pictureUrl = attachment.url;
                })
                .open();
        })
    })







})(jQuery);
</script>