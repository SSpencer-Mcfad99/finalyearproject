<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:23
 */

namespace VotingSystemsTutorial;
/**
 * Class RegistrationModel
 * @package VotingSystemsTutorial
 *
 * Data model that deals with registering new users of the application
 */
class RegistrationModel
{
    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

    public function __construct(){}

    public function __destruct(){}

    public function setDatabaseWrapper($database_wrapper)
    {
        $this->database_wrapper = $database_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }
    public function createNewUser($cleaned_username, $hashed_password, $cleaned_firstname, $cleaned_lastname, $cleaned_email)  : bool
    {
        $query_string = $this->sql_queries->createNewUser();

        $query_params = array(':userusername' => $cleaned_username, ':userpassword' => $hashed_password,
            ':useremail' => $cleaned_email, ':userfirstname' => $cleaned_firstname,
            ':userlastname' => $cleaned_lastname);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        //switches the value of the boolean to make for a more user friendly codebase - if result is false, the query executed successfully, inverting this to true infers this better
        if($result == false)
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    public function doesUsernameExist($username)
    {
        $query_string = $this->sql_queries->getUserId();
        $query_params = array(':userusername' => $username);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR (meaning the query has run)
        {
            return 'Unfortunately there has been a query error';
        }

        else // desired behaviour for when a query has RAN SUCCESSFULLY
        {
            $result = $this->database_wrapper->safeFetchArray();

            if($result != null) //if result is not null, user exists
            {
                return true;
            }

            else // if result is null, user doesn't exist
            {
                return false;
            }
        }
    }

    public function doesEmailExist($email)
    {
        $query_string = $this->sql_queries->getUserEmail();
        $query_params = array(':useremail' => $email);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR (meaning the query has run)
        {
            return 'Unfortunately there has been a query error';
        }

        else // desired behaviour for when a query has RAN SUCCESSFULLY
        {
            $result = $this->database_wrapper->safeFetchArray();

            if($result != null) //if result is not null, email exists
            {
                return true;
            }

            else // if result is null, email doesn't exist
            {
                return false;
            }
        }
    }
}