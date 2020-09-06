<?php

namespace votingSystemTutorial;

/**
 * Class VotingSystemModel
 * @package votingSystemTutorial
 *
 * Data model that deals with creating, deleting, editing and retrieving voting systems and voting system types.
 */

class VotingSystemModel
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

    /**Adds a new voting system type to the system.
     *
     * @param $cleaned_typename
     * @param $cleaned_typedesc
     * @return bool
     */
    public function createNewType($cleaned_typename, $cleaned_typedesc) {
		$query_string = $this->sql_queries->createSystemType();
        $query_params = array(':systemtypename' => $cleaned_typename, ':systemtypedesc' => $cleaned_typedesc);
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

    /**Adds a new system to the database.
     *
     * @param $cleaned_systemname
     * @param $cleaned_systemtype
     * @param $cleaned_systemsummary
     * @param $cleaned_systeminformation
     * @return bool
     */
	public function createNewSystem($cleaned_systemname, $cleaned_systemtype, $cleaned_systemsummary , $cleaned_systeminformation) {
		$query_string = $this->sql_queries->createVotingSystem();
        $query_params = array(':systemname' => $cleaned_systemname, ':systemtypeid' => $cleaned_systemtype, ':systemsummary' => $cleaned_systemsummary,
		':systeminformation' => $cleaned_systeminformation);

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

    /** Retrieves a system with a specified id from the database.
     *
     * @param $id
     * @return mixed
     */
    public function retrieveSystemFromDB($id){
        $query_string = $this->sql_queries->getSystem();
        $query_params = array(':systemid' => $id);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchRow();
        return $result;
    }

    /** Retrieves all the systems from the database.
     *
     * @return mixed
     */
	public function retrieveSystemsFromDB(){
        $query_string = $this->sql_queries->getSystems();
        $this->databaseConnectWithoutParams($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Retrieves all system types from the database.
     *
     * @return mixed
     */
    public function retrieveSystemTypesFromDB(){
        $query_string = $this->sql_queries->getSystemTypes();
        $this->databaseConnectWithoutParams($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Deletes a specified system from the database.
     *
     * @param $systemid
     * @return bool
     */
    public function deleteSystem($systemid) : bool{
        $query_string = $this->sql_queries->deleteSystem();
        $query_params = array(':systemid' => $systemid);

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

    /**Deletes all the systems with a specified typeid to prepare the system to delete a type.
     *
     * @param $typeid
     * @return bool
     */
    public function deleteSystems($typeid) : bool{
        $query_string = $this->sql_queries->deleteSystems();
        $query_params = array(':systemtypeid' => $typeid);

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

    /**Deletes a specified system type from the database.
     *
     * @param $typeid
     * @return bool
     */
    public function deleteSystemType($typeid) : bool{
        $query_string = $this->sql_queries->deleteSystemType();
        $query_params = array(':systemtypeid' => $typeid);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /** Updates a specified system with values from the parameters in the database.
     *
     * @param $system_id
     * @param $cleaned_system_name
     * @param $cleaned_type_id
     * @param $cleaned_system_summary
     * @param $cleaned_system_information
     * @return bool
     */
    public function updateSystem($system_id, $cleaned_system_name, $cleaned_type_id, $cleaned_system_summary, $cleaned_system_information) : bool{
        $query_string = $this->sql_queries->updateSystemInformation();
        $query_params = array(':systemid' => $system_id, ':systemname' => $cleaned_system_name,':systemtypeid' => $cleaned_type_id,
            ':systemsummary' => $cleaned_system_summary,':systeminformation' => $cleaned_system_information);

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

    /** Updates a specified system type with values from the parameters in the database.
     *
     * @param $type_id
     * @param $cleaned_type_name
     * @param $cleaned_type_desc
     * @return bool
     */
    public function updateSystemType($type_id, $cleaned_type_name, $cleaned_type_desc) : bool {
        $query_string = $this->sql_queries->updateSystemType();
        $query_params = array(':systemtypeid' => $type_id, ':systemtypename' => $cleaned_type_name,':systemtypedesc' => $cleaned_type_desc);

        $this->databaseConnectWithParams($query_string, $query_params);
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

    /** Retrieve a specified type from database.
     *
     * @param $typeid
     * @return mixed
     */
    public function getTypeFromDB($typeid){
        $query_string = $this->sql_queries->getSystemType();
        $query_params = array(':systemtypeid' => $typeid);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchRow();
        return $result;
    }

    /** Retrieve a specified system from the database.
     *
     * @param $systemid
     * @return mixed
     */
    public function getSystemFromDB($systemid){
        $query_string = $this->sql_queries->getSystem();
        $query_params = array(':systemid' => $systemid);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchRow();
        return $result;
    }

    /** Retrieve an overview of the systems in a specified type from the database.
     *
     * @param $typeid
     * @return mixed
     */
    public function getSystemOverviewFromDB($typeid)
    {
        $query_string = $this->sql_queries->viewVotingSummary();
        $query_params = array(':systemtypeid' => $typeid);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Retrieve the information about a specified voting system from the database.
     *
     * @param $systemid
     * @return mixed
     */
    public function getSystemInformationFromDB($systemid)
    {
        $query_string = $this->sql_queries->viewVotingInformation();
        $query_params = array(':systemid' => $systemid);
        $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchRow();
        return $result;
    }

    /** Retrieves every category and the most recent post in each category from the database.
     *
     * @return mixed
     */
    public function getCategoriesAndLatestPost() {
        $query_string = $this->sql_queries->getCategoriesAndLatestPost();
        $this->databaseConnectWithoutParams($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Checks to see if a specified type exists within the database.
     *
     * @param $type
     * @return bool|string
     */
    public function doesTypeExist($type)
    {
        $query_string = $this->sql_queries->getSystemTypeId();
        $query_params = array(':systemtypename' => $type);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR (meaning the query has run)
        {
            return 'Unfortunately there has been a query error';
        }
        else // desired behaviour for when a query has RAN SUCCESSFULLY
        {
            $result = $this->database_wrapper->safeFetchArray();

            if ($result != null) //if result is not null, type exists
            {
                return true;
            }
            else // if result is null, type doesn't exist
            {
                return false;
            }
        }
    }

    /** Variation of doesTypeExist but checks every system type except the one being modified
     *
     * @param $type
     * @param $id
     * @return bool|string
     */
    public function doesTypesExist($id, $type)
    {
        $query_string = $this->sql_queries->getSystemTypeIds();
        $query_params = array(':systemtypename' => $type, ':systemtypeid' => $id);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR (meaning the query has run)
        {
            return 'Unfortunately there has been a query error';
        }
        else // desired behaviour for when a query has RAN SUCCESSFULLY
        {
            $result = $this->database_wrapper->safeFetchArray();

            if ($result != null) //if result is not null, type exists
            {
                return true;
            }
            else // if result is null, type doesn't exist
            {
                return false;
            }
        }
    }

    /** Checks to see if a specified system is within the database
     *
     * @param $system
     * @return bool|string
     */
    public function doesSystemExist($system)
    {
        $query_string = $this->sql_queries->getSystemId();
        $query_params = array(':systemname' => $system);
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

    /** Variation of doesSystemExist but checks every system except the one being modified
     *
     * @param $system
     * @param $id
     * @return bool|string
     */
    public function doesSystemsExist($system, $id)
    {
        $query_string = $this->sql_queries->getSystemIds();
        $query_params = array(':systemname' => $system, ':systemid' => $id);
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