<?php

namespace votingSystemTutorial;
/**
 * Class RoleModel
 * @package votingSystemTutorial
 *
 * Data model that deals with editing roles of users
 */
class RoleModel
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

    /** Retrieves all users excluding the current user from the database.
     *
     * @param $username
     * @return mixed
     */
    public function retrieveUsersFromDB($username)
    {
        $query_string = $this->sql_queries->getMostUsers();
        $query_params = array(':username' => $username);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Updates the role of a specified user.
     *
     * @param $id
     * @param $role
     * @return bool
     */
    public function updateUserRole($id, $role): bool
    {
        $query_string = $this->sql_queries->updateUserRole();
        $query_params = array(':role' => $role, ':userid' => $id);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if ($result == false)
        {
            return true;
        }
        else
        {
            return false;
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
	   