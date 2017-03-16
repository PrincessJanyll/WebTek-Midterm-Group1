<?php


class CourseManager
{
    private $_access;




    public function __construct()
    {

        $this->_access = new AccessData;

    }

    


    public function add(Course $course)
    {
        $a = array(

            StudyPressDB::COL_NAME_COURSE => $course->getName(),
            StudyPressDB::COL_DESCRIPTION_COURSE => $course->getDescription(),
            StudyPressDB::COL_PICTURE_COURSE => $course->getPictureId(),
        );

       $this->_access->insert(StudyPressDB::getTableNameCourse(), $a);

        
        $idCourse = $this->_access->getLastInsertId();


        $course->setId($idCourse);



        $this->addCategories($idCourse,$course->getCategories());



        $this->addGroupsBP($idCourse,$course->getGroupsBP());

        
        $this->addUsers($idCourse,$course->getAuthors());



        $this->post($course);


        return $idCourse;



    }


    public function deleteAllCategories($id){

        $this->_access->delete(
            StudyPressDB::getTableName_CourseCategory(), 
            array(StudyPressDB::COL_ID_COURSE_CAT_N_COURSE => $id)  
        );

    }

    public function addCategories($idCourse,array $categories)
    {
        foreach ($categories as $category) {
            $a = array(

                StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE => $category,
                StudyPressDB::COL_ID_COURSE_CAT_N_COURSE => $idCourse,
            );

            $this->_access->insert(StudyPressDB::getTableName_CourseCategory(), $a);
        }
    }


    public function deleteAllGroupsBP($id){

        $this->_access->delete(
            StudyPressDB::getTableName_GroupCourse(), 
            array(StudyPressDB::COL_ID_COURSE_GROUP => $id)  
        );

    }


    public function addGroupsBP($courseId,array $groups)
    {
        foreach ($groups as $g) {
            $a = array(

                StudyPressDB::COL_ID_GROUP_BP => $g,
                StudyPressDB::COL_ID_COURSE_GROUP => $courseId,
            );

            $this->_access->insert(StudyPressDB::getTableName_GroupCourse(), $a);
        }
    }




    public function getCategoriesId($idCourse)
    {
        $idCourse = (int) $idCourse;

        $result = $this->_access->getResults($this->_access->prepare(
                "SELECT " . StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE ." FROM " . StudyPressDB::getTableName_CourseCategory() ." WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '%d'",$idCourse)
        );




        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row[StudyPressDB::COL_ID_CATEGORY_CAT_N_COURSE];
        }


        return $ids;
    }



    public function getGroupsBPId($courseId)
    {
        $courseId = (int) $courseId;

        $result = $this->_access->getResults($this->_access->prepare(
                "SELECT " . StudyPressDB::COL_ID_GROUP_BP ." FROM " . StudyPressDB::getTableName_GroupCourse() ." WHERE " . StudyPressDB::COL_ID_COURSE_GROUP . " = '%d'",$courseId)
        );

        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row[StudyPressDB::COL_ID_GROUP_BP];
        }


        return $ids;
    }


    public function deleteAllUsers($id){

        $this->_access->delete(
            StudyPressDB::getTableName_CourseUsers(), 
            array(StudyPressDB::COL_ID_COURSE_CAT_N_COURSE => $id)  
        );

    }

    public function addUsers($idCourse,array $users)
    {
        foreach ($users as $user) {
            $a = array(

                StudyPressDB::COL_ID_USERS_USERS_N_COURSE => $user,
                StudyPressDB::COL_ID_COURSE_CAT_N_COURSE => $idCourse,
            );

            $this->_access->insert(StudyPressDB::getTableName_CourseUsers(), $a);
        }
    }


    public function getUsersId($idCourse)
    {
        $idCourse = (int) $idCourse;

        $result = $this->_access->getResults($this->_access->prepare(
                "SELECT " . StudyPressDB::COL_ID_USERS_USERS_N_COURSE ." FROM " . StudyPressDB::getTableName_CourseUsers() ." WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '%d'",$idCourse)
        );




        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row[StudyPressDB::COL_ID_USERS_USERS_N_COURSE];
        }

        return $ids;
    }




    
    public function update($id, Course $course)
    {
        $this->_access->update(
            StudyPressDB::getTableNameCourse(), 
            array(

                StudyPressDB::COL_NAME_COURSE => $course->getName(),
                StudyPressDB::COL_AVANCEMENT_COURSE => $course->getAvancement(),
                StudyPressDB::COL_PICTURE_COURSE => $course->getPictureId(),
                StudyPressDB::COL_ID_POST_COURSE => $course->getPostId(),
                StudyPressDB::COL_DESCRIPTION_COURSE => $course->getDescription(),  
            ),
            array(StudyPressDB::COL_ID_COURSE => $id)  
        );


        
        $this->deleteAllCategories($id);

        $this->addCategories($id,$course->getCategories());


        
        $this->deleteAllGroupsBP($id);

        $this->addGroupsBP($id,$course->getGroupsBP());


        $this->deleteAllUsers($id);

        $this->addUsers($id,$course->getAuthors());


        $childrens = PostWP::getChildrenPost($course->getPostId());



        foreach ($childrens as $c)  {

            PostWP::updatePost(array(
                "ID" => $c['ID'],
                'post_category' => $course->getCategories()
            ));

        }


        $this->updatePost($course);


    }

    
    public function delete($id)
    {
        $id = (int)$id;

        $course = $this->getById($id);

        if($course)
        {
           
            $this->deleteAllCategories($id);


            $this->deleteAllUsers($id);


            $this->unpost($course);


            


        }



        $this->_access->delete(
            StudyPressDB::getTableNameCourse(), 
            array(StudyPressDB::COL_ID_COURSE => $id)  
        );
    }

   
    public function isError()
    {
        return ($this->_access->getLastError() == '') ? false : true;
    }


    public function getMessageError(){
        return $this->_access->getLastError();
    }

    
    public function getAll()
    {

        $courses = array();
        $result = $this->_access->getResults("SELECT * FROM " .  StudyPressDB::getTableNameCourse() ." ORDER BY " . StudyPressDB::COL_ID_COURSE ." DESC ");

        foreach ($result as $row) {

            $course = self::returnedCourse($row);


            $course->setNbreLessons($this->getNumberOf('lesson',$course->getId()));
            $course->setNbrequizs($this->getNumberOf('quiz',$course->getId()));


            $course->setCategories($this->getCategoriesId($course->getId()));

            $course->setGroupsBP($this->getGroupsBPId($course->getId()));


            $course->setAuthors($this->getUsersId($course->getId()));


            array_push($courses, $course);

        }
        return $courses;


    }





    public function getNumberOf($type,$id)
    {


        return (int) $this->_access->getVar($this->_access->prepare("SELECT COUNT(*) FROM ". StudyPressDB::getTableNameActivity() . " WHERE ". StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '%d' AND " . StudyPressDB::COL_TYPE_ACTIVITY ." = '$type'",$id));
    }




    public static function returnedCourse($row)
    {
        return (
        empty($row)
            ? null :
            new Course(array(
                'id'          => (int) $row[StudyPressDB::COL_ID_COURSE],
                'name'        =>       $row[StudyPressDB::COL_NAME_COURSE],
                'description' =>       $row[StudyPressDB::COL_DESCRIPTION_COURSE],
                'pictureId'   =>       $row[StudyPressDB::COL_PICTURE_COURSE],
                'avancement'  =>       $row[StudyPressDB::COL_AVANCEMENT_COURSE],
                'postId'      => (int) $row[StudyPressDB::COL_ID_POST_COURSE]
            ))
        );
    }

    
    public function getById($id)
    {
        $result = $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameCourse() . " WHERE " . StudyPressDB::COL_ID_COURSE . " = '%d'", $id));


        $course =  self::returnedCourse($result);

        if($course) {

            $course->setNbreLessons($this->getNumberOf('lesson',$course->getId()));
            $course->setNbrequizs($this->getNumberOf('quiz',$course->getId()));

            //Ajouter les categories
            $course->setCategories($this->getCategoriesId($course->getId()));


            //Ajouter les les groupe buddypress
            $course->setGroupsBP($this->getGroupsBPId($course->getId()));


            //Ajouter les users
            $course->setAuthors($this->getUsersId($course->getId()));
        }


        return $course;

    }

    public function getCoursesByAuthor($authorId)
    {

        $user = new StudyPressUserWP($authorId);
        if($user->isAdministrator())
        {
            return $this->getAll();
        }
        $courses = array();
        $result = $this->_access->getResults($this->_access->prepare("SELECT ". StudyPressDB::COL_ID_COURSE_USERS_N_COURSE." FROM " .  StudyPressDB::getTableName_CourseUsers()." WHERE " . StudyPressDB::COL_ID_USERS_USERS_N_COURSE ." = '%d' ORDER BY " . StudyPressDB::COL_ID_COURSE_USERS_N_COURSE ." DESC ",$authorId));

        foreach ($result as $row) {

            $id = $row[StudyPressDB::COL_ID_COURSE_USERS_N_COURSE];

            $course =$this->getById($id);


            array_push($courses, $course);



        }
        return $courses;
    }








    public function hasActivities($courseId)
    {
        $nbr =   $this->_access->getVar("SELECT COUNT(*) FROM " . StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '" . $courseId . "'" );

        $nbr = (int) $nbr;

        return ($nbr !== 0)?true:false;
    }






    public function post(Course $course){


        $post = array(
            // 'ID'             => [ <post id> ] // Are you updating an existing post?
            'post_content' => '',  // The full text of the post.
            'post_name' => $course->getName(),// The name (slug) for your post
            'post_title' => $course->getName(), // The title of your post.
            'post_status' => 'publish',// [ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // Default 'draft'.
            'post_type' => 'course',//[ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] // Default 'post'.
            //'post_author' => $lesson->getAuthorId(),// The user ID number of the author. Default is the current user ID.

            'post_category' => $course->getCategories(),// Default empty.
            'post_excerpt' => ($course->getDescription())?$course->getDescription():$course->getName()

        );

        



        $post_id = PostWP::post($post);


        $post = array(
            'ID'           => $post_id,
            'post_content' => "[studypress_child id=". $course->getId() ."]"
        );


        PostWP::updatePost( $post );

        $course->setPostId($post_id);

        $this->update($course->getId(), $course);

        
    }


    public function updatePost(Course $course){

        $post = array(
            'ID'             => $course->getPostId(),// Are you updating an existing post?
            'post_content' => "[studypress_child id=". $course->getId() ."]",
            'post_name' => $course->getName(),// The name (slug) for your post
            'post_title' => $course->getName(), // The title of your post.
            'post_status' => 'publish',// [ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // Default 'draft'.
            'post_type' => 'course',//[ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] // Default 'post'.
            //'post_author' => $lesson->getAuthorId(),// The user ID number of the author. Default is the current user ID.

            'post_category' => $course->getCategories(),// Default empty.

            'post_excerpt' => ($course->getDescription())?$course->getDescription():$course->getName()

        );

        PostWP::updatePost($post);



    }


    public function unpost(Course $course){
        PostWP::unPost( $course->getPostId() );


    }


    public function attacherImageToPost(Course $course)
    {
        if($course->getPictureId() && $course->getPostId()){

            PostWP::setPostPicture(  $course->getPostId(), $course->getPictureId() );
        }
    }



    public function dettacherImageFromPost(Course $course)
    {
        if(PostWP::hasPostPicture($course->getId()))
        {
            PostWP::deletePostPicture( $course->getId() );
        }
    }


    public function getActivitiesOfCourse($courseId){

        $activities = array();
        $result = $this->_access->getResults($this->_access->prepare("SELECT * FROM " .  StudyPressDB::getTableNameActivity() . " WHERE " . StudyPressDB::COL_ID_COURSE_ACTIVITY . " = '%d' ORDER BY " . StudyPressDB::COL_ORDER_ACTIVITY ." ASC",$courseId));


        foreach ($result as $row) {
            $activity = array();

            if($row[StudyPressDB::COL_TYPE_ACTIVITY] === "lesson")
                $activity = LessonManager::returnedLesson($row);
            else
                $activity = QuizManager::returnedQuiz($row);

            if($activity) $activities[] = $activity;
        }


        return $activities;

    }

}
?>