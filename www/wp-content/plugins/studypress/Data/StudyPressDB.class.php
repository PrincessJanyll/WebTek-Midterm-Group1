<?php


class StudyPressDB {

    public static function getPrefix(){
        global $wpdb;
        return $wpdb->prefix;
    }


    public static function getCurrentDateTime(){
        return date('Y-m-d H:i:s');
    }



    private static $tableCategoryWP = "terms";

    public static function getTableNameCategoryWP(){
        return self::getPrefix() . self::$tableCategoryWP;
    }


    private static $tablePostWP = "posts";

    public static function getTableNamePostWP(){
        return self::getPrefix() . self::$tablePostWP;
    }



    //=================================================== Table

    private static $tableUsersWP = "users";

    public static function getTableNameUsersWP(){
        return self::getPrefix() . self::$tableUsersWP;
    }


    /**
     *
     */
    const COL_ID_USER_WP = "ID";


    //=================================================== Table

        private static $tableCourse = "studypress_course";

        public static function getTableNameCourse(){
            return self::getPrefix() . self::$tableCourse;
        }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_COURSE = "course_id";
    /**
     *
     */
    const COL_NAME_COURSE = "name";
    /**
     *
     */
    const COL_DESCRIPTION_COURSE = "description";
    /**
     *
     */
    const COL_AVANCEMENT_COURSE = "avancement";
    /**
     *
     */
    const COL_PICTURE_COURSE = "picture_id";
    /**
     *
     */
    const COL_ID_POST_COURSE = "post_id";





    //=================================================== Table

        private static  $tableActivity = "studypress_activity" ;

        public static function getTableNameActivity(){
            return self::getPrefix() . self::$tableActivity;
        }

    //================================================== Columns
        /**
         *
         */
        const COL_ID_ACTIVITY = "id";
        /**
         *
         */
        const COL_ID_COURSE_ACTIVITY = "course_id";
        /**
         *
         */
        const COL_NAME_ACTIVITY = "name";
        /**
         *
         */
        const COL_DURATION_ACTIVITY = "duration";
        /**
         *
         */
        const COL_AUTEUR_ACTIVITY = "author";
        /**
         *
         */
        const COL_DESCRIPTION_ACTIVITY = "description";
        /**
         *
         */
        const COL_TAGS_ACTIVITY = "tags";
        /**
         *
         */
        const COL_GLOSSARY_ACTIVITY = "glossary";
        /**
         *
         */
        const COL_PICTURE_ACTIVITY = "picture_id";
        /**
         *
         */
        const COL_SHORT_CODE_ACTIVITY = "shortcode";
        /**
         *
         */
        const COL_ID_AUTEUR_ACTIVITY = "author_id";
        /**
         *
         */
        const COL_ID_POST_ACTIVITY = "post_id";
        /**
         *
         */
        const COL_TYPE_ACTIVITY = "type";
        /**
         *
         */
        const COL_ORDER_ACTIVITY = "order_activity";


        private static $tableSlide = "studypress_slide";

        public static function getTableNameSlide(){
                return self::getPrefix() . self::$tableSlide;
            }

    //================================================== Columns
        /**
         *
         */
        const COL_ID_SLIDE = "id";
        /**
         *
         */
        const COL_ID_LESSON_SLIDE = "lesson_id";
        /**
         *
         */
        const COL_NAME_SLIDE = "name";
        /**
         *
         */
        const COL_CONTENT_SLIDE = "content";
        /**
         *
         */
        const COL_ORDER_SLIDE = "order_slide";



    private static $tableCourseCategory = "studypress_course_category";

    public static function getTableName_CourseCategory(){
        return self::getPrefix() . self::$tableCourseCategory;
    }

    /**
     *
     */
    const COL_ID_CATEGORY_WP = "term_id";

    //================================================== Columns
    /**
     *
     */
    const COL_ID_CATEGORY_CAT_N_COURSE = "term_id";
    /**
     *
     */
    const COL_ID_COURSE_CAT_N_COURSE = "course_id";


    //=================================================== Table

    private static $tableCourseUsers = "studypress_course_users";

    public static function getTableName_CourseUsers(){
        return self::getPrefix() . self::$tableCourseUsers;
    }




    //================================================== Columns
    /**
     *
     */
    const COL_ID_USERS_USERS_N_COURSE = "user_id";
    /**
     *
     */
    const COL_ID_COURSE_USERS_N_COURSE = "course_id";


    
    private static $tableVisite = "studypress_visite";

    public static function getTableNameVisite(){
        return self::getPrefix() . self::$tableVisite;
    }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_VISITE = "id";
    /**
     *
     */
    const COL_ID_ACTIVITY_VISITE = "activity_id";
    /**
     *
     */
    const COL_IP_VISITE = "ip";
    /**
     *
     */
    const COL_DATE_VISITE = "date_visite";
    /**
     *
     */
    const COL_ID_USER_VISITE = "user_id";

   
    private static $tableDomain = "studypress_domain";

    public static function getTableNameDomain(){
        return self::getPrefix() . self::$tableDomain;
    }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_DOMAIN = "id";
    /**
     *
     */
    const COL_NAME_DOMAIN = "name";
    /**
     *
     */
    const COL_DESCRIPTION_DOMAIN = "description";




    private static $tableRateQuality = "studypress_rate_quality";

    public static function getTableNameRateQuality(){
        return self::getPrefix() . self::$tableRateQuality;
    }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_RATE_QUALITY = "id";
    /**
     *
     */
    const COL_ID_ACTIVITY_RATE_QUALITY = "activity_id";
    /**
     *
     */
    const COL_VALUE_RATE_QUALITY = "value";
    /**
     *
     */
    const COL_DATE_RATE_QUALITY = "rate_date";
    /**
     *
     */
    const COL_ID_USER_RATE_QUALITY = "user_id";
    /**
     *
     */
    const COL_IP_RATE_QUALITY = "ip";



   
    private static $tableRateDomain = "studypress_rate_domain";

    public static function getTableNameRateDomain(){
        return self::getPrefix() . self::$tableRateDomain;
    }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_RATE_DOMAIN = "id";
    /**
     *
     */
    const COL_ID_ACTIVITY_RATE_DOMAIN = "activity_id";
    /**
     *
     */
    const COL_VALUE_RATE_DOMAIN = "value";
    /**
     *
     */
    const COL_DATE_RATE_DOMAIN = "rate_date";
    /**
     *
     */
    const COL_ID_USER_RATE_DOMAIN = "user_id";
    /**
     *
     */
    const COL_ID_DOMAIN_RATE_DOMAIN = "domain_id";
    /**
     *
     */
    const COL_IP_RATE_DOMAIN = "ip";

   

    private static $tableQuestions = "studypress_questions";

    public static function getTableNameQuestions(){
        return self::getPrefix() . self::$tableQuestions;
    }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_QUESTION = "id";
    /**
     *
     */
    const COL_ID_QUIZ_QUESTION = "quiz_id";
    /**
     *
     */
    const COL_CONTENT_QUESTION = "content";
    /**
     *
     */
    const COL_ORDER_QUESTION = "order_question";
    /**
     *
     */
    const COL_TYPE_QUESTION = "type_question";


   

    private static $tablePropositions = "studypress_propositions";

    public static function getTableNamePropositions(){
        return self::getPrefix() . self::$tablePropositions;
    }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_PROPOSITION = "id";
    /**
     *
     */
    const COL_ID_QUESTION_PROPOSITION = "question_id";
    /**
     *
     */
    const COL_CONTENT_PROPOSITION = "content";
    /**
     *
     */
    const COL_ORDER_PROPOSITION = "order_proposition";
    /**
     *
     */
    const COL_TYPE_PROPOSITION = "type";
    /**
     *
     */
    const COL_COL_PROPOSITION = "col";

   
    private static $tableResultQuiz = "studypress_quiz_result";

    public static function getTableNameResultQuiz(){
        return self::getPrefix() . self::$tableResultQuiz;
    }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_RESULT = "id";
    /**
     *
     */
    const COL_ID_QUIZ_RESULT = "quiz_id";
    /**
     *
     */
    const COL_ID_USER_RESULT = "user_id";
    /**
     *
     */
    const COL_DATE_RESULT = "result_date";
    /**
     *
     */
    const COL_RESPONSE_RESULT = "response";
    /**
     *
     */
    const COL_NOTE_RESULT = "note";
    /**
     *
     */
    const COL_NBR_QUESTIONS_RESULT = "nbr_questions";
    /**
     *
     */
    const COL_NBR_CORRECTS_RESULT = "nbr_corrects";
    /**
     *
     */
    const COL_DATE_BEGIN_RESULT = "begin_date";
    /**
     *
     */
    const COL_VALIDATE_RESULT = "validate";

    

    private static $tableConfiguration = "studypress_configuration";

    public static function getTableNameConfiguration(){
        return self::getPrefix() . self::$tableConfiguration;
    }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_CONFIG = "id";
    /**
     *
     */
    const COL_NAME_CONFIG = "name";
    /**
     *
     */
    const COL_VALUE_CONFIG= "value";

    

    private static $tableGroupCourse = "studypress_gcourse";

    public static function getTableName_GroupCourse(){
        return self::getPrefix() . self::$tableGroupCourse;
    }

    //================================================== Columns
    /**
     *
     */
    const COL_ID_GROUP = "id";
    /**
     *
     */
    const COL_ID_COURSE_GROUP = "course_id";
    /**
     *
     */
    const COL_ID_GROUP_BP= "group_bp_id";









} 