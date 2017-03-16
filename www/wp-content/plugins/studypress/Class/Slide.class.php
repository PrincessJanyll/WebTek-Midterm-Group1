<?php



class Slide {
    private $_id;

    private $_courseId;


    private $_name;
    private $_content;
    private $_order;


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

    public function content()
    {
        return $this->_content;
    }

   
    public function setContent($content)
    {
        $this->_content = stripslashes($content);
    }

    public function courseId()
    {
        return $this->_courseId;
    }

    
    public function setCourseId($courseId)
    {
        $this->_courseId = $courseId;
    }

    public function id()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    
    public function name()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getNiceName(){

        $points = strlen($this->name())>28?"...":"";
        return stripslashes(substr($this->name(),0,28) . $points) ;
    }

    
    public function order()
    {
        return $this->_order;
    }

   
    public function setOrder($order)
    {
        $this->_order = $order;
    }





} 