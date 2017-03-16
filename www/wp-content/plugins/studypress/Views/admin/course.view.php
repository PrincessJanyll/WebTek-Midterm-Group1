<?php


global $tr;

require_once  __ROOT_PLUGIN__ ."Views/includeCSS.php";
require_once  __ROOT_PLUGIN__ ."Views/inc/html/help.php";




$confirm = "onclick='return confirm(\"". $tr->__("Do you want to delete this / these Course(s)?") ."\")'";



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

    .sp-cat{
        width: 100%;
        height : 140px;
        overflow: auto;
        padding: 0 10px;
        box-shadow: inset 0 0 3px #777;
        border-radius: 5px;
    }

</style>

<h1>
    <?php $tr->_e("Course"); ?>


</h1>

<div class="container-fluid">

<?php
sp_display_link_help();
$user = new StudyPressUserWP();
?>


    <div class="row">

        <div class="<?php echo ($user->isAdministrator())?"col-md-8":"col-md-12" ?>">
            <h3><?php $tr->_e('All courses'); ?></h3>
            <div class="alert alert-danger" role="alert" <?php echo  ($error_course_remove=='')?'style=\'display:none\'':'' ?>> <?php echo  $error_course_remove ?> </div>
            <form action="" method="post" id="sp-reload">


            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <?php echo ($user->isAdministrator())?"<th>#</th>":"" ?>
                    <th><?php $tr->_e('Name'); ?></th>
                    <th><?php $tr->_e('Description'); ?></th>
                    <th><?php $tr->_e('Categories'); ?></th>
                    <th><?php $tr->_e('Authors'); ?></th>
                    <th><?php $tr->_e('Lessons'); ?></th>
                    <th><?php $tr->_e('Quiz'); ?></th>
                </tr>
                </thead>
                <tbody>
                
                <?php

                $__courses = $managerCourse->getCoursesByAuthor($user->id());

                $colSpan = ($user->isAdministrator())?"7":"6";

                if(empty($__courses))
                {
                    echo "<tr><td colspan='".$colSpan."'>". $tr->__('No Courses') ."</td></tr>";
                }
                else {
                foreach ($__courses as $row) {
                    $url_mod_course = "?page=mod-course&id=" . $row->getId();

                    ?>
                    <tr>
                        <?php if($user->isAdministrator()): ?>
                        <td><input type='checkbox' name="id[]" value='<?php echo  $row->getId() ?>'/></td>
                        <?php endif; ?>
                        <td> <a class="update" href="<?php echo  $url_mod_course ?>"><?php echo  $row->getName() ?> </a></td>
                        <td> <?php echo  $row->getNiceDescription() ?></td>
                        <td> <?php echo  $row->getStringCategories() ?></td>
                        <td> <?php echo  $row->getStringAuthors() ?></td>
                        <td> <?php echo  $row->getNbreLessons() ?></td>
                        <td> <?php echo  $row->getNbrequizs() ?></td>
                    </tr>

                    <?php
                }


                ?>
                </tbody>
                <?php if($user->isAdministrator()): ?>
                <tfoot>
                <tr>
                    <td colspan="<?php echo $colSpan ?>">
                        <button type="submit" name="remove" <?php echo  $confirm ?> class="btn btn-danger"><?php $tr->_e('Delete'); ?></button>
                    </td>
                </tr>
                </tfoot>
                <?php
                endif;
                }
                ?>

            </table>
            </form>

        </div>







        <?php if($user->isAdministrator()) : ?>
    <div class="col-md-4">
    <h3><?php $tr->_e('Quick creation of Course'); ?></h3>
    <form method="post" action="">
        <div class="panel panel-default">

            <div class="panel-body">
                <div class="alert alert-danger" role="alert" <?php echo  ($error_course_add=='')?'style=\'display:none\'':'' ?>> <?php echo  $error_course_add ?> </div>

                <div class="form-group">
                    <input type="hidden" name="id" value=""/>
                    <label for="name"><?php $tr->_e("Name"); ?></label>
                    <input type="text" class="form-control" id="name" name="course[name]" required="required" />
                </div>

                <div class="form-group">
                    <label for="desc"><?php $tr->_e("Description"); ?></label>
                    <textarea class="form-control" rows="3" id="desc" name="course[desc]"></textarea>

                </div>



                <div class="form-group">
                    <label for="picture"><?php $tr->_e("Associate an image"); ?></label>
                    <div>
                        <a href="#" class="button select-picture"><?php $tr->_e("Browse"); ?></a>
                        <input type="text" id="picture" value=""  disabled />
                        <input type="hidden" name="course[pictureId]" value="" />
                    </div>
                </div>


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
                        $content .= "name='course[]' /> ";
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

            </div>
            <div class="panel-footer">
                <button type="submit" name="add" class="btn btn-primary center-block"><?php $tr->_e('Validate'); ?></button>

            </div>
        </div>
        </form>
    </div>

        <?php endif; ?>





    </div>



<?php
$content = "<p>This page contains the list of all courses that you have the right to access. This page contains also a quick course creator, in which you can fill in the course name and description, associate an image, categories and authors.</p>
<p>Categories present in this page are the categories that you have defined as post categories on Wordpress. The link between them and courses can help the creation of the web site menu.</p>
<p>Authors list present in this page contains all Wordpress users that have administrator, editor or author rights. Associated authors to the course will have the rights to see, modify, delete and publish lessons and quizzes associated to this course.</p>";

$msg = "This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.";
sp_display_modal_help($content,$msg);
?>







<script>
    (function($) {
        $(document).ready(function () {

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
                        $("input[name='course[pictureId]']").val(attachment.id);
                        $('#picture').val(attachment.url);

                    })
                    .open();
            });
        })
    })(jQuery);
</script>