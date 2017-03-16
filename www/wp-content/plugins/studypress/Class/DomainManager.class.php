<?php

class DomainManager {

    private $_access;




    public function __construct()
    {

        $this->_access = new AccessData;

    }



    public function add(Domain $domain)
    {
        $a = array(

            StudyPressDB::COL_NAME_DOMAIN => $domain->getName(),
            StudyPressDB::COL_DESCRIPTION_DOMAIN => $domain->getDescription()

        );


        $this->_access->insert(StudyPressDB::getTableNameDomain(), $a);


        
        $idDomain = $this->_access->getLastInsertId();

        return $idDomain;

    }



    public function update($id, Domain $domain)
    {
        $this->_access->update(
            StudyPressDB::getTableNameDomain(), 
            array(

                StudyPressDB::COL_NAME_DOMAIN => $domain->getName(),
                StudyPressDB::COL_DESCRIPTION_DOMAIN => $domain->getDescription(),  
            ),
            array(StudyPressDB::COL_ID_DOMAIN => $id)  //Where
        );
    }



    
    public function delete($id)
    {
        $id = (int)$id;

        
        $this->_access->delete( StudyPressDB::getTableNameRateDomain(), 
            array(StudyPressDB::COL_ID_DOMAIN_RATE_DOMAIN => $id) 
        );


       
        $this->_access->delete(
            StudyPressDB::getTableNameDomain(), 
            array(StudyPressDB::COL_ID_DOMAIN => $id)  
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

        $domains = array();
        $result = $this->_access->getResults("SELECT * FROM " .  StudyPressDB::getTableNameDomain());

        foreach ($result as $row) {

            $dom = self::returnedDomain($row);
            if($dom)
            {
                array_push($domains, $dom);
            }

        }
        return $domains;


    }


    public function getById($id)
    {
        $result = $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameDomain() . " WHERE " . StudyPressDB::COL_ID_DOMAIN . " = '%d'", $id));


        $domain =  self::returnedDomain($result);



        return $domain;

    }


   
    public static function returnedDomain($row)
    {
        return (
        empty($row)
            ? null :
            new Domain(array(
                'id'          => (int) $row[StudyPressDB::COL_ID_DOMAIN],
                'name'        =>       $row[StudyPressDB::COL_NAME_DOMAIN],
                'description' =>       $row[StudyPressDB::COL_DESCRIPTION_DOMAIN]
            ))
        );
    }

} 