<?php


class Proposition {

    private $_id;
    private $_questionId;
    private $_content;
    private $_type;
    private $_order;

    private $_typeUser;





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

   
    public function getContent()
    {
        return stripslashes($this->_content);
    }

    
    public function setContent($content)
    {
        $this->_content = $content;
    }

    
    
    public function getId()
    {
        return $this->_id;
    }

   
    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getOrder()
    {
        return $this->_order;
    }

   
    public function setOrder($order)
    {
        $this->_order = $order;
    }

    
    public function getTypeUser()
    {
        return $this->_typeUser;
    }

    public function setTypeUser($typeUser)
    {
        $this->_typeUser = $typeUser;
    }


    public function getQuestionId()
    {
        return $this->_questionId;
    }

    
    public function setQuestionId($questionId)
    {
        $this->_questionId = $questionId;
    }

    
    public function getType()
    {
        return $this->_type;
    }

    
    public function setType($type)
    {
        $this->_type = $type;
    }

    

} 