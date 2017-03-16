<?php

class QuizManager extends AbstractActivityManager {

    private $type = "quiz";


    public function __construct(){
        parent::__construct($this->type);
    }



    public function add(AbstractActivity $quiz)
    {


        $idQuiz = parent::add($quiz);


        $quiz->setShortCode("[studypress_quiz id=" . $idQuiz . "]");

        $this->update($idQuiz,$quiz);



        return $idQuiz;

    }


    public static function returnedQuiz($row)
    {
        return ( ( !empty($row) )? new Quiz(parent::returnedArrayActivity($row)) : null );

    }


   
    public function delete($id)
    {
        $id = (int)$id;
        
        $this->_access->query("START TRANSACTION");



        $manageRateDomain = new RateDomainManager();
        $manageRateQuality = new RateQualityManager();

        $manageRateDomain->deleteByActivityId($id);
        $manageRateQuality->deleteByActivityId($id);



        $manageQuestion = new QuestionManager();
        $questions = $manageQuestion->getQuestionsOfQuiz($id);
        foreach ($questions as $q) {
            $manageQuestion->delete($q->getId());
        }

        $this->deleteResultByQuiz($id);



        $quiz = $this->getById($id);
        if($quiz->getPostId() !== 0){
            $this->unpost($quiz);
        }


        $this->_access->delete(
            StudyPressDB::getTableNameVisite(), 
            array(StudyPressDB::COL_ID_ACTIVITY_VISITE => $id)  
        );


        $this->_access->delete(
            StudyPressDB::getTableNameActivity(), 
            array(StudyPressDB::COL_ID_ACTIVITY => $id)  
        );






        if($this->isError()) {

            $m = $this->getMessageError(); 
            $this->_access->query("ROLLBACK"); 
            $this->_access->setMsgError($m);
        }
        else
            $this->_access->query("COMMIT");
    }


    public function getAllWithoutQuestions()
    {

        $result = parent::getAllWithout();

        $quizs = array();
        
        foreach ($result as $row) {

            $quiz = self::returnedQuiz($row);

            if($quiz)
            {
                array_push($quizs, $quiz);
            }


        }
        return $quizs;


    }

    
    public function getById($id)
    {

        $quiz =  self::returnedQuiz(parent::getById($id));

       
        if($quiz != null) {
            $managerQuestion = new QuestionManager();
            $question = $managerQuestion->getQuestionsOfQuiz($quiz->getId());
            $quiz->setQuestions($question);


        }



        return $quiz;
    }


    public function getQuizByPostId($postId)
    {
        return self::returnedQuiz(parent::getActivityByPostId($postId));
    }




    public function getQuizsOfCourse($courseId)
    {

        $result = parent::getActivityOfCourse($courseId);


        $quizs = array();

        foreach ($result as $row) {

            $quiz = self::returnedQuiz($row);

            if ($quiz) {
               

                array_push($quizs, $quiz);
            }


        }
        return $quizs;

    }



    public function saveResult($quizId,$userId,$note,$response,$nbrQuestions,$nbrCorrects,$dateBegin,$valid){

        $this->_access->delete(
            StudyPressDB::getTableNameResultQuiz(), //From table Quiz
            array(StudyPressDB::COL_ID_QUIZ_RESULT => $quizId,
                  StudyPressDB::COL_ID_USER_RESULT => $userId)
        );

        $a = array(

                StudyPressDB::COL_ID_QUIZ_RESULT => $quizId,
                StudyPressDB::COL_ID_USER_RESULT => $userId,
                StudyPressDB::COL_NOTE_RESULT => $note,
                StudyPressDB::COL_RESPONSE_RESULT => json_encode($response),
                StudyPressDB::COL_NBR_QUESTIONS_RESULT => $nbrQuestions,
                StudyPressDB::COL_NBR_CORRECTS_RESULT => $nbrCorrects,
                StudyPressDB::COL_VALIDATE_RESULT => $valid,
                StudyPressDB::COL_DATE_BEGIN_RESULT => $dateBegin,
                StudyPressDB::COL_DATE_RESULT => StudyPressDB::getCurrentDateTime()
            );

        $this->_access->insert(StudyPressDB::getTableNameResultQuiz(), $a);

       
        $idResult = $this->_access->getLastInsertId();




        return $idResult;

    }




    public function getResultOfQuizByUser($quizId,$userId){

        $row =   $this->_access->getRow("SELECT * FROM " . StudyPressDB::getTableNameResultQuiz() . " WHERE " . StudyPressDB::COL_ID_QUIZ_RESULT . " = '". $quizId ."' AND " . StudyPressDB::COL_ID_USER_RESULT ." = '" . $userId . "'" );

        return self::returnedResultQuiz($row);

    }


    public function getResultByQuiz($quizId){
        $quizs = array();
        $result = $this->_access->getResults($this->_access->prepare(
        "SELECT * FROM " .  StudyPressDB::getTableNameResultQuiz() . " WHERE " . StudyPressDB::COL_ID_QUIZ_RESULT ." = '%d' AND " .StudyPressDB::COL_VALIDATE_RESULT . " = 'true'",$quizId));

        // Pour chaque ligne...
        foreach ($result as $row) {

            $quiz = self::returnedResultQuiz($row,false);

            if($quiz)
            {
                array_push($quizs, $quiz);
            }


        }
        return $quizs;


    }


    public function deleteResult($id){
        $this->_access->delete(
            StudyPressDB::getTableNameResultQuiz(), 
            array(StudyPressDB::COL_ID_RESULT => $id)  
        );
    }


    public function deleteResultByQuiz($quizId){
        $this->_access->delete(
            StudyPressDB::getTableNameResultQuiz(), 
            array(StudyPressDB::COL_ID_QUIZ_RESULT => $quizId)  
        );


    }


    public static function returnedResponseToQuestions($response){

        $questions = array();
        if($response)
        {
            foreach ($response as $r) {
                $question  = new Question(array(
                    'id' => $r['id'],
                    'content' => $r['title']
                ));
                $props = array();
                foreach ($r['props'] as $key => $p) {
                    $prop = new Proposition(array(
                        'id' => $p['id'],
                        'content' => $p['title'],
                        'type' => $p['true'],
                        'typeUser' => $p['trueUser'],
                        'order' => $key +1
                    ));
                    array_push($props,$prop);
                }
                $question->setPropositions($props);

                array_push($questions,$question);

            }
        }


        return $questions;

    }


    public static function returnedResultQuiz($row,$chargeResponse = true){

        return (
        (!empty($row))? new ResultQuiz(array(
            'id'                 => (int) $row[StudyPressDB::COL_ID_RESULT],
            'quizId'             => (int) $row[StudyPressDB::COL_ID_QUIZ_RESULT],
            'userId'             => (int) $row[StudyPressDB::COL_ID_USER_RESULT],
            'note'               => (int) $row[StudyPressDB::COL_NOTE_RESULT],
            'nbrQuestions'       => (int) $row[StudyPressDB::COL_NBR_QUESTIONS_RESULT],
            'nbrCorrectResponse' => (int) $row[StudyPressDB::COL_NBR_CORRECTS_RESULT],
            'dateResult'         =>       $row[StudyPressDB::COL_DATE_RESULT],
            'validate'           =>       $row[StudyPressDB::COL_VALIDATE_RESULT],
            'dateBegin'          =>       $row[StudyPressDB::COL_DATE_BEGIN_RESULT],
            'questions'          =>       ($chargeResponse)?self::returnedResponseToQuestions(json_decode($row[StudyPressDB::COL_RESPONSE_RESULT],true)):array()

        )) : null );

    }


    public function isDone($activityId,$userId){
        $result = $this->_access->getVar($this->_access->prepare("SELECT * FROM " . StudyPressDB::getTableNameResultQuiz() . " WHERE " . StudyPressDB::COL_ID_QUIZ_RESULT . " = '%d' AND " . StudyPressDB::COL_ID_USER_RESULT . " = '$userId' AND " . StudyPressDB::COL_VALIDATE_RESULT . " = 'true' ", $activityId));
        return ( (int) $result !== 0 ) ? true : false;
    }




} 