<?php

/*
Plugin Name:  StudyPress

Text Domain:  studypress

Plugin URI:   https://wordpress.org/plugins/StudyPress

Description:  StudyPress is an elearning authoring tool. With this plugin you can easily create multimedia learning content and publish it as slides in your wordpress pages and posts. It can manage courses, lessons and quizzes. 

Version:      1.1.2

Author:       Mohammed Tadlaoui, Salim Mohamed Saidi, Meryem Bendella, Bensmaine yasser, Bouacha oussama

License:      GPLv2 or later

*/



if ( !defined( 'ABSPATH' ) ) exit;


add_action('admin_menu', 'activate_studypress_plugin');


$sp_lecteur = 0;


define('__ROOT_PLUGIN__',trailingslashit(dirname(__FILE__)));

define('__ROOT_PLUGIN__2',plugin_dir_url(__FILE__));

require_once '_AutoloadClass.php';


require_once 'actions-studypress.php';



$tr = SpTranslate::getInstance();



function activate_studypress_plugin() {

    global $tr;

    $access = new AccessData;


    $table = StudyPressDB::getTableNameCourse();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_COURSE ." BIGINT UNSIGNED AUTO_INCREMENT ,
                    ". StudyPressDB::COL_NAME_COURSE ." VARCHAR(200),
                    ". StudyPressDB::COL_DESCRIPTION_COURSE ." LONGTEXT ,
                    ". StudyPressDB::COL_AVANCEMENT_COURSE ." VARCHAR(255) DEFAULT '0',
                    ". StudyPressDB::COL_PICTURE_COURSE ." VARCHAR(255),
                    ". StudyPressDB::COL_ID_POST_COURSE ." BIGINT DEFAULT NULL,
                     PRIMARY KEY (". StudyPressDB::COL_ID_COURSE .")
                     )";
        $access->query($sql);
    }
   
    $table = StudyPressDB::getTableNameActivity();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_ACTIVITY ." BIGINT UNSIGNED AUTO_INCREMENT ,
                    ". StudyPressDB::COL_ID_COURSE_ACTIVITY ." BIGINT UNSIGNED ,
                    ". StudyPressDB::COL_NAME_ACTIVITY ." VARCHAR(200),
                    ". StudyPressDB::COL_DURATION_ACTIVITY ." INT NOT NULL,
                    ". StudyPressDB::COL_AUTEUR_ACTIVITY ." VARCHAR(200),
                    ". StudyPressDB::COL_DESCRIPTION_ACTIVITY ." longtext ,
                    ". StudyPressDB::COL_TAGS_ACTIVITY ." longtext ,
                    ". StudyPressDB::COL_GLOSSARY_ACTIVITY ." longtext ,
                    ". StudyPressDB::COL_PICTURE_ACTIVITY ." text,
                    ". StudyPressDB::COL_SHORT_CODE_ACTIVITY ." VARCHAR(50),
                    ". StudyPressDB::COL_ID_AUTEUR_ACTIVITY ." BIGINT,
                    ". StudyPressDB::COL_ID_POST_ACTIVITY ." BIGINT DEFAULT NULL,
                    ". StudyPressDB::COL_TYPE_ACTIVITY ." VARCHAR(10),
                    ". StudyPressDB::COL_ORDER_ACTIVITY ." INT NOT NULL,
                     PRIMARY KEY (". StudyPressDB::COL_ID_ACTIVITY ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_COURSE_ACTIVITY .") REFERENCES ". StudyPressDB::getTableNameCourse() ." (". StudyPressDB::COL_ID_COURSE .")
                     )";
        $access->query($sql);
    }
    
    $table = StudyPressDB::getTableNameSlide();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_SLIDE ." BIGINT UNSIGNED AUTO_INCREMENT ,
                    ". StudyPressDB::COL_ID_LESSON_SLIDE ." BIGINT UNSIGNED,
                    ". StudyPressDB::COL_NAME_SLIDE ." VARCHAR(200) ,
                    ". StudyPressDB::COL_CONTENT_SLIDE ." longtext ,
                    ". StudyPressDB::COL_ORDER_SLIDE ." INT NOT NULL,
                     PRIMARY KEY (". StudyPressDB::COL_ID_SLIDE ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_LESSON_SLIDE .") REFERENCES ". StudyPressDB::getTableNameActivity() ." (". StudyPressDB::COL_ID_ACTIVITY .")
                     )";
        $access->query($sql);
    }
    
    $table = StudyPressDB::getTableName_CourseCategory();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_COURSE_CAT_N_COURSE ." BIGINT UNSIGNED,
                    ". StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE ." BIGINT UNSIGNED,
                     PRIMARY KEY (". StudyPressDB::COL_ID_COURSE_CAT_N_COURSE .",". StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE .") REFERENCES
                       ". StudyPressDB::getTableNameCategoryWP() ." (". StudyPressDB::COL_ID_CATEGORY_WP ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_COURSE_CAT_N_COURSE .") REFERENCES
                      ". StudyPressDB::getTableNameCourse() ." (". StudyPressDB::COL_ID_COURSE .")
                      )";
        $access->query($sql);
    }
    
    $table = StudyPressDB::getTableName_CourseUsers();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_COURSE_CAT_N_COURSE ." BIGINT UNSIGNED,
                    ". StudyPressDB::COL_ID_USERS_USERS_N_COURSE ." BIGINT UNSIGNED,
                     PRIMARY KEY (". StudyPressDB::COL_ID_COURSE_ACTIVITY .",". StudyPressDB::COL_ID_USERS_USERS_N_COURSE ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_USERS_USERS_N_COURSE .") REFERENCES
                       ". StudyPressDB::getTableNameUsersWP() ." (". StudyPressDB::COL_ID_USER_WP ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_COURSE_CAT_N_COURSE .") REFERENCES
                      ". StudyPressDB::getTableNameCourse() ." (". StudyPressDB::COL_ID_COURSE .")
                      )";
        $access->query($sql);
    }
    
    $table = StudyPressDB::getTableNameVisite();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql= "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_VISITE ." BIGINT UNSIGNED AUTO_INCREMENT,
  					". StudyPressDB::COL_ID_ACTIVITY_VISITE ." BIGINT UNSIGNED,
 					". StudyPressDB::COL_IP_VISITE ." VARCHAR(40),
  					". StudyPressDB::COL_DATE_VISITE ." DATETIME,
					". StudyPressDB::COL_ID_USER_VISITE ." BIGINT UNSIGNED ,
 					 PRIMARY KEY (". StudyPressDB::COL_ID_VISITE ."),
					 FOREIGN KEY ( ". StudyPressDB::COL_ID_ACTIVITY_VISITE ." ) REFERENCES ". StudyPressDB::getTableNameActivity() ." (". StudyPressDB::COL_ID_ACTIVITY ."),
					 FOREIGN KEY ( ". StudyPressDB::COL_ID_USER_VISITE ." ) REFERENCES ". StudyPressDB::getTableNameUsersWP() ." (". StudyPressDB::COL_ID_USER_WP .")
					 )";
        $access->query($sql);
    }
    

    $table = StudyPressDB::getTableNameDomain();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql= "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_DOMAIN ." BIGINT UNSIGNED AUTO_INCREMENT,
                    ". StudyPressDB::COL_NAME_DOMAIN ." VARCHAR(200) UNIQUE,
 					". StudyPressDB::COL_DESCRIPTION_DOMAIN ." LONGTEXT,
 					PRIMARY KEY (". StudyPressDB::COL_ID_DOMAIN .")
					 )";
        $access->query($sql);
    }
    
    $table = StudyPressDB::getTableNameRateQuality();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql= "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_RATE_QUALITY ." BIGINT UNSIGNED AUTO_INCREMENT,
  					". StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY ." BIGINT UNSIGNED ,
 					". StudyPressDB::COL_VALUE_RATE_QUALITY ." INT NULL,
  					". StudyPressDB::COL_DATE_RATE_QUALITY ." DATETIME,
					". StudyPressDB::COL_ID_USER_RATE_QUALITY ." BIGINT UNSIGNED,
					". StudyPressDB::COL_IP_RATE_QUALITY ." INT UNSIGNED,
 					 PRIMARY KEY (". StudyPressDB::COL_ID_RATE_QUALITY ."),
 					 FOREIGN KEY ( ". StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY ." ) REFERENCES ". StudyPressDB::getTableNameActivity() ." (". StudyPressDB::COL_ID_ACTIVITY ."),
					 FOREIGN KEY ( ". StudyPressDB::COL_ID_USER_RATE_QUALITY ." ) REFERENCES ". StudyPressDB::getTableNameUsersWP() ." (". StudyPressDB::COL_ID_USER_WP .")
					 )";
        $access->query($sql);
    }
    

    $table = StudyPressDB::getTableNameRateDomain();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql= "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_RATE_DOMAIN ." BIGINT UNSIGNED AUTO_INCREMENT,
  					". StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN ." BIGINT UNSIGNED ,
 					". StudyPressDB::COL_VALUE_RATE_DOMAIN ." INT NULL,
  					". StudyPressDB::COL_DATE_RATE_DOMAIN ." DATETIME,
					". StudyPressDB::COL_ID_USER_RATE_DOMAIN ." BIGINT UNSIGNED,
					". StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN ." BIGINT UNSIGNED,
					". StudyPressDB::COL_IP_RATE_DOMAIN ." INT UNSIGNED,
 					 PRIMARY KEY (". StudyPressDB::COL_ID_RATE_DOMAIN ."),
					 UNIQUE ( ". StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN .",". StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN .",". StudyPressDB::COL_ID_USER_RATE_DOMAIN ." ),
					 FOREIGN KEY ( ". StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY ." ) REFERENCES ". StudyPressDB::getTableNameActivity() ." (". StudyPressDB::COL_ID_ACTIVITY ."),
					 FOREIGN KEY ( ". StudyPressDB::COL_ID_USER_RATE_QUALITY ." ) REFERENCES ". StudyPressDB::getTableNameUsersWP() ." (". StudyPressDB::COL_ID_USER_WP ."),
					 FOREIGN KEY ( ". StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN ." ) REFERENCES ". StudyPressDB::getTableNameDomain() ." (". StudyPressDB::COL_ID_DOMAIN .")
					 )";
        $access->query($sql);
    }
   
    $table = StudyPressDB::getTableNameQuestions();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_QUESTION ." BIGINT UNSIGNED AUTO_INCREMENT ,
                    ". StudyPressDB::COL_ID_QUIZ_QUESTION ." BIGINT UNSIGNED,
                    ". StudyPressDB::COL_CONTENT_QUESTION ." TEXT,
                    ". StudyPressDB::COL_ORDER_QUESTION ." INT NOT NULL,
                    ". StudyPressDB::COL_TYPE_QUESTION ." VARCHAR(25) ,
                     PRIMARY KEY (". StudyPressDB::COL_ID_QUESTION ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_QUIZ_QUESTION .") REFERENCES ". StudyPressDB::getTableNameActivity() ." (". StudyPressDB::COL_ID_ACTIVITY .")
                     )";
        $access->query($sql);
    }
    
    $table = StudyPressDB::getTableNamePropositions();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_PROPOSITION ." BIGINT UNSIGNED AUTO_INCREMENT ,
                    ". StudyPressDB::COL_ID_QUESTION_PROPOSITION ." BIGINT UNSIGNED,
                    ". StudyPressDB::COL_CONTENT_PROPOSITION ." TEXT ,
                    ". StudyPressDB::COL_TYPE_PROPOSITION ." VARCHAR(10),
                    ". StudyPressDB::COL_ORDER_PROPOSITION ." INT NOT NULL,
                    ". StudyPressDB::COL_COL_PROPOSITION ." VARCHAR(255) ,
                     PRIMARY KEY (". StudyPressDB::COL_ID_PROPOSITION ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_QUESTION_PROPOSITION .") REFERENCES ". StudyPressDB::getTableNameQuestions() ." (". StudyPressDB::COL_ID_QUESTION .")
                     )";
        $access->query($sql);
    }
    
    $table = StudyPressDB::getTableNameResultQuiz();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_RESULT ." BIGINT UNSIGNED AUTO_INCREMENT ,
                    ". StudyPressDB::COL_ID_QUIZ_RESULT ." BIGINT UNSIGNED,
                    ". StudyPressDB::COL_ID_USER_RESULT ." BIGINT UNSIGNED,
                    ". StudyPressDB::COL_DATE_RESULT ." DATETIME,
                    ". StudyPressDB::COL_RESPONSE_RESULT ." LONGTEXT,
                    ". StudyPressDB::COL_NOTE_RESULT ." INT,
                    ". StudyPressDB::COL_NBR_QUESTIONS_RESULT ." INT,
                    ". StudyPressDB::COL_NBR_CORRECTS_RESULT ." INT,
                    ". StudyPressDB::COL_DATE_BEGIN_RESULT ." VARCHAR(30),
                    ". StudyPressDB::COL_VALIDATE_RESULT ." VARCHAR(6),
                     PRIMARY KEY (". StudyPressDB::COL_ID_RESULT ."),
                     UNIQUE (". StudyPressDB::COL_ID_QUIZ_RESULT .",". StudyPressDB::COL_ID_USER_RESULT ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_QUIZ_RESULT .") REFERENCES ". StudyPressDB::getTableNameActivity() ." (". StudyPressDB::COL_ID_ACTIVITY .")
                     )";
        $access->query($sql);
    }
    
    $table = StudyPressDB::getTableNameConfiguration();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_CONFIG ." BIGINT UNSIGNED AUTO_INCREMENT ,
                    ". StudyPressDB::COL_NAME_CONFIG ." VARCHAR(200),
                    ". StudyPressDB::COL_VALUE_CONFIG ." VARCHAR(10),
                     PRIMARY KEY (". StudyPressDB::COL_ID_CONFIG .")
                     )";
        $access->query($sql);
    }
    
    $table = StudyPressDB::getTableName_GroupCourse();
    if($access->getVar("SHOW TABLES LIKE '$table'") != $table) {
        $sql = "CREATE TABLE $table (
                    ". StudyPressDB::COL_ID_GROUP ." BIGINT UNSIGNED AUTO_INCREMENT,
                    ". StudyPressDB::COL_ID_COURSE_GROUP ." BIGINT UNSIGNED,
                    ". StudyPressDB:: COL_ID_GROUP_BP ." BIGINT UNSIGNED,
                     UNIQUE (". StudyPressDB::COL_ID_GROUP_BP .",". StudyPressDB::COL_ID_COURSE_GROUP ."),
                     PRIMARY KEY (". StudyPressDB::COL_ID_GROUP ."),
                     FOREIGN KEY (". StudyPressDB::COL_ID_COURSE_GROUP .") REFERENCES
                       ". StudyPressDB::getTableNameCourse() ." (". StudyPressDB::COL_ID_COURSE .")
                      )";
        $access->query($sql);
    }
   

    require_once 'Migration/v0.12tov1.0.php';

    $nbrColumnOfCourse =  $access->getResults("DESC " .StudyPressDB::getTableNameCourse());
    if(count($nbrColumnOfCourse)<6)
        require_once 'Migration/1.0-to-1.1.php';

    
    require_once 'notices.php';


    add_menu_page(
        'Cours',
        'studypress',   
        'publish_posts', 
        'id_Cours',  
        'display_courses',
        __ROOT_PLUGIN__2 . 'images/logo.png' ,
        '30' 
    );

    
    add_submenu_page(
        'id_Cours',
        $tr->__("Lessons"),
        $tr->__("Lessons"),
        'publish_posts',
        'id_Cours',
        'display_courses'
    );

    function display_courses(){

        require_once __ROOT_PLUGIN__ . 'controllers/lesson.controller.php';
    }


    add_submenu_page(
        'id_Cours',
        'Quizs',
        'Quizs',
        'publish_posts',
        'quizs',
        'display_quizs'
    );

    function display_quizs(){
        require_once __ROOT_PLUGIN__ . 'controllers/quiz.controller.php';
    }


    add_submenu_page(
        null, 
        'Modifier Quiz',
        'Modifier Quiz',
        'publish_posts',
        'mod-quiz',
        'display_mod_quiz'
    );


    function display_mod_quiz(){
        require_once __ROOT_PLUGIN__ . 'controllers/modQuiz.controller.php';
    }



    add_submenu_page(
        null, 
        'Result Quiz',
        'Result Quiz',
        'publish_posts',
        'result-quiz',
        'display_result_quiz'
    );


    function display_result_quiz(){
        require_once __ROOT_PLUGIN__ . 'controllers/resultQuiz.controller.php';
    }


    add_submenu_page(
        'id_Cours',
        $tr->__('Courses'),
        $tr->__('Courses'),
        'publish_posts',
        'courses',
        'display_categories'
    );

    function display_categories(){
        require_once __ROOT_PLUGIN__ . 'controllers/course.controller.php';
    }


    add_submenu_page(
        null,
        'Modifier Cours',
        'Modifier Cours',
        'publish_posts',
        'mod-course',
        'display_mod_course'
    );


    function display_mod_course(){
        require_once __ROOT_PLUGIN__ . 'controllers/modCourse.controller.php';
    }

    add_submenu_page(
        null, 
        'Modifier Leçon',
        'Modifier Leçon',
        'publish_posts',
        'mod-lesson',
        'display_mod_lesson'
    );


    function display_mod_lesson(){
        require_once __ROOT_PLUGIN__ . 'controllers/modLesson.controller.php';
    }

    add_submenu_page(
        'id_Cours',
        $tr->__('Parameters'),
        $tr->__('Parameters'),
        'manage_options',
        'sp_parameters',
        'display_parameters'
    );

    function display_parameters(){
        require_once __ROOT_PLUGIN__ . 'controllers/configuration.controller.php';
    }

    add_submenu_page(
        'id_Cours',
        $tr->__('Help'),
        $tr->__('Help'),
        'publish_posts',
        'sp_help',
        'display_help'
    );

    function display_help(){
        require_once __ROOT_PLUGIN__ . 'Views/admin/help.view.php';
    }



} 


