<?php

namespace votingSystemTutorial;

/**
 * Class QuizModel
 * @package votingSystemTutorial
 *
 * A data model dealing with adding, deleting, editing and retrieval of quizzes.
 */

class QuizModel
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

    /** Adds a quiz to the database.
     *
     * @param $cleaned_quiz_name
     * @param $cleaned_quiz_description
     * @return bool
     */
	public function createQuiz($cleaned_quiz_name, $cleaned_quiz_description) : bool{
        $query_string = $this->sql_queries->createNewQuiz();
        $query_params = array(':quizname' => $cleaned_quiz_name,':quizdescription' => $cleaned_quiz_description);

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

    /** Edits the name and description of a specified quiz.
     *
     * @param $quiz_id
     * @param $cleaned_quiz_name
     * @param $cleaned_quiz_description
     * @return bool
     */
	public function editQuiz($quiz_id, $cleaned_quiz_name, $cleaned_quiz_description) : bool{
        $query_string = $this->sql_queries->updateQuiz();
        $query_params = array(':quizid' => $quiz_id, ':quizname' => $cleaned_quiz_name, ':quizdescription' => $cleaned_quiz_description);
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

    /** Deletes specified quiz from the database.
     *
     * @param $quiz_id
     * @return bool
     */
    public function deleteQuiz($quiz_id) : bool{
        $query_string = $this->sql_queries->deleteQuiz();
        $query_params = array(':quizid' => $quiz_id);

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

    /** Deletes all questions within a specified quiz, preparing for quiz being deleted.
     *
     * @param $quiz_id
     * @return bool
     */
	public function deleteQuestions($quiz_id) : bool{
        $query_string = $this->sql_queries->deleteQuestions();
        $query_params = array(':quizid' => $quiz_id);

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

    /** Retrieves the details of a specified quiz from the database.
     * @param $quiz_id
     * @return mixed
     */
	public function getQuiz($quiz_id)
    {
        $query_string = $this->sql_queries->getQuiz();
        $query_params = array(':quizid' => $quiz_id);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchRow();
        return $result;
    }

    /** Retrieves all quizzes from the database.
     *
     * @return mixed
     */
    public function retrieveQuizzesFromDB()
    {
        $query_string = $this->sql_queries->getQuizzes();
        $this->databaseConnectWithoutParams($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Gets all questions from a specified quiz.
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

    /** Gets all questions from database.
     *
     * @return mixed
     */
    public function retrieveQuestions()
    {
        $query_string = $this->sql_queries->retrieveQuestions();
        $this->databaseConnectWithoutParams($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Checks to see if name of quiz already exists.
     *
     * @param $quizname
     * @return bool|string
     */
	public function doesQuizExist($quizname){
	    $query_string = $this->sql_queries->getQuizName();
        $query_params = array(':quizname' => $quizname);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR (meaning the query has run)
        {
            return 'Unfortunately there has been a query error';
        }
        else // desired behaviour for when a query has RAN SUCCESSFULLY
        {
            $result = $this->database_wrapper->safeFetchArray();

            if($result != null) //if result is not null, quiz exists
            {
                return true;
            }
            else // if result is null, quiz doesn't exist
            {
                return false;
            }
        }
	}

    /** Checks to see if quiz with the same name already exists (excludes name of quiz being updated).
     *
     * @param $id
     * @param $quizname
     * @return bool|string
     */
    public function doesNewQuizExist($id, $quizname){
        $query_string = $this->sql_queries->getNewQuizName();
        $query_params = array(':quizid' => $id, ':quizname' => $quizname);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR (meaning the query has run)
        {
            return 'Unfortunately there has been a query error';
        }
        else // desired behaviour for when a query has RAN SUCCESSFULLY
        {
            $result = $this->database_wrapper->safeFetchArray();

            if($result != null) //if result is not null, quiz exists
            {
                return true;
            }
            else // if result is null, quiz doesn't exist
            {
                return false;
            }
        }
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