<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 02/03/2020
 * Time: 12:11
 */

namespace VotingSystemsTutorial;


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

    public function createNewType($cleaned_typename, $cleaned_typedesc) {
		$query_string = $this->sql_queries->createSystemType();

        $query_params = array(':systemtypename' => $cleaned_typename, ':systemtypedesc' => $cleaned_typedesc);

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
	
	public function createNewSystem($cleaned_systemname, $cleaned_systemtype, $cleaned_systemsummary , $cleaned_systeminformation) {
		$query_string = $this->sql_queries->createVotingSystem();

        $query_params = array(':systemname' => $cleaned_systemname, ':systemtypeid' => $cleaned_systemtype, ':systemsummary' => $cleaned_systemsummary ,
		':systeminformation' => $cleaned_systeminformation);

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

	public function retrieveSystemsFromDB(){
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getSystems();

        $this->database_wrapper->safeQuery($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    public function retrieveTypesFromDB(){
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getTypes();


        $this->database_wrapper->safeQuery($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    public function deleteSystem($systemid) : bool{
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deleteSystem();
        $query_params = array(':systemid' => $systemid);

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

    public function deleteType($typeid) : bool{
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deleteType();
        $query_params = array(':systemtypeid' => $typeid);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        if($result == false)
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    public function updateSystem($system_id, $cleaned_system_name, $cleaned_type_id, $cleaned_system_summary, $cleaned_system_information) : bool{
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->updateSystem();
        $query_params = array(':systemid' => $system_id, ':systemname' => $cleaned_system_name,':systemtypeid' => $cleaned_type_id,
            ':systemsummary' => $cleaned_system_summary,':systeminformation' => $cleaned_system_information);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        if($result == false)
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    public function updateType($type_id, $cleaned_type_name, $cleaned_type_desc) : bool {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->updateType();
        $query_params = array(':systemtypeid' => $type_id':systemtypename' => $cleaned_type_name,':systemtypedesc' => $cleaned_type_desc);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        if($result == false)
        {
            return true;
        }

        else
        {
            return false;
        }
    }
    public function getTypeFromDB($typeid){
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getSystemType();
        $query_params = array(':typeid' => $typeid);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }
    public function getSystemFromDB($systemid){
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getSystem();
        $query_params = array(':systemid' => $systemid);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    public function getSystemOverviewFromDB($systemid)
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->viewVotingSummary();
        $query_params = array(':systemid' => $systemid);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    public function getSystemInformationFromDB($systemid)
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->viewVotingInformation();
        $query_params = array(':systemid' => $systemid);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchRow();
        return $result;
    }
}