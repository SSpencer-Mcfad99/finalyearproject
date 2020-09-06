<?php

namespace votingSystemTutorial;
/**
 * Class DetailModel
 * @package votingSystemTutorial
 *
 * Data model that deals with editing details of users
 */
class DetailModel
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

    /** Gets all the details of the user (barring userrole) from the database.
     *
     * @param $username
     * @return mixed
     */
	public function retrieveDetailsFromDB($username){
        $query_string = $this->sql_queries->getUserDetails();
		$query_params = array(':username' => $username);

        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchRow();
        return $result;
	}

    /** Checks to see if the password entered in the confirm password box
     * matches password stored for user in the database.
     *
     * @param $userid
     * @param $username
     * @return string
     */
    public function checkUserPassword($userid, $username){
        $query_string = $this->sql_queries->checkUserPassword();
        $query_params = array(':userid' => $userid, ':username' => $username);

        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if ($result == true) {
            return 'There has been a Query Error';
        } else {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['password'];
        }
    }

    /** Updates the details of the user account.
     *
     * @param $username
     * @param $hashed_password
     * @param $firstname
     * @param $lastname
     * @param $email
     * @param $user_id
     * @return bool
     */
    public function editUser($username, $hashed_password, $firstname, $lastname, $email, $user_id)  : bool
    {
        $query_string = $this->sql_queries->updateUserDetails();

        $query_params = array(':username' => $username, ':password' => $hashed_password, ':email' => $email,
            ':firstname' => $firstname, ':lastname' => $lastname, ':userid' => $user_id);

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

    /**A variation of editUser that doesn't involve changing passwords
     *
     * @param $username
     * @param $firstname
     * @param $lastname
     * @param $email
     * @param $user_id
     * @return bool
     */
    public function editUserNoPass($username, $firstname, $lastname, $email, $user_id)
    {
        $query_string = $this->sql_queries->updateUserDetailsNoPass();
        $query_params = array(':username' => $username, ':email' => $email, ':firstname' => $firstname,
            ':lastname' => $lastname, ':userid' => $user_id);

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

    /**Deletes a user account from the database.
     *
     * @param $id
     * @return bool
     */
	public function deleteUser($id) {
	    $query_string = $this->sql_queries->deleteUserAccount();
        $query_params = array(':userid' => $id);

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

    /** Deletes the lines from the login logs involving the deleted user.
     *
     * @param $id
     * @return bool
     */
    public function deleteUserLoginLogs($id) {
        $query_string = $this->sql_queries->deleteLoginLogsForUser();
        $query_params = array(':userid' => $id);

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

    /** Checks to see if username already exists (excluding if it is used by the account being updated)
     *
     * @param $username
     * @param $id
     * @return bool|string
     */
    public function doesUsersExist($username, $id)
    {
        $query_string = $this->sql_queries->getUpdatedUserID();
        $query_params = array(':username' => $username, ':userid' => $id);

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

    /** Checks to see if the newly updated email is being used by another account
     * (excluding if it is used by the account being updated).
     *
     * @param $email
     * @param $id
     * @return bool|string
     */
    public function doesEmailExist($email, $id)
    {
        $query_string = $this->sql_queries->getUpdatedEmail();
        $query_params = array(':email' => $email, ':userid' => $id);

        $result = $this->databaseConnectWithParams($query_string, $query_params);

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