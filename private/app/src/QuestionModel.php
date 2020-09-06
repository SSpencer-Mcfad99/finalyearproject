<?php

namespace votingSystemTutorial;

/**
 * Class QuestionModel
 * @package votingSystemTutorial
 *
 * Data model that deals with adding, deleting or editing questions. It also deals with question retrieval.
 */
class QuestionModel
{
    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

    public function _construct(){}

    public function _destruct(){}

    public function setDatabaseWrapper($database_wrapper){
        $this->database_wrapper = $database_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings){
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSQLQueries($sql_queries){
        $this->sql_queries = $sql_queries;
    }

    /** Creates a question using cleaned values and a specified quiz in the database.
     *
     * @param $quizid
     * @param $question
     * @param $choice_1
     * @param $choice_2
     * @param $choice_3
     * @param $choice_4
     * @param $answer
     * @return bool
     */
    public function createQuestion($quizid, $question, $choice_1, $choice_2, $choice_3, $choice_4, $answer) : bool{
        $query_string = $this->sql_queries->createQuizQuestion();
        $query_params = array(':quizid' => $quizid, ':question' => $question, ':choice1' => $choice_1, ':choice2' => $choice_2, 
            ':choice3' => $choice_3, ':choice4' => $choice_4, ':ans' => $answer);

        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if($result == false)
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    /** Deletes a specified question from a quiz and the database.
     *
     * @param $question_id
     * @return bool
     */
    public function deleteQuestion($question_id) : bool{
        $query_string = $this->sql_queries->deleteQuestion();
        $query_params = array(':questionid' => $question_id);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if($result == false)
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    /** Edits the details of a specified question within a quiz.
     *
     * @param $question_id
     * @param $quizid
     * @param $question
     * @param $choice_1
     * @param $choice_2
     * @param $choice_3
     * @param $choice_4
     * @param $answer
     * @return bool
     */
    public function editQuestion($question_id, $quizid, $question, $choice_1, $choice_2, $choice_3, $choice_4, $answer) : bool{
        $query_string = $this->sql_queries->updateQuestion();
        $query_params = array(':questionid' => $question_id, ':quizid' => $quizid, ':question' => $question,
            ':choice1' => $choice_1, ':choice2' => $choice_2, ':choice3' => $choice_3,
            ':choice4' => $choice_4, ':ans' => $answer);

        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if($result == false)
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    /** Checks to see if question already exists within the database.
     *
     * @param $question
     * @return bool|string
     */
    public function doesQuestionExist($question){
        $query_string = $this->sql_queries->getQuestionId();
        $query_params = array(':question' => $question);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR (meaning the query has run)
        {
            return 'Unfortunately there has been a query error';
        }
        else // desired behaviour for when a query has RAN SUCCESSFULLY
        {
            $result = $this->database_wrapper->safeFetchArray();

            if ($result != null) //if result is not null, question exists
            {
                return true;
            } else // if result is null, question doesn't exist
            {
                return false;
            }
        }
    }

    /** Checks to see if question already exists in database (doesn't check against itself). Returns an id if so.
     *
     * @param $id
     * @param $question
     * @return bool|string
     */
    public function doesQuestionsExist( $id, $question){
        $query_string = $this->sql_queries->getQuestionIds();
        $query_params = array(':questionid' => $id,':question' => $question);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR (meaning the query has run)
        {
            return 'Unfortunately there has been a query error';
        }

        else // desired behaviour for when a query has RAN SUCCESSFULLY
        {
            $result = $this->database_wrapper->safeFetchArray();

            if ($result != null) //if result is not null, question exists
            {
                return true;
            } else // if result is null, question doesn't exist
            {
                return false;
            }
        }
    }

    /**Gets specific question from database.
     *
     * @param $question_id
     * @return mixed
     */
    public function getQuestion($question_id){
        $query_string = $this->sql_queries->getQuestion();
        $query_params = array(':questionid' => $question_id);

        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchRow();
        return $result;
    }

    /** Gets all questions from database.
     *
     * @return mixed
     */
    public function getQuestions()
    {
        $query_string = $this->sql_queries->retrieveQuestions();
        $this->databaseConnectWithoutParams($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }
    /** Retrieves all questions within a specified quiz.
     *
     * @param $quizid
     * @return mixed
     */
    public function retrieveQuestionsFromDB($quizid)
    {
        $query_string = $this->sql_queries->getQuestions();
        $query_params = array(':quizid' => $quizid);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Retrieves the questions and answers from quiz, ready to be checked.
     *
     * @param $quiz_id
     * @return mixed
     */
    public function retrieveQuestionsAndAnswersFromDB($quiz_id){
        $query_string = $this->sql_queries->getQuestionsAndAnswers();
        $query_params = array(':quizid' => $quiz_id);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Function that sets up the database connection, its settings as well as performing the query and returns the result
     * of the query. Used by most functions in the class.
     *
     * @param $query_string
     * @param $query_params
     * @return mixed
     */
    public function databaseConnectWithParams($query_string, $query_params) {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        return $result;
    }

    /** Function that works similar to databaseConnectWithParams but instead is used for queries that do not need any parameters.
     *
     * @param $query_string
     * @return mixed
     */
    public function databaseConnectWithoutParams($query_string) {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string);

        return $result;
    }
}