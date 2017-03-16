<?php


abstract class AbstractActivityManager {

    protected  $_access;

    private $type;

    protected function __construct($type){
        $this->_access = new AccessData;
        $this->type = $type;
    }



    protected function add(AbstractActivity $activity)
    {
        $a = array(

            StudyPressDB::COL_NAME_ACTIVITY => $activity->getName(),
            StudyPressDB::COL_DURATION_ACTIVITY => $activity->getDuration(),
            StudyPressDB::COL_AUTEUR_ACTIVITY => $activity->getAuthor(),
            StudyPressDB::COL_DESCRIPTION_ACTIVITY => $activity->getDescription(),
            StudyPressDB::COL_TAGS_ACTIVITY => $activity->getTagsJson(),
            StudyPressDB::COL_GLOSSARY_ACTIVITY => $activity->getGlossaryJson(),
            StudyPressDB::COL_PICTURE_ACTIVITY => $activity->getPictureUrl(),
            StudyPressDB::COL_SHORT_CODE_ACTIVITY => $activity->getShortCode(),
            StudyPressDB::COL_ID_AUTEUR_ACTIVITY => $activity->getAuthorId(),
            StudyPressDB::COL_ID_COURSE_ACTIVITY => $activity->getCourseId(),
            StudyPressDB::COL_ORDER_ACTIVITY => $this->getLastOrder($activity->getCourseId())+1,
            StudyPressDB::COL_TYPE_ACTIVITY => $this->type,
        );

        $this->_access->insert(StudyPressDB::getTableNameActivity(), $a);

        /*
         * Récupere l'Id
         */
        return  $this->_access->getLastInsertId();



    }

    public function getLastOrder($courseId){
        return  $this->_access->getVar("SELECT MAX(".StudyPressDB::COL_ORDER_ACTIVITY.") FROM " . StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '" . $courseId . "'" );
    }


    
    public function update($id, AbstractActivity $activity)
    {
        $this->_access->update(
            StudyPressDB::getTableNameActivity(), //From table lesson
            array(

                StudyPressDB::COL_NAME_ACTIVITY => $activity->getName(),
                StudyPressDB::COL_DURATION_ACTIVITY => $activity->getDuration(),
                StudyPressDB::COL_AUTEUR_ACTIVITY => $activity->getAuthor(),
                StudyPressDB::COL_DESCRIPTION_ACTIVITY => $activity->getDescription(),  //col update
                StudyPressDB::COL_TAGS_ACTIVITY => $activity->getTagsJson(),
                StudyPressDB::COL_GLOSSARY_ACTIVITY => $activity->getGlossaryJson(),
                StudyPressDB::COL_PICTURE_ACTIVITY => $activity->getPictureUrl(),
                StudyPressDB::COL_SHORT_CODE_ACTIVITY => $activity->getShortCode(),
                StudyPressDB::COL_ID_AUTEUR_ACTIVITY => $activity->getAuthorId(),
                StudyPressDB::COL_ID_POST_ACTIVITY => $activity->getPostId(),
                StudyPressDB::COL_ID_COURSE_ACTIVITY => $activity->getCourseId()
            ),
            array(StudyPressDB::COL_ID_ACTIVITY => $id)  //Where
        );

       
        if($activity->getPostId() !== 0)
        {
           $this->updatePost($activity);
        }



    }



    
    public static function returnedArrayActivity($row)
    {
        return array(
            'id'          => (int) $row[StudyPressDB::COL_ID_ACTIVITY],
            'name'        =>       $row[StudyPressDB::COL_NAME_ACTIVITY],
            'author'      =>       $row[StudyPressDB::COL_AUTEUR_ACTIVITY],
            'description' =>       $row[StudyPressDB::COL_DESCRIPTION_ACTIVITY],
            'duration'    =>       $row[StudyPressDB::COL_DURATION_ACTIVITY],
            'tags'        =>       $row[StudyPressDB::COL_TAGS_ACTIVITY],
            'glossary'    =>       $row[StudyPressDB::COL_GLOSSARY_ACTIVITY],
            'pictureUrl'  =>       $row[StudyPressDB::COL_PICTURE_ACTIVITY],
            'shortCode'   =>       $row[StudyPressDB::COL_SHORT_CODE_ACTIVITY],
            'authorId'    => (int) $row[StudyPressDB::COL_ID_AUTEUR_ACTIVITY],
            'postId'      => (int) $row[StudyPressDB::COL_ID_POST_ACTIVITY],
            'courseId'    => (int) $row[StudyPressDB::COL_ID_COURSE_ACTIVITY],
         );

    }


    public function isError()
    {
        return ($this->_access->getLastError() == '') ? false : true;
    }

    public function getMessageError()
    {
        return $this->_access->getLastError();
    }


    public function getById($id)
    {
        return $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY . " = '%d' AND " . StudyPressDB::COL_TYPE_ACTIVITY . " = '$this->type' ", $id));
    }



    public function getActivityOfCourse($courseId)
    {

        return $this->_access->getResults($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '%d' AND " . StudyPressDB::COL_TYPE_ACTIVITY . " = '$this->type' ", $courseId));

    }


    public function getActivityByPostId($postId)
    {


        return $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_POST_ACTIVITY . " = '%d' AND " . StudyPressDB::COL_TYPE_ACTIVITY . " = '$this->type' ", $postId));
    }


    public function getAllWithout()
    {

        return $this->_access->getResults("SELECT * FROM " . StudyPressDB::getTableNameActivity() ." WHERE " . StudyPressDB::COL_TYPE_ACTIVITY . " = '$this->type' ORDER BY " . StudyPressDB::COL_ID_ACTIVITY ." DESC " );

    }




        


    public function post(AbstractActivity $activity){

        $managerCourse = new CourseManager();
        $course = $managerCourse->getById($activity->getCourseId());
        $post = array(
            // 'ID'             => [ <post id> ] // Are you updating an existing post?
            'post_content' => $activity->getShortCode(),  // The full text of the post.
            'post_name' => $activity->getName(),// The name (slug) for your post
            'post_title' => $activity->getName(), // The title of your post.
            'post_status' => 'publish',// [ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // Default 'draft'.
            'post_type' => 'post',//[ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] // Default 'post'.
            'post_author' => $activity->getAuthorId(),// The user ID number of the author. Default is the current user ID.

            'post_category' => $course->getCategories(),// Default empty.

            'post_parent' => $course->getPostId(),

            'post_excerpt' => ($activity->getDescription())?$activity->getDescription():$activity->getName(),

            'tags_input'     => $activity->getTags()

        );



        $post_id = PostWP::post($post);




        $activity->setPostId($post_id);

        $this->update($activity->getId(), $activity);

        //Attacher l'image si elle existe...
        $this->attacherImageToPost($activity);


        return $post_id;



    }


    public function updatePost(AbstractActivity $activity){

        $managerCourse = new CourseManager();
        $course = $managerCourse->getById($activity->getCourseId());
        $post = array(
             'ID'             => $activity->getPostId(),// Are you updating an existing post?
            'post_content' => $activity->getShortCode(),  // The full text of the post.
            'post_name' => $activity->getName(),// The name (slug) for your post
            'post_title' => $activity->getName(), // The title of your post.
            'post_status' => 'publish',// [ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // Default 'draft'.
            'post_type' => 'post',//[ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] // Default 'post'.
            'post_author' => $activity->getAuthorId(),// The user ID number of the author. Default is the current user ID.

            'post_category' => $course->getCategories(),// Default empty.

            'post_parent' => $course->getPostId(),

            'post_excerpt' => ($activity->getDescription())?$activity->getDescription():$activity->getName(),

            'tags_input'     => $activity->getTags()

        );

        PostWP::updatePost($post);


        //Attacher l'image si elle existe...
        $this->attacherImageToPost($activity);

    }




    public function unPost(AbstractActivity $activity){


        PostWP::unPost( $activity->getPostId());

        $activity->setPostId(0);

        $this->update($activity->getId(), $activity);
    }




    public function attacherImageToPost(AbstractActivity $activity)
    {
        if($activity->getPictureUrl() && $activity->getPostId()){

            PostWP::setPostPicture(  $activity->getPostId(), $activity->getPictureUrl() );
        }
    }



    public function dettacherImageFromPost(AbstractActivity $activity)
    {
        if(PostWP::hasPostPicture($activity->getId()))
        {
            PostWP::deletePostPicture( $activity->getId() );
        }
    }



    public function setVisitedActivity($userId,$activityId){

        $userId = (int) $userId;
        $activityId = (int) $activityId;
        $visit  = $this->_access->getRow("SELECT ".StudyPressDB::COL_ID_VISITE." FROM " . StudyPressDB::getTableNameVisite() ." WHERE " . StudyPressDB::COL_ID_ACTIVITY_VISITE . " = '$activityId' AND " . StudyPressDB::COL_ID_USER_VISITE ." = '$userId'");
        $currentTime = StudyPressDB::getCurrentDateTime();
        if($visit)
        {
            $this->_access->update(
                StudyPressDB::getTableNameVisite(),
                array(StudyPressDB::COL_DATE_VISITE => $currentTime),
                array(StudyPressDB::COL_ID_VISITE => $visit[StudyPressDB::COL_ID_VISITE])
            );
        }
        else
        {

            $this->_access->insert(
                StudyPressDB::getTableNameVisite(),
                array(
                    StudyPressDB::COL_ID_ACTIVITY_VISITE => $activityId,
                    StudyPressDB::COL_ID_USER_VISITE => $userId,
                    StudyPressDB::COL_DATE_VISITE => $currentTime,
                )
            );
        }

    }

    
    public function updateOrders(array $idSlideOrder){


        $ids = implode(',', array_keys($idSlideOrder));
        $sql = "UPDATE ".StudyPressDB::getTableNameActivity()." SET ".StudyPressDB::COL_ORDER_ACTIVITY." = CASE ".StudyPressDB::COL_ID_ACTIVITY ." ";
        foreach ($idSlideOrder as $id => $ordinal) {
            $sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);
        }
        $sql .= "END WHERE ".StudyPressDB::COL_ID_ACTIVITY ." IN ($ids)";
        $this->_access->query($sql);
    }



    public static function isBuddyPressActive(){
        return function_exists('bp_is_active') && bp_is_active('groups');
    }


    public function shareOnGroupsBP(AbstractActivity $activity,$action,$content){

        if(self::isBuddyPressActive())
        {
            $configuration = new Configuration();
            $configuration = $configuration->getConfig();

            if($configuration['bp_shareActivity'] === 'true')
            {

                $managerCourse = new CourseManager();
                $course = $managerCourse->getById($activity->getCourseId());

                foreach ($course->getGroupsBP() as $groupId) {

                    $activity_id = bp_activity_add(array(
                        'user_id' => $activity->getAuthorId(),
                        'component' => 'groups',
                        'type' => 'activity_update',
                        'action' => $action,
                        'content' => $content,
                        'item_id' => $groupId


                    ));
                }
            }
        }

    }



    public static function isWooCommerceActive(){
        
        return  in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }


}