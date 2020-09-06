<?php

namespace votingSystemTutorial;
/**
 * Class RegistrationModel
 * @package votingSystemTutorial
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

    /** Adds a new user account to the system.
     *
     * @param $username
     * @param $hashed_password
     * @param $firstname
     * @param $lastname
     * @param $email
     * @return bool
     */
    public function createNewUser($username, $hashed_password, $firstname, $lastname, $email)  : bool
    {
        $query_string = $this->sql_queries->createNewUser();

        $query_params = array(':username' => $username, ':password' => $hashed_password, ':email' => $email,
            ':firstname' => $firstname, ':lastname' => $lastname);

        $result = $this->databaseConnectWithParams($query_string, $query_params);

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


    /** Checks to see if there's already a user account with the credentials entered in the registration input boxes.
     *
     * @param $username
     * @param $email
     * @return bool|string
     */
    public function doesUserExist($username, $email)
    {
        $query_string = $this->sql_queries->getUserIDandEmail();
        $query_params = array(':username' => $username, ':email' => $email);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

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