<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 08/03/2020
 * Time: 13:20
 */

namespace VotingSystemsTutorial;
/**
 * Class RoleModel
 * @package VotingSystemsTutorial
 *
 * Data model that deals with editing roles of users
 */
class RoleModel
{
    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

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

    public function retrieveUsersFromDB($id)
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getMostUsers();
        $query_params = array(':userid' => $id);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    public function updateUserRole($id, $role): bool
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->updateUserRole();
        $query_params = array(':userid' => $id, ':userrole' => $role);

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if ($result == false) {
            return true;
        } else {
            return false;
        }
    }
}
	   