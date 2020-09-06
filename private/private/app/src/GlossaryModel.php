<?php


namespace votingSystemTutorial;

/**
 * Class GlossaryModel
 * @package votingSystemTutorial
 *
 * Data model that deals with adding, deleting, editing or retrieving glossary entries.
 */

class GlossaryModel
{
    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

    public function _construct(){}

    public function _destruct(){}

    public function setDatabaseWrapper($database_wrapper)
    {
        $this->database_wrapper = $database_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSQLQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    /**Adds a glossary entry to the glossary.
     *
     * @param $word
     * @param $definition
     * @return bool
     */
    public function addDefinition($word, $definition) : bool
    {
        $query_string = $this->sql_queries->addDefinition();
        $query_params = array(':word' => $word, ':worddefinition' => $definition);
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

    /** Deletes a specified glossary entry from the glossary
     *
     * @param $definition_id
     * @return bool
     */
    public function deleteDefinition($definition_id) :bool
    {
        $query_string = $this->sql_queries->deleteDefinition();
        $query_params = array(':wordid' => $definition_id);
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

    /** Edits the details of a specified glossary entry.
     *
     * @param $definition_id
     * @param $word
     * @param $definition
     * @return bool
     */
    public function editDefinition($definition_id, $word, $definition) : bool
    {
        $query_string = $this->sql_queries->editDefinition();
        $query_params = array(':wordid' => $definition_id, ':word' => $word, ':worddefinition' => $definition);
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

    /** Retrieves a specified glossary entry from the database.
     *
     * @param $id
     * @return mixed
     */
    public function getDefinitionFromDB($id){
        $query_string = $this->sql_queries->getDefinitionFromDB();
        $query_params = array(':wordid' => $id);
        $result = $this->databaseConnectWithParams($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchRow();
        return $result;
    }

    /** Retrieves all glossary entries from the database.
     *
     * @return mixed
     */
    public function  retrieveDefinitionsFromDB(){
        $query_string = $this->sql_queries->retrieveDefinitionsFromDB();
        $result = $this->databaseConnectWithoutParams($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    public function getDefinitions()
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getDefinitions();

        $this->database_wrapper->safeQuery($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /** Checks to see if a word already exists in the glossary.
     *
     * @param $word
     * @return bool|string
     */
    public function DoesWordExist($word)
    {
        $query_string = $this->sql_queries->getDefinitionId();
        $query_params = array(':word' => $word);

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

    /**Checks to see if a word already exists in the glossary (excluding the current entry being updated).
     *
     * @param $definition_id
     * @param $word
     * @return bool|string
     */
    public function DoesWordsExist($definition_id, $word)
    {
        $query_string = $this->sql_queries->getDefinitionIds();
        $query_params = array(':wordid' => $definition_id, ':word' => $word);
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