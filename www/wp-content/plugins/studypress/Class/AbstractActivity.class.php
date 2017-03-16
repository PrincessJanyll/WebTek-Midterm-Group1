<?php


abstract class AbstractActivity {

    protected $_id;
    protected $_name;
    protected $_author;
    protected $_description;
    protected $_duration;
    protected $_tags;
    protected $_glossary;
    protected $_pictureUrl;
    protected $_shortCode;
    protected $_authorId;
    protected $_courseId;
    protected $_postId ;



    /**
     *
     ***Constructor****
     *
     *
     *
     */


    public function __construct(array $d)
    {
        $this->hydrate($d);
    }

    public function hydrate(array $d)
    {
        foreach ($d as $key => $value) {

            $method = 'set' . ucfirst($key);

            if (method_exists($this,$method)) {

                $this->$method($value);
            }
        }
    }



    public function getId() {
        return $this->_id;
    }


    public function setId($id) {
        $this->_id = $id;
        return $this;
    }



    public function getName() {
        return stripslashes($this->_name);
    }

    public function getNiceName(){
        $points = strlen($this->getName())>28?"...":"";
        return stripslashes(substr($this->getName(),0,28) . $points) ;
    }



    public function setName($name) {
        $this->_name = $name;
        return $this;
    }


    public function getAuthor() {
        return $this->_author;
    }


    public function setAuthor($author) {
        $this->_author = $author;
        return $this;
    }



    public function getDescription() {
        return stripslashes($this->_description);
    }


    public function setDescription($description) {
        $this->_description = $description;
        return $this;
    }

    public function getNiceDescription(){

        $points = (strlen($this->_description)>90)?"...":"";
        return stripslashes(substr($this->_description,0,90) . $points);
    }


    public function getDuration() {
        //return $this->_duration;
       if (!is_null($this->_duration))
          return $this->_duration;
       return 0;
    }


    public function setDuration($duration) {
        $this->_duration = $duration;
        return $this;
    }


    public function getTags() {
        if(json_decode($this->_tags) !== null)
        {
            $notes = json_decode($this->_tags);
            foreach ($notes as $key => $val)
            {
                $notes[$key] = stripslashes($val);
            }

            return $notes;

        }

        return array();
    }

    public function getTagsJson() {
        return $this->_tags;
    }


    public function setTags($note) {
        $this->_tags = $note;
        return $this;
    }


    public function getGlossary() {


        if(($glossaries = (array) json_decode($this->_glossary)) !== array())
        {


            $n = array();
            foreach ($glossaries['name'] as $k => $g)
            {
                $n['name'][$k] = stripslashes($g);
            }

            foreach ($glossaries['desc'] as $k => $g)
            {
                $n['desc'][$k] = stripslashes($g);
            }

            return  (object) $n;

        }
        return (object) array('name' => array(),'desc' => array());
    }

    public function getGlossaryJson() {
        return $this->_glossary;
    }


    public function setGlossary($glossary) {
        $this->_glossary = $glossary;
        return $this;
    }


    public function getPictureUrl() {
        return $this->_pictureUrl;
    }




    public function getPicture() {
        if($this->getPictureUrl())
        {
            $image = wp_get_attachment_image_src($this->getPictureUrl(), $size = 'thumbnail');
            $image = $image[0];
        }
        else
        {
            $type = ($this instanceof Lesson)?"lesson":"quiz";
            $image = __ROOT_PLUGIN__2 . "images/$type.png";
        }

        return $image;
    }


    public function setPictureUrl($pictureUrl) {
        $this->_pictureUrl = $pictureUrl;
        return $this;
    }


    public function getShortCode() {
        return $this->_shortCode;
    }


    public function setShortCode($shortCode) {
        $this->_shortCode = $shortCode;
        return $this;
    }


    public function getAuthorId() {
        return $this->_authorId;
    }


    public function setAuthorId($authorId) {
        $this->_authorId = $authorId;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getCourseId()
    {
        return $this->_courseId;
    }

    /**
     * @param mixed $IdCourse
     */
    public function setCourseId($IdCourse)
    {
        $this->_courseId = $IdCourse;
    }



    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->_postId;
    }

    /**
     * @param mixed $isPosted
     */
    public function setPostId($isPosted)
    {
        $this->_postId = $isPosted;
    }



} 