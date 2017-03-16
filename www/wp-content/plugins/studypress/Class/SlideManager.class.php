<?php


class SlideManager
{

    private $_access;




    public function __construct()
    {

        $this->_access = new AccessData;

    }



    public function add(Slide $slide)
    {
        $a = array(

            StudyPressDB::COL_NAME_SLIDE => $slide->name(),
            StudyPressDB::COL_CONTENT_SLIDE => $slide->content(),
            StudyPressDB::COL_ID_LESSON_SLIDE => $slide->courseId(),
            StudyPressDB::COL_ORDER_SLIDE => $this->getLastOrder($slide->courseId())+1
        );


        $this->_access->insert(
            StudyPressDB::getTableNameSlide(), $a

        );

    }

    public function getLastOrder($idCourse){
       return  $this->_access->getVar("SELECT MAX(".StudyPressDB::COL_ORDER_SLIDE.") FROM " . StudyPressDB::getTableNameSlide() . " WHERE " . StudyPressDB::COL_ID_LESSON_SLIDE . " = '" . $idCourse . "'" );
    }




   
    public function update($id, Slide $slide)
    {

        $this->_access->update(
            StudyPressDB::getTableNameSlide(), 
            array(

                StudyPressDB::COL_NAME_SLIDE => $slide->name(),
                StudyPressDB::COL_CONTENT_SLIDE => $slide->content(),
                StudyPressDB::COL_ID_LESSON_SLIDE => $slide->courseId(),
                StudyPressDB::COL_ORDER_SLIDE => $slide->order()
            ),
            array(StudyPressDB::COL_ID_SLIDE => $id) 
        );
    }

    
    public function delete($id)
    {
        $id = (int)$id;
        $this->_access->delete(
            StudyPressDB::getTableNameSlide(), 
            array(StudyPressDB::COL_ID_SLIDE => $id)  
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



        $slides = array();
        $result = $this->_access->getResults("SELECT * FROM " . StudyPressDB::getTableNameSlide());
        foreach ($result as $row) {

            $slide = self::returnedSlide($row);
            array_push($slides, $slide);

        }
        return $slides;


    }

    
    public static function returnedSlide($row)
    {
        return (
        empty($row)
            ? null :
            new Slide(array(
                'id'       => (int) $row[StudyPressDB::COL_ID_SLIDE],
                'name'     =>       $row[StudyPressDB::COL_NAME_SLIDE],
                'content'  =>       $row[StudyPressDB::COL_CONTENT_SLIDE],
                'courseId' => (int) $row[StudyPressDB::COL_ID_LESSON_SLIDE],
                'order'    =>       $row[StudyPressDB::COL_ORDER_SLIDE]
            ))
        );
    }

    public function getById($id)
    {
        $result = $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameSlide() . " WHERE " . StudyPressDB::COL_ID_SLIDE . " = '%d'", $id));
        return self::returnedSlide($result);
    }


    public function getSlidesOfLesson($id){
        $slides = array();
        $id = (int) $id;
        $result = $this->_access->getResults($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameSlide() . " WHERE " . StudyPressDB::COL_ID_LESSON_SLIDE . " = '%d' ORDER BY " . StudyPressDB::COL_ORDER_SLIDE ." ASC",$id));
        foreach ($result as $row) {

            $slide = self::returnedSlide($row);
            array_push($slides, $slide);

        }
        return $slides;
    }


    public function getCourse(Slide $slide)
    {

        $result = $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameLesson() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY . " = '%d'", $slide->courseId()));
        return LessonManager::returnedLesson($result);
    }
    
    public function updateOrders(array $idSlideOrder){


        $ids = implode(',', array_keys($idSlideOrder));
        $sql = "UPDATE ".StudyPressDB::getTableNameSlide()." SET ".StudyPressDB::COL_ORDER_SLIDE." = CASE ".StudyPressDB::COL_ID_SLIDE ." ";
        foreach ($idSlideOrder as $id => $ordinal) {
            $sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);
        }
        $sql .= "END WHERE ".StudyPressDB::COL_ID_SLIDE ." IN ($ids)";
        $this->_access->query($sql);
    }


}

?>