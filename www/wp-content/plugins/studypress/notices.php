<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 11/03/2015
 * Time: 14:39
 */


$sp_user = new StudyPressUserWP();


if($sp_user->isAdministrator())
{
    add_action('admin_notices', 'sp_notice_menu_course');


    function sp_notice_menu_course()
    {
        $sp_user = new StudyPressUserWP();
        global $tr;

        $user_id = $sp_user->id();
        
        if (!get_user_meta($user_id, 'sp_menu_course_ignore_notice')) : ?>

            <div class="update-nag">
                    <?php echo $tr->__("This is a fresh installation of StudyPress. Don't forget to go to Appearance -> Menus -> Screen Options, and activate 'Course' option. ") . " | <a href='?page=id_Cours&sp_nag_ignore=0'>". $tr->__('Hide Notice') ."</a><br/>" ?>
                    <?php echo $tr->__("If courses does not appear in your web site, on your dashboard, go to 'Settings' -> 'Permalinks' and just click on 'Save changes' without changing anything. "); ?>
            </div>

        <?php endif;

    }


    add_action('admin_init', 'sp_nag_ignore');



    function sp_nag_ignore() {

        $sp_user = new StudyPressUserWP();
        $user_id = $sp_user->id();


        if ( isset($_GET['sp_nag_ignore']) && '0' == $_GET['sp_nag_ignore'] ) {

            add_user_meta($user_id, 'sp_menu_course_ignore_notice', 'true', true);

        }

    }
}



add_action('admin_notices', 'sp_notice_sp_migrate');


function sp_notice_sp_migrate()
{
    $sp_user = new StudyPressUserWP();
    global $tr;

    $user_id = $sp_user->id();

    if(isset($_GET['sp_ignore_migrate']) && $_GET['sp_ignore_migrate'] == '0')
        update_user_meta( $user_id,'sp_menu_add_warning_migrate', false );

    if (get_user_meta($user_id, 'sp_menu_add_warning_migrate',true)) : ?>

        <div class="error">
                <?php echo  $tr->__("This is a fresh installation of StudyPress. Since the version 1.0 contains multiple new functionalities and has a new way to organize lessons and courses, all courses that you have created were assigned to one category. Don't forget to reorganize your courses. ") ." | <a href='?page=id_Cours&sp_ignore_migrate=0'>". $tr->__('Hide Notice') ."</a>" ?>

        </div>

    <?php endif;



}






