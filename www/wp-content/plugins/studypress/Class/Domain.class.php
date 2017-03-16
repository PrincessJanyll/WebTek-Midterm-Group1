<?php


class Domain {

    private $_id;
    private $_name;
    private $_description;


    
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


    /**
     * @return mixed
     */
    public function getDescription()
    {
        return stripslashes( $this->_description);
    }

    public function getNiceDescription(){

        $points = (strlen($this->_description)>80)?"...":"";
        return stripslashes(substr($this->_description,0,80) . $points);
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return stripslashes($this->_name);
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }
} 