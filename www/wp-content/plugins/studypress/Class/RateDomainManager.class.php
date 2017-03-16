<?php


class RateDomainManager {

    private $_access;




    public function __construct()
    {

        $this->_access = new AccessData;

    }

    


    public function add(RateDomain $rate)
    {
        $a = array(

            StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN => $rate->getActivityId(),
            StudyPressDB::COL_VALUE_RATE_DOMAIN => $rate->getValue(),
            StudyPressDB::COL_DATE_RATE_DOMAIN => $rate->getDateRate(),
            StudyPressDB::COL_ID_USER_RATE_DOMAIN => $rate->getUserId(),
            StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN => $rate->getDomainId()

        );


        $this->_access->insert(StudyPressDB::getTableNameRateDomain(), $a);


        $idRate = $this->_access->getLastInsertId();

        return $idRate;

    }



    public function update($id, RateDomain $rate)
    {
        $this->_access->update(
            StudyPressDB::getTableNameRateDomain(), //From table RateDomain
            array(

                StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN => $rate->getActivityId(),
                StudyPressDB::COL_VALUE_RATE_DOMAIN => $rate->getValue(),   //Col update
                StudyPressDB::COL_DATE_RATE_DOMAIN => $rate->getDateRate(),
                StudyPressDB::COL_ID_USER_RATE_DOMAIN => $rate->getUserId(),
                StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN => $rate->getDomainId()
            ),
            array(StudyPressDB::COL_ID_RATE_DOMAIN => $id)  //Where
        );
    }


    public function deleteByActivityId($activityId){

        $activityId = (int)$activityId;
        $this->_access->delete(
            StudyPressDB::getTableNameRateDomain(), //From table RateDomain
            array(StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN => $activityId)
        );
    }

    

    public function delete($id)
    {
        $id = (int)$id;
        $this->_access->delete(
            StudyPressDB::getTableNameRateDomain(), //From table RateDomain
            array(StudyPressDB::COL_ID_RATE_DOMAIN => $id)
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
        $result = $this->_access->getResults("SELECT * FROM " .  StudyPressDB::getTableNameRateDomain());

        foreach ($result as $row) {

            $rat = self::returnedRate($row);
            array_push($rates, $rat);

        }
        return $rates;


    }


    public function getByElement($id)
    {

        $result = $this->_access->getResults($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameRateDomain() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN . " = '%d'", $id));

        $rates = array();

        foreach ($result as $row) {
            $rate =  self::returnedRate($row);
            if($rate) $rates[] = $rate;

        }




        return $rates;

    }


    public function voteExist($activityId,$userId,$domainId)
    {
        $row =   $this->_access->getRow("SELECT * FROM " . StudyPressDB::getTableNameRateDomain() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN . " = '". $activityId ."' AND " .StudyPressDB::COL_ID_USER_RATE_DOMAIN ." = '" . $userId . "'  AND " . StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN ." = '" . $domainId . "'" );

        return self::returnedRate($row);

    }


    public function countRate($activityId,$domainId)
    {
        return  $this->_access->getVar("SELECT COUNT(*) FROM " . StudyPressDB::getTableNameRateDomain() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN . " = '" . $activityId . "'  AND " . StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN ." = '" . $domainId . "'");
    }


    public function AVG($activityId,$domainId){
        $nbr = $this->_access->getVar("SELECT AVG(". StudyPressDB::COL_VALUE_RATE_DOMAIN .") FROM " . StudyPressDB::getTableNameRateDomain() . " WHERE " . StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN . " = '" . $activityId . "'  AND " . StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN ." = '" . $domainId . "'");
        return  ($nbr)?$nbr:0;
    }


    public static function returnedRate($row)
    {
        return (
        empty($row)
            ? null :
            new RateDomain(array(
                'id'       => (int) $row[StudyPressDB::COL_ID_RATE_DOMAIN],
                'activityId'=> (int) $row[StudyPressDB::COL_ID_ACTIVITY_RATE_DOMAIN],
                'dateRate' =>       $row[StudyPressDB::COL_DATE_RATE_DOMAIN],
                'value'    =>       $row[StudyPressDB::COL_VALUE_RATE_DOMAIN],
                'userId'   => (int) $row[StudyPressDB::COL_ID_USER_RATE_DOMAIN],
                'domainId' => (int) $row[StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN],
            ))
        );
    }

} 