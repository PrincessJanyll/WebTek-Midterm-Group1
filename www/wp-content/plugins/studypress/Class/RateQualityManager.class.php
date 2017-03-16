<?php


class RateQualityManager {

    private $_access;




    public function __construct()
    {

        $this->_access = new AccessData;

    }


    public function add(RateQuality $rate)
    {
        $a = array(

            StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY => $rate->getActivityId(),
            StudyPressDB::COL_VALUE_RATE_QUALITY => $rate->getValue(),
            StudyPressDB::COL_DATE_RATE_QUALITY => $rate->getDateRate(),
            StudyPressDB::COL_ID_USER_RATE_QUALITY => $rate->getUserId()

        );


        $this->_access->insert(StudyPressDB::getTableNameRateQuality(), $a);


       
        $idRate = $this->_access->getLastInsertId();

        return $idRate;

    }


    public function update($id, RateQuality $rate)
    {
        $this->_access->update(
            StudyPressDB::getTableNameRateQuality(), 
            array(

                StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY => $rate->getActivityId(),
                StudyPressDB::COL_VALUE_RATE_QUALITY => $rate->getValue(),  
                StudyPressDB::COL_DATE_RATE_QUALITY => $rate->getDateRate(),
                StudyPressDB::COL_ID_USER_RATE_QUALITY => $rate->getUserId()
            ),
            array(StudyPressDB::COL_ID_RATE_QUALITY => $id)  
        );
    }


    public function deleteByActivityId($activityId){

        $activityId = (int)$activityId;
        $this->_access->delete(
            StudyPressDB::getTableNameRateQuality(), 
            array(StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY => $activityId)
        );
    }



    
    public function delete($id)
    {
        $id = (int)$id;
        $this->_access->delete(
            StudyPressDB::getTableNameRateQuality(),
            array(StudyPressDB::COL_ID_RATE_QUALITY => $id) 
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

        $rates = array();
        $result = $this->_access->getResults("SELECT * FROM " .  StudyPressDB::getTableNameRateQuality());

        foreach ($result as $row) {

            $rat = self::returnedRate($row);
            array_push($rates, $rat);

        }
        return $rates;


    }


    
    public function getByElement($id)
    {

        $result = $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameRateQuality() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY . " = '%d'", $id));


        $rate =  self::returnedRate($result);


        return $rate;

    }


    public function voteExist($activityId,$userId)
    {
        $row =   $this->_access->getRow("SELECT * FROM " . StudyPressDB::getTableNameRateQuality() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY . " = '". $activityId ."' AND " . StudyPressDB::COL_ID_USER_RATE_QUALITY ." = '" . $userId . "'" );

        return self::returnedRate($row);

    }


    public function countRate($activityId)
    {
        return  $this->_access->getVar("SELECT COUNT(*) FROM " . StudyPressDB::getTableNameRateQuality() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY . " = '" . $activityId  . "'" );
    }


    public function AVG($activityId){
        $nbr = $this->_access->getVar("SELECT AVG(". StudyPressDB::COL_VALUE_RATE_QUALITY .") FROM " . StudyPressDB::getTableNameRateQuality() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY . " = '" . $activityId .  "'" );
        return  ($nbr)?$nbr:0;
    }


   
    public static function returnedRate($row)
    {
        return (
        empty($row)
            ? null :
            new RateQuality(array(
                'id'         => (int) $row[StudyPressDB::COL_ID_RATE_QUALITY],
                'activityId' => (int) $row[StudyPressDB::COL_ID_ACTIVITY_RATE_QUALITY],
                'dateRate'   =>       $row[StudyPressDB::COL_DATE_RATE_QUALITY],
                'value'      =>       $row[StudyPressDB::COL_VALUE_RATE_QUALITY],
                'userId'     => (int) $row[StudyPressDB::COL_ID_USER_RATE_QUALITY]
            ))
        );
    }

} 