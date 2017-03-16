<?php


class QuestionManager {

    private $_access;




    public function __construct()
    {

        $this->_access = new AccessData;

    }



    public function add(Question $question)
    {
        $a = array(

            StudyPressDB::COL_CONTENT_QUESTION => $question->getContent(),
            StudyPressDB::COL_ID_QUIZ_QUESTION => $question->getQuizId(),
            StudyPressDB::COL_TYPE_QUESTION => $question->getType(),
            StudyPressDB::COL_ORDER_QUESTION => $this->getLastOrder($question->getQuizId()) + 1
        );

        $this->_access->insert(StudyPressDB::getTableNameQuestions(), $a);

        
        $idQuestion = $this->_access->getLastInsertId();


        $question->setId($idQuestion);








        return $idQuestion;



    }


    public function getLastOrder($quizId){
        return  $this->_access->getVar("SELECT MAX(".StudyPressDB::COL_ORDER_QUESTION.") FROM " . StudyPressDB::getTableNameQuestions() . " WHERE " . StudyPressDB::COL_ID_QUIZ_QUESTION . " = '" . $quizId . "'" );
    }



    
    public function update($id, Question $question)
    {
        $this->_access->update(
            StudyPressDB::getTableNameQuestions(), 
            array(

                StudyPressDB::COL_CONTENT_QUESTION => $question->getContent(),
                StudyPressDB::COL_ID_QUIZ_QUESTION => $question->getQuizId(),
                StudyPressDB::COL_TYPE_QUESTION => $question->getType(),
                StudyPressDB::COL_ORDER_QUESTION => $question->getOrder()  
            ),
            array(StudyPressDB::COL_ID_QUESTION => $id)  
        );


    }

    public function delete($id)
    {
        $id = (int)$id;

        $question = $this->getById($id);



        $managerProp = new PropositionManager();
        foreach ($question->getPropositions() as $prop) {
            $managerProp->delete($prop->getId());
        }


        $this->_access->delete(
            StudyPressDB::getTableNameQuestions(), 
            array(StudyPressDB::COL_ID_QUESTION => $id)  
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

        $questions = array();
        $result = $this->_access->getResults("SELECT * FROM " .  StudyPressDB::getTableNameQuestions());

        $managerProp = new PropositionManager();

        foreach ($result as $row) {

            $question = self::returnedQuestion($row);


            if ($question) {


                $props = $managerProp->getPropositionsOfQuestion($question->getId());


                $question->setPropositions($props);


                array_push($questions, $question);
            }

        }
        return $questions;


    }


    public function getNumberOfPropositions($id)
    {
        return (int) $this->_access->getVar($this->_access->prepare("SELECT COUNT(*) FROM ". StudyPressDB::getTableNamePropositions() . " WHERE ". StudyPressDB::COL_ID_QUESTION_PROPOSITION . " = '%d'",$id));
    }





    public static function returnedQuestion($row)
    {
        return (
        empty($row)
            ? null :
            new Question(array(
                'id'          => (int) $row[StudyPressDB::COL_ID_QUESTION],
                'quizId'      => (int) $row[StudyPressDB::COL_ID_QUIZ_QUESTION],
                'type'        =>       $row[StudyPressDB::COL_TYPE_QUESTION],
                'content'     =>       $row[StudyPressDB::COL_CONTENT_QUESTION],
                'order'       =>       $row[StudyPressDB::COL_ORDER_QUESTION]
            ))
        );
    }

    
    public function getById($id)
    {
        $result = $this->_access->getRow($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameQuestions() . " WHERE " . StudyPressDB::COL_ID_QUESTION . " = '%d'", $id));


        $question =  self::returnedQuestion($result);

        if($question) {

            $managerProp = new PropositionManager();
            $props = $managerProp->getPropositionsOfQuestion($question->getId());
            $question->setPropositions($props);
        }



        return $question;

    }


    public function getQuestionsOfQuiz($quizId){
        $questions = array();
        $quizId = (int) $quizId;

        $managerProp = new PropositionManager();

        $result = $this->_access->getResults($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameQuestions() . " WHERE " . StudyPressDB::COL_ID_QUIZ_QUESTION . " = '%d' ORDER BY " . StudyPressDB::COL_ORDER_QUESTION ." ASC",$quizId));
        foreach ($result as $row) {

            $question = self::returnedQuestion($row);

            if($question)
            {
                $question->setPropositions($managerProp->getPropositionsOfQuestion($question->getId()));
                array_push($questions, $question);
            }


        }
        return $questions;
    }



    public function updateOrders(array $idQuestionOrder){


        $ids = implode(',', array_keys($idQuestionOrder));
        $sql = "UPDATE ".StudyPressDB::getTableNameQuestions()." SET ".StudyPressDB::COL_ORDER_QUESTION." = CASE ".StudyPressDB::COL_ID_QUESTION ." ";
        foreach ($idQuestionOrder as $id => $ordinal) {
            $sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);
        }
        $sql .= "END WHERE ".StudyPressDB::COL_ID_QUESTION ." IN ($ids)";
        $this->_access->query($sql);
    }




} 