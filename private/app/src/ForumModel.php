<?php

namespace votingSystemTutorial;

/**
 * Class ForumModel
 * @package votingSystemTutorial
 *
 * Data Model that deals with adding, deleting, editing or retrieving categories, posts and replies from the forums.
 */
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

/** Stores a reply of a given post to the reply table.
 *
 * @param $reply_message
 * @param $reply_id
 * @param $reply_author
 * @return bool
 */
public function addReply($reply_message, $reply_id, $reply_author) : bool {
	$query_string = $this->sql_queries->addReply();
	$query_params = array(':replycontent' => $reply_message, ':replytopic' => $reply_id, ':replyauthor' => $reply_author);

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

/** Adds a post within a category to the post table.
 *
 * @param $subject_name
 * @param $post_subject
 * @param $post_author
 * @return bool
 */
public function addPost($subject_name, $post_subject, $post_author) : bool
{
	$query_string = $this->sql_queries->createNewPost();
	$query_params = array(':postsubject' => $subject_name, ':postcategory' => $post_subject, ':postauthor' => $post_author);

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

/**Adds a new forum category to the category table.
 *
 * @param $category_name
 * @param $category_desc
 * @return bool
 */
public function addCategory($category_name, $category_desc) : bool
{
	$query_string = $this->sql_queries->createNewCategory();
	$query_params = array(':categoryname' => $category_name, ':categorydescription' => $category_desc);

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

/** Deletes a specified reply from the database
 *
 * @param $reply_id
 * @return bool
 */
public function deleteReply($reply_id) : bool
{
        $query_string = $this->sql_queries->deleteReply();
        $query_params = array(':replyid' => $reply_id);

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

/** Deletes all replies within a post to prepare for that post to be deleted.
 *
 * @param $post_id
 * @return bool
 */
public function deleteReplies($post_id) : bool
{
        $query_string = $this->sql_queries->deleteReplies();
        $query_params = array(':replytopic' => $post_id);

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

/** Deletes the specified post from the database.
 *
 * @param $post_id
 * @return bool
 */
public function deletePost($post_id) : bool
{
    $query_string = $this->sql_queries->deletePost();
    $query_params = array(':postid' => $post_id);
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

/** Deletes all posts within a specified category to prepare for that category to be deleted.
 *
 * @param $category_id
 * @return bool
 */
public function deletePosts($category_id) : bool
{


	$query_string = $this->sql_queries->deletePosts();
	$query_params = array(':postcategory' => $category_id);

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

/** Deletes a specified category from the database.
 *
 * @param $category_id
 * @return bool
 */
public function deleteCategory($category_id) : bool
{
	$query_string = $this->sql_queries->deleteCategory();
	$query_params = array(':categoryid' => $category_id);

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

/** Edits the details of a specified category and updates database.
 *
 * @param $category_name
 * @param $category_desc
 * @param $id
 * @return bool
 */
public function editCategory($category_name, $category_desc, $id) : bool
{
    $query_string = $this->sql_queries->editCategory();
    $query_params = array(':categoryname' => $category_name, ':categorydescription' => $category_desc, ':categoryid' => $id);
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

/** Retrieves the post that was most recently created in a specified category.
 *
 * @param $categoryid
 * @return mixed
 */
public function getLatestPost($categoryid)
{
    $query_string = $this->sql_queries->getLatestPost();
    $query_params = array(':postcategory' => $categoryid);

    $this->databaseConnectWithParams($query_string, $query_params);

    $result = $this->database_wrapper->safeFetchRow();
    return $result;
}

public function getLatestPosts()
{
    $query_string = $this->sql_queries->getLatestPosts();
    $this->databaseConnectWithoutParams($query_string);

    $result = $this->database_wrapper->safeFetchAll();
    return $result;
}

/** Retrieve a specified category from the database.
 *
 * @param $categoryid
 * @return mixed
 */
public function retrieveCategoryFromDB($categoryid)
{
    $query_string = $this->sql_queries->getCategory();
    $query_params = array(':categoryid' => $categoryid);

    $this->databaseConnectWithParams($query_string, $query_params);

    $result = $this->database_wrapper->safeFetchRow();
    return $result;
}

/** Retrieve all posts within a specified category.
 *
 * @param $categoryid
 * @return mixed
 */
public function retrievePostsFromDB($categoryid)
{
    $query_string = $this->sql_queries->getThreads();
    $query_params = array(':postcategory' => $categoryid);

    $this->databaseConnectWithParams($query_string, $query_params);

    $result = $this->database_wrapper->safeFetchAll();
    return $result;
}

/**Retrieve all replies within a specified post.
 *
 * @param $topicid
 * @return mixed
 */
public function retrieveRepliesFromDB($topicid)
{

    $query_string = $this->sql_queries->getReplies();
    $query_params = array(':replytopic' => $topicid);

    $this->databaseConnectWithParams($query_string, $query_params);

    $result = $this->database_wrapper->safeFetchAll();
    return $result;
}

/** Retrieve all categories from the database.
 *
 * @return mixed
 */
public function retrieveCategoriesFromDB()
{
    $query_string = $this->sql_queries->getCategories();
    $this->databaseConnectWithoutParams($query_string);

    $result = $this->database_wrapper->safeFetchAll();
    return $result;
}

/** Checks to see if a category with the specified name already exists.
 *
 * @param $category
 * @return bool|string
 */
public function doesCategoryExist($category)
{
    $query_string = $this->sql_queries->getCategoryId();
    $query_params = array(':categoryname' => $category);

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

/** Checks to see if a category with the specified name already exists (excluding if category being updated has it).
 *
 * @param $category
 * @param $id
 * @return bool|string
 */
public function doesNewCategoryExist($category, $id)
{
    $query_string = $this->sql_queries->getNewCategoryId();
    $query_params = array(':categoryname' => $category, ':categoryid' => $id);

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