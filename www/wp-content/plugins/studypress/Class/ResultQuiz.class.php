<?php


class ResultQuiz {

    private $_id;
    private $_quizId;
    private $_dateResult;
    private $_userId;
    private $note;
    private $_nbrQuestions;
    private $_nbrCorrectResponse;
    private $_validate;
    private $_dateBegin;
    private $_questions = array();

    


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

   
    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    
    public function getNbrCorrectResponse()
    {
        return $this->_nbrCorrectResponse;
    }

    
    public function setNbrCorrectResponse($nbrCorrectResponse)
    {
        $this->_nbrCorrectResponse = $nbrCorrectResponse;
    }

    
    public function getNbrQuestions()
    {
        return $this->_nbrQuestions;
    }

    
    public function setNbrQuestions($nbrQuestions)
    {
        $this->_nbrQuestions = $nbrQuestions;
    }

   
    public function getQuestions()
    {
        return $this->_questions;
    }

   
    public function setQuestions($questions)
    {
        $this->_questions = $questions;
    }

   
    public function getQuizId()
    {
        return $this->_quizId;
    }

    
    public function setQuizId($quizId)
    {
        $this->_quizId = $quizId;
    }

    
    public function getUserId()
    {
        return $this->_userId;
    }

    
    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    
    public function getNote()
    {
        return $this->note;
    }

    
    public function setNote($note)
    {
        $this->note = $note;
    }

    public function getQuestionByPosition($position){
        return $this->_questions[$position];
    }

    public function getDateBegin()
    {
        return $this->_dateBegin;
    }

    
    public function setDateBegin($dateBegin)
    {
        $this->_dateBegin = $dateBegin;
    }

    public function getDateResult()
    {
        return $this->_dateResult;
    }

    public function setDateResult($dateResult)
    {
        $this->_dateResult = $dateResult;
    }


    public function getValidate()
    {
        return $this->_validate;
    }

    
    public function setValidate($validate)
    {
        $this->_validate = trim($validate);
    }

    public function isValide(){

        return ($this->_validate === "true")?true:false;

    }



}