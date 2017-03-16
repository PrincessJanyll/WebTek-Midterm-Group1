<?php

class LessonManager extends AbstractActivityManager {

    private $type = "lesson";


    public function __construct(){
        parent::__construct($this->type);
    }



    public function add(AbstractActivity $lesson)
    {

        $idLesson = parent::add($lesson);


        $lesson->setShortCode("[studypress_lesson id=" . $idLesson . "]");

       
        $this->update($idLesson,$lesson);


        return $idLesson;


    }




    public static function returnedLesson($row)
    {
        return ( ( !empty($row) ) ? new Lesson(parent::returnedArrayActivity($row)) : null );

    }



    public function delete($id)
    {
        $id = (int)$id;
        
        $this->_access->query("START TRANSACTION");


        $manageRateDomain = new RateDomainManager();
        $manageRateQuality = new RateQualityManager();

        $manageRateDomain->deleteByActivityId($id);
        $manageRateQuality->deleteByActivityId($id);




        $manageSlide = new SlideManager();
        $slides = $manageSlide->getSlidesOfLesson($id);
        foreach ($slides as $slide) {
            $manageSlide->delete($slide->id());
        }


       
        $lesson = $this->getById($id);
        if($lesson->getPostId() !== 0){
            $this->unpost($lesson);
        }


        $this->_access->delete(
            StudyPressDB::getTableNameVisite(), 
            array(StudyPressDB::COL_ID_ACTIVITY_VISITE => $id)  
        );


        $this->_access->delete(
            StudyPressDB::getTableNameActivity(), 
            array(StudyPressDB::COL_ID_ACTIVITY => $id)  
        );









        if($this->isError()) {

            $m = $this->getMessageError(); 
            $this->_access->query("ROLLBACK"); 
            $this->_access->setMsgError($m); 
        }
        else
            $this->_access->query("COMMIT");
    }


    
    public function getAllWithoutSlides()
    {

        $result = parent::getAllWithout();

        $lessons = array();

        
        foreach ($result as $row) {

           
            $lesson = self::returnedLesson($row);

            if($lesson)
            {
                array_push($lessons, $lesson);
            }


        }
        return $lessons;


    }

    
    public function getById($id)
    {

        $lesson =  self::returnedLesson(parent::getById($id));

        /*
         * Récupere tous les slides
         */
        if($lesson != null) {
            $manageSlides = new SlideManager();
            $slides = $manageSlides->getSlidesOfLesson($lesson->getId());
            $lesson->setSlides($slides);


        }



        return $lesson;
    }


    public function getLessonByPostId($postId)
    {
       return self::returnedLesson(parent::getActivityByPostId($postId));
    }




    public function getLessonsOfCourse($courseId){


        $result = parent::getActivityOfCourse($courseId);

        $lessons = array();

        foreach ($result as $row) {

            $lesson = self::returnedLesson($row);

            if($lesson)
            {
                array_push($lessons, $lesson);
            }




        }
        return $lessons;

    }


    public function isDone($activityId,$userId){
        $result = $this->_access->getVar($this->_access->prepare("SELECT COUNT(*) FROM " . StudyPressDB::getTableNameVisite() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY_VISITE . " = '%d' AND " . StudyPressDB::COL_ID_USER_VISITE . " = '$userId'", $activityId));

        return ( (int) $result !== 0 ) ? true : false;
    }





} 