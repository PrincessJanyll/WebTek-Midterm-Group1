<?php



class Quiz extends AbstractActivity
{

    private $_questions = array();


    public function __construct(array $d){
        parent::__construct($d);
    }


    
    public function getQuestions()
    {
        return $this->_questions;
    }

   
    public function setQuestions($questions)
    {
        $this->_questions = $questions;
    }


    public function addQuestion(Question $q)
    {
        $this->_questions[] = $q;
    }

    public function countQuestions()
    {
        return count($this->_questions);
    }

    public function getQuestionByPosition($position)
    {
        return  $this->_questions[$position];
    }

    public function removeQuestion($id){
        $newQuestions = array();
        foreach($this->_questions as $q){
            if($id != $q->getId()) $newQuestions[] = $q;
        }

        $this->_slides = $newQuestions;
    }

    public function getQuestionById($id){
        foreach ($this->_questions as $q) {
            if((int) $id === $q->getId()) return $q;
        }
        return null;


    }




}

