<?php

namespace votingSystemTutorial;
/**
 * Class LoginModel
 * @package votingSystemTutorial
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

    /** Stores the login attempt of the user into the user login logs table.
     *
     * @param $userid
     * @param $login_result
     */
    public function storeLoginAttempt($userid, $login_result){
        $query_string = $this->sql_queries->storeUserLogin();
        $query_params = array(':userid'=> $userid, ':loginstatus' => $login_result);

        $this->databaseConnectWithParams($query_string, $query_params);
    }

    /** Checks to see if the entered password matches the password registered for the user in the database.
     *
     * @param $userid
     * @param $username
     * @return string
     */
    public function checkUserPassword($userid, $username){
        $query_string = $this->sql_queries->checkUserPassword();
        $query_params = array(':userid' => $userid, ':username' => $username);

        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if ($result == true)
        {
            return 'There has been a Query Error';
        }
        else
        {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['password'];
        }
    }

    /** Checks to see if user exists in the database.
     *
     * @param $username
     * @return string
     */
    public function checkUserID($username){
        $query_string = $this->sql_queries->getUserID();
        $query_params = array(':username' => $username);

        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if ($result == true)
        {
            return 'There has been a Query Error';
        }
        else
        {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['userid'];
        }

    }

    /** Checks to see if the user is an admin or a regular user.
     *
     * @param $userid
     * @return string
     */
    public function checkUserRole($userid) {
        $query_string = $this->sql_queries->getUserRole();
        $query_params = array(':userid' => $userid);

        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if ($result == true)
        {
            return 'There has been a Query Error';
        }
        else
        {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['role'];
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
}