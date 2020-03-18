<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:23
 */

namespace VotingSystemsTutorial;
/**
 * Class LoginModel
 * @package VotingSystemsTutorial
 *
 * Data model that deals with logging into and logging out of the application
 */
class LoginModel
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

    public function storeLoginAttempt($userid, $login_result){
        $query_string = $this->sql_queries->storeUserLoginResult();
        $query_params = array(':userid'=> $userid, ':logincompleted' => $login_result);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string,$query_params);
    }

    public function checkUserPassword($userid, $username){
        $query_string = $this->sql_queries->checkUserPassword();
        $query_params = array(':userid' => $userid, ':userusername' => $username);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if ($result == true) {
            return 'There has been a Query Error';
        } else {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['userpassword'];
        }
    }

    public function checkUserID($username){
        $query_string = $this->sql_queries->getUserID();
        $query_params = array(':userusername' => $username);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if ($result == true) {
            return 'There has been a Query Error';
        } else {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['userid'];
        }

    }

    public function checkUserRole($userid) {
        $query_string = $this->sql_queries->getUserRole();
        $query_params = array(':userid' => $userid);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if ($result == true) {
            return 'There has been a Query Error';
        } else {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['userrole'];
        }
    }
}