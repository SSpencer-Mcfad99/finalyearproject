<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 10/03/2020
 * Time: 12:36
 */

namespace VotingSystemsTutorial;


class ForumModel
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

public function addReply($cleaned_reply_message, $reply_id, $cleaned_reply_subject, $cleaned_reply_author) : bool {
	$query_string = $this->sql_queries->addReply();

        $query_params = array(':replycontent' => $cleaned_reply_message, ':replytopic' => $reply_id,
        ':replytopic' => $cleaned_reply_topic, ':replyauthor' => $cleaned_reply_author);

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

public function addPost($cleaned_subject_name, $cleaned_post_subject, $cleaned_post_author) : bool {
	$query_string = $this->sql_queries->createNewPost();

        $query_params = array(':postsubject' => $cleaned_subject_name, ':postcategory' => $cleaned_post_subject,
		':postauthor' => $cleaned_post_author);

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

public function addCategory($cleaned_category_name, $cleaned_category_desc) : bool {
	$query_string = $this->sql_queries->createNewCategory();

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

public function deleteReply($reply_id) : bool{
	$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deleteReply();
        $query_params = array(':replyid' => $reply_id);

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

public function deleteReplies($post_id) : bool{
	$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deleteReplies();
        $query_params = array(':replytopic' => $post_id);

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

public function deletePost($post_id) : bool{
	$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deletePost();
        $query_params = array(':postid' => $post_id);

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

public function deletePosts($category_id) : bool{
	$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deletePosts();
        $query_params = array(':postcategory' => $category_id);

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

public function deleteCategory($category_id) : bool{
	$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deleteCategory();
        $query_params = array(':categoryid' => $category_id);

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

public function getLatestPost() {
	    $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getLatestPost();
		
		 $this->database_wrapper->safeQuery($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
}

public function retrievePostsFromDB($categoryid)
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getThreads();
        $query_params = array(':postcategory' => $categoryid);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }
	
public function retrieveRepliesFromDB($topicid)
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getReplies();
        $query_params = array(':replytopic' => $topicid);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }
	
public function retrieveCategoriesFromDB()
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getCategories();
        $this->database_wrapper->safeQuery($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }
}