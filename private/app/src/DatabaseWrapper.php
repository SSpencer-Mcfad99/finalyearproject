<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:22
 */

namespace VotingSystemsTutorial;
class DatabaseWrapper
{
private $database_connection_settings;
private $db_handle;
private $errors;
private $prepared_statement;
private $sql_queries;

public function _construct(){
    $database_connection_settings = null;
    $db_handle = null;
    $errors = null;
    $prepared_statement = null;
    $sql_queries = [];
}

public function _destruct(){}

    /**Takes passed database connection settings and copies them to local variable
     *
     * @param $database_connection_settings     */

public function setDatabaseConnectionSettings($database_connection_settings){
    $this->database_connection_settings = $database_connection_settings;
}

    /** '\' character in front of the PDO class name signifies that it is a globally available class
     * and is not part of the namespace
     * @return string
     */
public function makeDatabaseConnection(){
       $pdo = false;
       $pdo_error = '';

       $database_settings = $this->database_connection_settings;
       $host_name = $database_settings['rdbms'] . ':host=' . $database_settings['host'];
       $port_number = ';port=' . '3306';
       $user_database = ';dbname=' . $database_settings['db_name'];
       $host_details = $host_name . $port_number . $user_database;
       $user_name = $database_settings['user_name'];
       $user_password = $database_settings['user_password'];
       $pdo_attributes = $database_settings['options'];

       try{
           $pdo_handle = new \PDO($host_details, $user_name, $user_password, $pdo_attributes);
           $this->db_handle = $pdo_handle;
       } catch (\PDOException $exception_object){

           trigger_error('error connecting to database');
           $pdo_error = 'error connecting to database';
       }

       return $pdo_error;
}

    /**Takes predefined SQLqueries from the SQLqueries class, it sets a retrieved SQLquery local when needed
     *
     * @param $sql_queries
     */

public function setSQLQueries($sql_queries){
    $this->sql_queries = $sql_queries;
}

    /**
     * @param $query_string
     * @param null $params
     *
     * For transparency, each parameter value is bound separately to its placeholder
     * This is not always strictly necessary.
     *
     * @return mixed
     */
public function safeQuery($query_string, $params = null) {
    $this->errors['db_error'] = false;
    $query_parameters = $params;

    try
    {
        $this->prepared_statement = $this->db_handle->prepare($query_string);
        $execute_result = $this->prepared_statement->execute($query_parameters);
        $this->errors['execute-OK'] = $execute_result;
    }
    catch (PDOException $exception_object)
    {
        $error_message  = 'PDO Exception caught. ';
        $error_message .= 'Error with the database access.' . "\n";
        $error_message .= 'SQL query: ' . $query_string . "\n";
        $error_message .= 'Error: ' . var_dump($this->prepared_statement->errorInfo(), true) . "\n";
        $this->errors['db_error'] = true;
        $this->errors['sql_error'] = $error_message;
    }
    return $this->errors['db_error'];
}

    /**Returns the number of rows within the database
     *
     * @return mixed
     */
public function countRows(){
    $num_rows = $this->prepared_statement->rowCount();
    return $num_rows;
}

    /**Safely fetches a result row as an array indexed by column number
     *
     * @return mixed
     */
public function safeFetchRow(){
    $record_set = $this->prepared_statement->fetch(\PDO::FETCH_NUM);
    return $record_set;
}

    /**Safely fetches a result row as an associated array using a key-value pair
     *
     * @return mixed
     */
public function safeFetchArray(){
    $row = $this->prepared_statement->fetch(\PDO::FETCH_ASSOC);
    $this->prepared_statement->closeCursor();
    return $row;
}

    public function safeFetchAll()
    {
        $result_set = $this->prepared_statement->fetchAll();
        $this->prepared_statement->closeCursor();
        return $result_set;
    }
}