<?php

class PropositionManager {

    private $_access;




    public function __construct()
    {

        $this->_access = new AccessData;

    }

   


    public function add(Proposition $prop)
    {
        $a = array(

            StudyPressDB::COL_CONTENT_PROPOSITION => $prop->getContent(),
            StudyPressDB::COL_ID_QUESTION_PROPOSITION => $prop->getQuestionId(),
            StudyPressDB::COL_TYPE_PROPOSITION => $prop->getType(),
            StudyPressDB::COL_ORDER_PROPOSITION => $this->getLastOrder($prop->getQuestionId()) + 1

        );


        $this->_access->insert(StudyPressDB::getTableNamePropositions(), $a);


        $idProp = $this->_access->getLastInsertId();

        return $idProp;

    }



    public function getLastOrder($questionId){
        return  $this->_access->getVar("SELECT MAX(".StudyPressDB::COL_ORDER_PROPOSITION.") FROM " . StudyPressDB::getTableNamePropositions() . " WHERE " . StudyPressDB::COL_ID_QUESTION_PROPOSITION . " = '" . $questionId . "'" );
    }



    public function update($id, Proposition $prop)
    {
        $this->_access->update(
            StudyPressDB::getTableNamePropositions(), 
            array(

                StudyPressDB::COL_CONTENT_PROPOSITION => $prop->getContent(),
                StudyPressDB::COL_ID_QUESTION_PROPOSITION => $prop->getQuestionId(),
                StudyPressDB::COL_TYPE_PROPOSITION => $prop->getType(),
                StudyPressDB::COL_ORDER_PROPOSITION => $prop->getOrder()  
            ),
            array(StudyPressDB::COL_ID_PROPOSITION => $id)  //Where
        );
    }



    
    public function delete($id)
    {
        $id = (int)$id;

        $this->_access->delete(
            StudyPressDB::getTableNamePropositions(), 
            array(StudyPressDB::COL_ID_PROPOSITION => $id)  
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

        $props = array();
        $result = $this->_access->getResults("SELECT * FROM " .  StudyPressDB::getTableNamePropositions());

        foreach ($result as $row) {

            $prop = self::returnedProposition($row);
            array_push($props, $prop);

        }
        return $props;


    }


    public function getById($id)
    {
        $result = $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNamePropositions() . " WHERE " . StudyPressDB::COL_ID_PROPOSITION . " = '%d'", $id));


        $prop =  self::returnedProposition($result);



        return $prop;

    }


    public function getPropositionsOfQuestion($questionId){
        $props = array();
        $result = $this->_access->getResults($this->_access->prepare("SELECT * FROM " .  StudyPressDB::getTableNamePropositions()." WHERE " . StudyPressDB::COL_ID_QUESTION_PROPOSITION ." = '%d'  ORDER BY " . StudyPressDB::COL_ORDER_PROPOSITION ." ASC",$questionId));

        foreach ($result as $row) {

            $prop = self::returnedProposition($row);

            if($prop)
            {
                array_push($props, $prop);
            }




        }
        return $props;

    }


    
    public static function returnedProposition($row)
    {
        return (
        empty($row)
            ? null :
            new Proposition(array(
                'id'         => (int) $row[StudyPressDB::COL_ID_PROPOSITION],
                'questionId' => (int) $row[StudyPressDB::COL_ID_QUESTION_PROPOSITION],
                'content'    =>       $row[StudyPressDB::COL_CONTENT_PROPOSITION],
                'type'       =>       $row[StudyPressDB::COL_TYPE_PROPOSITION],
                'order'      =>       $row[StudyPressDB::COL_ORDER_PROPOSITION]
            ))
        );
    }

} 