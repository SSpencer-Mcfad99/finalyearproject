<?php

namespace votingSystemTutorial;
/**
* SQLQueries.php
*
* hosts all SQL queries to be used by the data models
*/
class SQLQueries
{

public function _construct(){}

public function _destruct(){}

/*****************************************************/
/* User Queries (including admin tools)              */
/*****************************************************/

/**
* This query inserts login attempts into the LoginLogs table, taking the UserID and LoginStatus values to store every attempt,
* and whether the attempt succeeded or failed.
*
* @return string <- returns the query string necessary for the application to take and use
*/
public function storeUserLogin()
{
    $query_string  = "INSERT INTO loginlogs ";
    $query_string .= "SET ";
    $query_string .= "userid = :userid, ";
    $query_string .= "loginstatus = :loginstatus";

    return $query_string;
}

/**
* This query adds new users to the database using values from the registration form.
*
* @return string <- returns the query string necessary for the application to take and use
*/
public function createNewUser()
{
    $query_string = "INSERT INTO users ";
    $query_string .= "SET ";
    $query_string .= "username = :username, ";
    $query_string .= "password = :password, ";
    $query_string .= "email = :email, ";
    $query_string .= "firstname = :firstname, ";
    $query_string .= "lastname = :lastname";

    return $query_string;
}

/**
 * This query updates user details within the database based on values from updateuser form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function updateUserDetails()
{
    $query_string = "UPDATE users ";
    $query_string .= "SET ";
    $query_string .= "username = :username, ";
    $query_string .= "password = :password, ";
    $query_string .= "email = :email, ";
    $query_string .= "firstname = :firstname, ";
    $query_string .= "lastname = :lastname ";
    $query_string .= "WHERE userid = :userid";

    return $query_string;
}

/**
 * This query is the same as updateUserDetails except it doesn't update a user's password..
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function updateUserDetailsNoPass()
{
    $query_string = "UPDATE users ";
    $query_string .= "SET ";
    $query_string .= "username = :username, ";
    $query_string .= "email = :email, ";
    $query_string .= "firstname = :firstname, ";
    $query_string .= "lastname = :lastname ";
    $query_string .= "WHERE userid = :userid";

    return $query_string;
}

/**
 * This query updates the user's role within the database based on value from updaterole form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function updateUserRole()
{
    $query_string = "UPDATE users ";
    $query_string .= "SET ";
    $query_string .= "role = :role ";
    $query_string .= "WHERE userid = :userid";

    return $query_string;
}

/**
 * This query deletes a user from the database with a specified userid, this being obtained from deleteuser form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteUserAccount()
{
    $query_string = "DELETE FROM users ";
    $query_string .= "WHERE userid = :userid";

    return $query_string;
}

/**
 * This query deletes the loginlogs of a user being deleted based on a specified userid obtained from deleteuser form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteLoginLogsForUser()
{
    $query_string = "DELETE FROM loginlogs ";
    $query_string .= "WHERE userid = :userid ";

    return $query_string;
}

/** This query fetches all users except for the user currently logged in from the database
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getMostUsers()
{
	$query_string = "SELECT userid, username ";
    $query_string .= "FROM users ";
    $query_string .= "WHERE username != :username ";

    return $query_string;
}

/** This query fetches all users from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getAllUsers()
{
    $query_string = "SELECT userid, username ";
    $query_string .= "FROM users ";

    return $query_string;
}

/** This query compares the entered password in either loginform or detailupdate form
 * to the user's password in the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function checkUserPassword()
{
    $query_string = "SELECT userid, username, password ";
    $query_string .= "FROM users ";
    $query_string .= "WHERE ";
    $query_string .= "userid = :userid AND ";
    $query_string .= "username = :username";

    return $query_string;
}

/** The query fetches all the details of a specified user (excluding role) based on logged in user.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getUserDetails()
{
	$query_string = "SELECT userid, username, email, firstname, lastname ";
    $query_string .= "FROM users ";
    $query_string .= "WHERE ";
    $query_string .= "username = :username";

    return $query_string;
}

/**
 * This query checks the database to see whether a user exists. It is used by the login (authenticate) route to check that the entered username exists.
 *
 * @return string
 */
public function getUserID()
{
    $query_string = "SELECT userid FROM users ";
    $query_string .= "WHERE username = :username";

    return $query_string;
}

/**
 * This query checks the database to see whether a user already exists. It is used by the registration (registeruser) route to prevent multiple users
 * from having the same email and username.
 *
 * @return string
 */
public function getUserIDandEmail()
{
    $query_string = "SELECT userid FROM users ";
    $query_string .= "WHERE username = :username OR email = :email";

    return $query_string;
}

/**
* This query checks the database to see whether a user already exists. It is used by the edituserdetails (detailedit) route to prevent multiple users
 * from having the same username.
*
* @return string
*/
public function getUpdatedUserID()
{
    $query_string = "SELECT userid FROM users ";
    $query_string .= "WHERE userid != :userid AND username = :username";

    return $query_string;
}

/**
 * This query checks the database to see whether a user already exists. It is used by the edituserdetails (detailedit) route to prevent multiple users
 * from having the same email.
 *
 * @return string
 */
public function getUpdatedEmail()
{
    $query_string = "SELECT userid FROM users ";
    $query_string .= "WHERE userid != :userid AND email = :email";

    return $query_string;
}

/** The query fetches the user role of the logged in user after being authenticated. This affects what navbar and content is shown on pages.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getUserRole()
{
    $query_string = "SELECT role FROM users ";
    $query_string .= "WHERE userid = :userid";

    return $query_string;
}

/*****************************************************/
/* Forum Queries (including admin tools)             */
/*****************************************************/
/** The query checks to see if a category doesn't exist in the database, returns a value if it does.
 * Used by createCategory.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getCategoryId()
{
    $query_string = "SELECT categoryid FROM categories ";
    $query_string .= "WHERE categoryname = :categoryname";

    return $query_string;
}

/**The query checks to see if a category doesn't exist in the database, returns a value if it does.
 * This excludes the name of the category being editted and is used by editCategory.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getNewCategoryId()
{
    $query_string = "SELECT categoryid FROM categories ";
    $query_string .= "WHERE categoryname = :categoryname AND categoryid != :categoryid";

    return $query_string;
}

/** This query fetches a category from the database based upon specified category id. Used in editCategories.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getCategory()
{
    $query_string = "SELECT categoryname, categorydescription FROM categories ";
    $query_string .= "WHERE categoryid = :categoryid";

    return $query_string;
}

/** This query fetches all the categories from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getCategories()
{
    $query_string = "SELECT categoryid, categoryname, categorydescription ";
    $query_string .= "FROM categories";

    return $query_string;
}

/** This query fetches all the categories within the database and the latest post in each one.
 * This is used on the forum homepage.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getLatestPosts()
{
    $query_string = "SELECT categoryid, postid, postsubject, postdate FROM categories, posts ";
    $query_string .= "WHERE postid IN ( ";
    $query_string .= "SELECT MAX(postid) ";
    $query_string .= "FROM posts GROUP BY postcategory ";
    $query_string .= ") AND categoryid = postcategory";

    return $query_string;
}

/** This query retrieves all the posts within a specified categoryid from the database. Used in viewCategory
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getThreads()
{
    $query_string = "SELECT postid, postsubject, postdate, postcategory FROM posts ";
    $query_string .= "WHERE postcategory = :postcategory";

    return $query_string;

}

/** This query retrieves the most recent post that was added in a specified category.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getLatestPost()
{
    $query_string = "SELECT postid FROM posts " ;
    $query_string .= "WHERE postcategory = :postcategory " ;
    $query_string .= "ORDER BY postdate DESC LIMIT 1";

    return $query_string;
}

/** This query retrieves all the replies within a specified post.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getReplies()
{
	$query_string = "SELECT replyid, replycontent, replydate, replyauthor FROM replies ";
    $query_string .= "WHERE replytopic = :replytopic";

    return $query_string;
}

/** This query creates a new category in the database based on values from the createCategory form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function createNewCategory()
{
    $query_string = "INSERT INTO categories ";
    $query_string .= "SET ";
    $query_string .= "categoryname = :categoryname, ";
    $query_string .= "categorydescription = :categorydescription";

    return $query_string;
}

/** This query edits the category in the database using values from editCategory form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function editCategory()
{
    $query_string = "UPDATE categories ";
    $query_string .= "SET ";
    $query_string .= "categoryname = :categoryname, ";
    $query_string .= "categorydescription = :categorydescription ";
    $query_string .= "WHERE categoryid = :categoryid";

    return $query_string;
}

/** This query creates a new Post in the database using values from viewCategory (gets category id from this page)
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function createNewPost()
{
    $query_string = "INSERT INTO posts ";
    $query_string .= "SET ";
    $query_string .= "postsubject = :postsubject, ";
    $query_string .= "postcategory = :postcategory, ";
    $query_string .= "postauthor = :postauthor";

    return $query_string;
}

/** This query adds a reply to a post in the database using values from viewPost.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function addReply()
{
    $query_string = "INSERT INTO replies ";
    $query_string .= "SET ";
    $query_string .= "replycontent = :replycontent, ";
    $query_string .= "replytopic = :replytopic, ";
    $query_string .= "replyauthor = :replyauthor";

    return $query_string;
}

/** This query deletes a specified reply from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteReply()
{
    $query_string = "DELETE FROM replies ";
    $query_string .= "WHERE replyid = :replyid";

    return $query_string;
}

/** This query deletes all replies from a specified post.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteReplies()
{
    $query_string = "DELETE FROM replies ";
    $query_string .= "WHERE replytopic = :replytopic";

    return $query_string;
}

/** This query deletes a post from a specified category from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deletePost()
{
    $query_string = "DELETE FROM posts ";
    $query_string .= "WHERE postid= :postid";

    return $query_string;
}

/** This query deletes all posts from a category within the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deletePosts()
{
    $query_string = "DELETE FROM posts ";
    $query_string .= "WHERE postcategory= :postcategory";

    return $query_string;
}

/** This query deletes a category from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteCategory()
{
    $query_string = "DELETE FROM categories ";
    $query_string .= "WHERE categoryid= :categoryid";

    return $query_string;
}

/*****************************************************/
/* Quiz Queries (including admin tools)              */
/*****************************************************/

/** This query creates a new quiz based on values from the createQuiz form
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function createNewQuiz ()
{
    $query_string = "INSERT INTO quizzes ";
    $query_string .= "SET ";
    $query_string .= "quizname = :quizname, ";
    $query_string .= "quizdescription = :quizdescription";

    return $query_string;
}

/** This query retrieves a specified quiz from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getQuiz()
{
    $query_string = "SELECT quizid, quizname, quizdescription FROM quizzes ";
    $query_string .= "WHERE quizid = :quizid";

    return $query_string;
}

/** This query retrieves all quizzes from the database. Used in quizzes.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getQuizzes()
{
    $query_string = "SELECT quizid, quizname, quizdescription ";
    $query_string .= "FROM quizzes";

    return $query_string;
}

/** This query checks to see if a quiz of the same name already exists. Returns a quizid if it does. Used in createQuiz.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getQuizName()
{
    $query_string = "SELECT quizid FROM quizzes ";
    $query_string .= "WHERE quizname = :quizname";

    return $query_string;
}

/** This query is a version of getQuizName that works to check every other quiz except the one being edited in editQuiz,
 * returns a quizid if it already exists.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getNewQuizName()
{
    $query_string = "SELECT quizid FROM quizzes ";
    $query_string .= "WHERE quizname = :quizname AND quizid != :quizid";

    return $query_string;
}

/** This query adds a new question to database using values from createQuestion form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function createQuizQuestion()
{
    $query_string = "INSERT INTO questions ";
    $query_string .= "SET ";
    $query_string .= "quizid = :quizid, ";
    $query_string .= "question = :question, ";
    $query_string .= "choice1 = :choice1, ";
    $query_string .= "choice2 = :choice2, ";
    $query_string .= "choice3 = :choice3, ";
    $query_string .= "choice4 = :choice4, ";
    $query_string .= "ans = :ans ";

    return $query_string;
}

/** This query updates the values of a specified row in the quiz table.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function updateQuiz()
{
    $query_string = "UPDATE quizzes ";
    $query_string .= "SET ";
    $query_string .= "quizname = :quizname, ";
    $query_string .= "quizdescription = :quizdescription ";
    $query_string .= "WHERE quizid = :quizid";

    return $query_string;
}

/** This query updates the values in a specified row in the question table.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function updateQuestion()
{
    $query_string = "UPDATE questions ";
    $query_string .= "SET ";
    $query_string .= "quizid = :quizid, ";
    $query_string .= "question = :question, ";
    $query_string .= "choice1 = :choice1, ";
    $query_string .= "choice2 = :choice2, ";
    $query_string .= "choice3 = :choice3, ";
    $query_string .= "choice4 = :choice4, ";
    $query_string .= "ans = :ans ";
    $query_string .= "WHERE questionid = :questionid ";

    return $query_string;
}

/** This query deletes a specified question from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
 public function deleteQuestion()
 {
     $query_string = "DELETE FROM questions ";
     $query_string .= "WHERE questionid= :questionid";

     return $query_string;
}

/** This query deletes all questions in a quiz to prepare for quiz deletion.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteQuestions()
{
    $query_string = "DELETE FROM questions ";
    $query_string .= "WHERE quizid = :quizid";

    return $query_string;
}

/** This query deletes a specified quiz from the quiz table.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteQuiz()
{
    $query_string = "DELETE FROM quizzes ";
    $query_string .= "WHERE quizid = :quizid";

    return $query_string;
}

/** This query checks to see if a question already exists in the database. Returns an id if so.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getQuestionId()
{
    $query_string = "SELECT questionid FROM questions ";
    $query_string .= "WHERE question = :question";

    return $query_string;
}

/** This query checks to see if a question already exists in the database (excludes checking edited question). Returns an id if so.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getQuestionIds()
{
    $query_string = "SELECT questionid FROM questions ";
    $query_string .= "WHERE question = :question AND questionid != :questionid";

    return $query_string;
}

/** This query gets all rows in quiz table.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function viewQuiz()
{
    $query_string = "SELECT quizid, quizname, quizdescription ";
    $query_string .= "FROM quizzes";

    return $query_string;
}

/** This query retrieves a specified question from database.
 *
 * @return string
 */
public function getQuestion()
{
    $query_string = "SELECT questionid, question, choice1, choice2, choice3, choice4, ans ";
    $query_string .= "FROM questions ";
    $query_string .= "WHERE questionid = :questionid";

    return $query_string;
}

/** This query gets all questions within a specified quiz. Used in quiz.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getQuestions()
{
    $query_string = "SELECT questionid, question, choice1, choice2, choice3, choice4, ans ";
    $query_string .= "FROM questions ";
    $query_string .= "WHERE quizid = :quizid";

    return $query_string;
}

public function retrieveQuestions(){
    $query_string = "SELECT questionid, question, choice1, choice2, choice3, choice4, ans ";
    $query_string .= "FROM questions ";

    return $query_string;
}

/** This query gets all the questionids and answers to prepare program for checking answers. Used in checkanswer.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getQuestionsAndAnswers()
{
        $query_string = "SELECT questionid, ans ";
        $query_string .= "FROM questions ";
        $query_string .= "WHERE quizid = :quizid";

        return $query_string;
}

/** This query is used to get an answer from a specified question/quiz. It's used in checkanswer file.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function checkAnswer()
{
    $query_string = "SELECT ans FROM questions ";
    $query_string .= "WHERE questionid = :questionid AND quizid = :quizid";

    return $query_string;
}

/*****************************************************/
/* Voting System Queries (including admin tools)     */
/*****************************************************/

/** This query is used to create a new voting system in database using values from createSystem form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function createVotingSystem()
{
    $query_string = "INSERT INTO voting_system ";
    $query_string .= "SET ";
    $query_string .= "systemname = :systemname, ";
    $query_string .= "systemtypeid = :systemtypeid, ";
    $query_string .= "systemsummary = :systemsummary, ";
    $query_string .= "systeminformation = :systeminformation";

    return $query_string;

}

/** This query fetches the name, id and summary of voting systems within a specified type. Used in viewSystems.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function viewVotingSummary()
{
    $query_string = "SELECT systemid, systemname, systemsummary ";
    $query_string .= "FROM voting_system ";
    $query_string .= "WHERE systemtypeid = :systemtypeid";

    return $query_string;
}

/** This query fetches the name and information of a specified voting system from the database. Used in viewSystem.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function viewVotingInformation()
{
    $query_string = "SELECT systemname, systeminformation FROM voting_system";
    $query_string .= "WHERE systemid = :systemid";

    return $query_string;
}

/** This query updates the specified voting system in the database using values from the editSystem form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function updateSystemInformation()
{
    $query_string = "UPDATE voting_system ";
    $query_string .= "SET ";
    $query_string .= "systemname = :systemname, ";
    $query_string .= "systemtypeid = :systemtypeid, ";
    $query_string .= "systemsummary = :systemsummary, ";
    $query_string .= "systeminformation = :systeminformation ";
    $query_string .= "WHERE systemid = :systemid";

    return $query_string;
}

/** This query deletes a specified voting system from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteSystem()
{
    $query_string = "DELETE FROM voting_system ";
    $query_string .= "WHERE systemid = :systemid ";

    return $query_string;
}

/** This query deletes all voting systems with a specified system type from the database to prepare for deleting a system type.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteSystems()
{
    $query_string = "DELETE FROM voting_system ";
    $query_string .= "WHERE systemtypeid = :systemtypeid ";

    return $query_string;
}

/** This query creates a new system type within the database using values from the createType form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function createSystemType()
{
    $query_string = "INSERT INTO system_type ";
    $query_string .= "SET ";
    $query_string .= "systemtypename = :systemtypename, ";
    $query_string .= "systemtypedesc = :systemtypedesc";

    return $query_string;
}

/** This query updates the values of a specified system type using values from editType form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function updateSystemType()
{
    $query_string = "UPDATE system_type ";
    $query_string .= "SET ";
    $query_string .= "systemtypename = :systemtypename, ";
	$query_string .= "systemtypedesc = :systemtypedesc ";
	$query_string .= "WHERE systemtypeid = :systemtypeid";

    return $query_string;
}

/** This query deletes a specified system type from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteSystemType()
{
    $query_string = "DELETE FROM system_type ";
    $query_string .= "WHERE systemtypeid = :systemtypeid ";

    return $query_string;
}

/** This query gets all the voting systems from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getSystems()
{
    $query_string = "SELECT systemid, systemname ";
    $query_string .= "FROM voting_system";

    return $query_string;
}

/** This query checks to see if a voting system already exists in the database. Returns an id if so.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getSystemId()
{
    $query_string = "SELECT systemid FROM voting_system ";
    $query_string .= "WHERE systemname = :systemname";

    return $query_string;

}

/** This query checks to see if a voting system already exists in the database (excludes checking system being edited). Returns an id if so.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getSystemIds()
{
    $query_string = "SELECT systemid FROM voting_system ";
    $query_string .= "WHERE systemname = :systemname AND systemid != :systemid";

    return $query_string;

}

/** This query returns a specified voting system. Used in editSystem form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getSystem()
{
    $query_string = "SELECT systemname, systemtypeid, systemsummary, systeminformation FROM voting_system ";
    $query_string .= "WHERE systemid = :systemid";

    return $query_string;
}

/** This query checks to see if a system type already exists in the database. Returns an id if so.
 *
 * @return string <- returns the query string necessary for the application to take and usw
 */
public function getSystemTypeId()
{
    $query_string = "SELECT systemtypeid FROM system_type ";
    $query_string .= "WHERE systemtypename = :systemtypename";

    return $query_string;
}

/** This query checks to see if a system type already exists in the database (excludes checking type being edited). Returns an id if so.
 *
 * @return string <- returns the query string necessary for the application to take and usw
 */
public function getSystemTypeIds()
{
    $query_string = "SELECT systemtypeid FROM system_type ";
    $query_string .= "WHERE systemtypename = :systemtypename AND systemtypeid != :systemtypeid";

    return $query_string;
}

/** This query returns a specified type to edit. Used in editType.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getSystemType()
{
    $query_string = "SELECT systemtypename, systemtypedesc FROM system_type ";
    $query_string .= "WHERE systemtypeid = :systemtypeid";

    return $query_string;
}

/** This query returns all system types from database. Used in votingSystemType.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getSystemTypes()
{
    $query_string = "SELECT systemtypeid, systemtypename, systemtypedesc FROM system_type";

    return $query_string;
}

/*****************************************************/
/* Glossary Queries (including admin tools)          */
/*****************************************************/

/** This query adds a definition to the database using values from the addDefinition form.
  *
  * @return string <- returns the query string necessary for the application to take and use
  */
public function addDefinition()
{
    $query_string = "INSERT INTO glossary ";
    $query_string .= "SET ";
    $query_string .= "word = :word, ";
    $query_string .= "worddefinition = :worddefinition";

    return $query_string;
}

/** This query deletes a specified definition from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function deleteDefinition()
{
    $query_string = "DELETE FROM glossary ";
    $query_string .= "WHERE wordid = :wordid ";

    return $query_string;
}

/** This query updates a specified definition using values from the editDefinition form.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function editDefinition()
{
    $query_string = "UPDATE glossary ";
    $query_string .= "SET ";
    $query_string .= "word = :word, ";
    $query_string .= "worddefinition = :worddefinition ";
    $query_string .= "WHERE wordid = :wordid";

    return $query_string;
}

/** This query gets a specified definition from the database.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getDefinitionFromDB()
{
    $query_string = "SELECT word, worddefinition FROM glossary ";
    $query_string .= "WHERE wordid = :wordid";

    return $query_string;
}

/** This query gets all the definitions from the database. Used in viewglossary.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getDefinitions()
{
    $query_string = "SELECT word, worddefinition FROM glossary";

    return $query_string;
}

/** This query retrieves all definitions from the database. Used in selectdefinition
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function retrieveDefinitionsFromDB()
{
    $query_string = "SELECT wordid, word FROM glossary";

    return $query_string;
}

/** This query checks to see if a word is already in the glossary. Returns wordid if so.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getDefinitionId()
{
    $query_string = "SELECT wordid FROM glossary ";
    $query_string .= "WHERE word = :word";

    return $query_string;
}

/** This query does the same as getDefinitionId but excludes checking the definition being edited.
 *
 * @return string <- returns the query string necessary for the application to take and use
 */
public function getDefinitionIds()
{
    $query_string = "SELECT wordid FROM glossary ";
    $query_string .= "WHERE word = :word AND wordid != :wordid";

    return $query_string;
}
}

