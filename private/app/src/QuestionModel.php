<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 02/03/2020
 * Time: 12:11
 */

namespace VotingSystemsTutorial;


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

    public function getQuestionsFromDB($quizid)
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getQuestions();
        $query_params = array(':quizid' => $quizid);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }
}