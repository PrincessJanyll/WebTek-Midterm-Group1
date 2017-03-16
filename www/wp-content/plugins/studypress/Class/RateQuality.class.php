<?php


class RateQuality {

    private $_id;
    private $_value;
    private $_userId;
    private $_activityId;
    private $_dateRate;

    


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


    public function getDateRate()
    {
        return $this->_dateRate;
    }

    
    public function setDateRate($dateRate)
    {
        $this->_dateRate = $dateRate;
    }

   
    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getActivityId()
    {
        return $this->_activityId;
    }

    public function setActivityId($activityId)
    {
        $this->_activityId = $activityId;
    }



    public function setType($type)
    {
        $this->_type = $type;
    }


    public function getUserId()
    {
        return $this->_userId;
    }

    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    public function getValue()
    {
        return $this->_value;
    }

   
    public function setValue($value)
    {
        $this->_value = $value;
    }



} 