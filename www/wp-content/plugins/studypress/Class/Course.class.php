<?php



class Course
{
    private $_id;
    private $_name;
    private $_description;
    private $_avancement;
    private $_pictureId;
    private $_categories = array();
    private $_lessons= array();
    private $_authors = array();
    private $_groupsBP = array();
    private $_nbreLessons = 0;
    private $_nbrequizs = 0;
    private $_postId;





    public function __construct(array $d)
    {
        $this->hydrate($d);
    }


    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {

            $method = 'set' . ucfirst($key);

            if (method_exists($this,$method)) {

                $this->$method($value);
            }
        }
    }

    public function getDescription()
    {
        return stripslashes($this->_description);
    }

    public function getNiceDescription(){

        $points = (strlen($this->_description)>80)?"...":"";
        return stripslashes(substr($this->_description,0,80) . $points);
    }

    
    public function setDescription($description)
    {
        $this->_description = $description;
    }

   
    public function getId()
    {
        return $this->_id;
    }

    
    public function setId($id)
    {
        $this->_id = $id;
    }

    
    public function getLessons()
    {
        return $this->_lessons;
    }

    
    public function setLessons($lessons)
    {
        $this->_lessons = $lessons;
    }

    
    public function getName()
    {
        return stripcslashes($this->_name);
    }

    
    public function setName($name)
    {
        $this->_name = $name;
    }

   
    public function getPictureId()
    {
        return $this->_pictureId;
    }

    
    public function setPictureId($pictureId)
    {
        $this->_pictureId = $pictureId;
    }




    
    public function getAvancement()
    {
        return $this->_avancement;
    }

    public function setAvancement($avancement)
    {
        $this->_avancement = $avancement;
    }

    
    public function getGroupsBP()
    {
        return $this->_groupsBP;
    }

    
    public function setGroupsBP($groupsBP)
    {
        $this->_groupsBP = $groupsBP;
    }


    public function getNbreLessons()
    {
        return $this->_nbreLessons;
    }

   
    public function getPostId()
    {
        return $this->_postId;
    }

    public function setPostId($postId)
    {
        $this->_postId = $postId;
    }




    public function setNbreLessons($nbreLessons)
    {
        $this->_nbreLessons = $nbreLessons;
    }

    
    public function getNbrequizs()
    {
        return $this->_nbrequizs;
    }

    public function setNbrequizs($nbrequizs)
    {
        $this->_nbrequizs = $nbrequizs;
    }





    public function addLesson(Lesson $lesson){
        $this->_lessons[] = $lesson;
    }

    public function getLessonById($id){
        foreach($this->_lessons as $lesson){
            if($lesson->getId() == $id) return $lesson;
        }
        return null;
    }

    public function removeLesson($id){
        $newLesson = array();
        foreach ($this->_lessons as $LessonSelected) {
            if($id != $LessonSelected->getId()) $newLesson[] = $LessonSelected;
        }

        $this->_courses = $newLesson;

    }

   
    public function getCategories()
    {
        return $this->_categories;
    }


    public function getStringCategories(){
        global $tr;

        if(!count($this->_categories)){
            $allName[] =$tr->__("None");
        }
        else {
            foreach ($this->_categories as $key => $value) {

                $nameCat = isIdCategoryWpExist($value);
                $allName[] = $nameCat->name;
            }
        }
        return implode(", ",$allName);


    }

    public function setCategories($categories)
    {
        $this->_categories = $categories;
    }

    
    public function getAuthors()
    {
        return $this->_authors;
    }

    
    public function setAuthors($authors)
    {
        $this->_authors = $authors;
    }


    public function getStringAuthors(){
        global $tr;

        if(!count($this->_authors)){
            $allName[] =$tr->__("None");
        }
        else {
            foreach ($this->_authors as $authorId) {

                $user = new StudyPressUserWP($authorId);
                $allName[] = $user->displayName();
            }
        }
        return implode(", ",$allName);


    }




}