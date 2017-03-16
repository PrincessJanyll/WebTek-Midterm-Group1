<?php

if ( !defined( 'ABSPATH' ) ) exit;

add_action('admin_print_scripts','STPRS_addCustomScripts_BackEnd');


function STPRS_addCustomScripts_BackEnd(){

    if(!is_admin()) return;

    wp_enqueue_script('bootstrap-min', plugins_url("studypress/js/bootstrap.min.js",__ROOT_PLUGIN__) , array('jquery','jquery-ui-core'),false,true);

}


function insert_opengraph_in_head() {

    global $post;
    if ( !is_singular()) 
        return;

    echo '<meta property="og:title" content="' . get_the_title() . '"/>';
    echo '<meta property="og:url" content="' . get_permalink() . '"/>';
    echo '<meta property="og:description" content="' .strip_tags(get_the_excerpt()) . '" />';
    $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) );
    echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
    echo '<link rel="image_src" href="'. esc_attr( $thumbnail_src[0] ) . '" />';
}
add_action( 'wp_head', 'insert_opengraph_in_head', 5 );



function sp_setExcerpt($idPost,$excerpt){
    $idPost = (int) $idPost;
    $access = new AccessData();
    $access->query($access->prepare("UPDATE " . StudyPressDB::getTableNamePostWP() . " SET post_excerpt ='%s'  WHERE ID = '" . $idPost ."'",$excerpt));
}

function isIdCategoryWpExist($id){
    $id = (int) $id;
    $cat = get_term_by('id', $id, 'category');
    return $cat;
}


add_action( 'init', 'studypress_load_plugin_textdomain' );

function studypress_load_plugin_textdomain()
{

    load_plugin_textdomain( SpTranslate::getDomain(), false, SpTranslate::getPath() );
}



add_action( 'delete_term_taxonomy', 'sp_delete_course_categories' );
function sp_delete_course_categories ( $tt_id )
{

    $access = new AccessData();
    $access->delete(StudyPressDB::getTableName_CourseCategory(),
        array(StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE => $tt_id));
};


add_action( 'delete_user', 'sp_delete_course_users');
function sp_delete_course_users( $userId ) {
    $access = new AccessData();
    $access->delete(StudyPressDB::getTableName_CourseUsers(),
        array(StudyPressDB::COL_ID_USERS_USERS_N_COURSE => $userId));
};


add_filter('sanitize_file_name', 'remove_accents' );



add_shortcode('studypress_lesson','studypress_shortcode_lesson');


function studypress_shortcode_lesson($atts,$content)
{
    global $sp_lecteur;
    $atts = shortcode_atts(array(
        'id' => null
    ),$atts);
    extract($atts);

    if($sp_lecteur < 1)
    {
        require_once __ROOT_PLUGIN__ . "controllers/player-lesson.controller.php";
        $sp_lecteur++;


    }


}



add_shortcode('studypress_quiz','studypress_shortcode_quiz');


function studypress_shortcode_quiz($atts,$content)
{
    global $sp_lecteur;
    $atts = shortcode_atts(array(
        'id' => null
    ),$atts);
    extract($atts);


    if($sp_lecteur < 1)
    {
        require_once __ROOT_PLUGIN__ . "controllers/player-quiz.controller.php";
        $sp_lecteur++;





    }


    }

    
    
    
// Enable font size & font family selects in the editor
if ( ! function_exists( 'wpex_mce_buttons' ) ) {

    function wpex_mce_buttons( $buttons ) {

        array_unshift( $buttons, 'fontselect' ); // Add Font Select

        array_unshift( $buttons, 'fontsizeselect' ); // Add Font Size Select

        return $buttons;

    }

}

add_filter( 'mce_buttons_2', 'wpex_mce_buttons' );

add_action( 'wp_loaded', 'sp_add_post_course');
    function sp_add_post_course() {
    global $tr;

    $labels = array(
        'name' => $tr->__( 'Course' ),
        'singular_name' => $tr->__( 'Course' ),
        'add_new' => $tr->__( 'Add course' ),
        'all_items' => $tr->__( 'All courses' ),
        'add_new_item' => $tr->__( 'Add course' ),
        'edit_item' => $tr->__( 'Edit course' ),
        'new_item' => $tr->__( 'New course' ),
        'view_item' => $tr->__( 'View course' ),
        'search_items' => $tr->__( 'Search courses' ),
        'not_found' => $tr->__( 'No courses found' ),
        'not_found_in_trash' => $tr->__( 'No courses found in trash' ),
        'parent_item_colon' => $tr->__( 'Parent course' )
        
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_in_menu'       => false,
        'publicly_queryable' => true,
        'query_var' => true,
        'rewrite' => true,
        
        'hierarchical' => false,
        'supports' => array(
            
        ),
        
    );
    register_post_type(
        'course',
        $args
    );
};


add_shortcode('studypress_child','studypress_shortcode_child');


function studypress_shortcode_child($atts,$content)
{
    $atts = shortcode_atts(array(
        'id' => null
    ),$atts);
    extract($atts);

    $args = array(
        'numberposts' => -1,
        'order'=> 'DESC',
        'post_parent' => $id,
    );

    
    require_once __ROOT_PLUGIN__ . "Views/course-page.php";


}

