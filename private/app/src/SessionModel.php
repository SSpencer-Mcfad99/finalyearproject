<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:23
 */

namespace VotingSystemsTutorial;

/**
 * SessionModel.php
 *
 * stores the validated values in the relevant storage location
 */
class SessionModel
{
    private $username;
    private $server_type;
    private $password;
    private $storage_result;
    private $session_wrapper_file;
    private $session_wrapper_database;
    private $database_connection_settings;
    private $sql_queries;

    public function _construct() {
        $username = null;
        $server_type = null;
        $password = null;
        $storage_result = null;
        $session_wrapper_file = null;
        $session_wrapper_database = null;
        $database_connection_settings = null;
        $sql_queries = null;
    }

    public function _destruct(){}

    public function setSessionUsername($username){
        $this->username = $username;
    }

    public function setSessionPassword($password){
        $this->password = $password;
    }

    public function setDatabaseConnectionSettings($database_connection_settings){
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSQLQueries($sql_queries){
        $this->sql_queries = $sql_queries;
    }

    public function getStorageResult(){
        return $this->storage_result;
    }

    public function storeDataInDatabase(){
        $store_result = false;

        $this->session_wrapper_database->setSqlQueries( $this->sql_queries);
        $this->session_wrapper_database->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->session_wrapper_database->makeDatabaseConnection();

        $store_result_username = $this->session_wrapper_database->setSessionVar('user_name', $this->username);
        $store_result_password = $this->session_wrapper_database->setSessionVar('user_password', $this->password);

        if ($store_result_username !== false && $store_result_password !== false)
        {
            $store_result = true;
        }
        return $store_result;
    }



}