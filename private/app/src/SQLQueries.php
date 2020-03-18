<?php
/**
* Created by PhpStorm.
* User: p17206266
* Date: 06/01/2020
* Time: 13:24
*/

namespace VotingSystemsTutorial;
/**
* SQLQueries.php
*
* hosts all SQL queries to be used by the data models
*/
class SQLQueries
{

public function _construct(){}

public function _destruct(){}

/**
* This query inserts login attempts into the UserLoginLogs table, taking the UserID and LoginCompleted values to store every attempt,
* and whether the attempt succeeded or failed.
*
* @return string <- returns the query string necessary for the application to take and use
*/
public function storeUserLoginResult()
{
$query_string  = "INSERT INTO userloginlogs ";
$query_string .= "SET ";
$query_string .= "userid = :userid, ";
$query_string .= "logincompleted = :logincompleted";
return $query_string;
}

/**
* This query adds new users to the database from the registration form.
*
* @return string <- returns the query string necessary for the application to take and use
*/
public function createNewUser()
{
$query_string = "INSERT INTO users ";
$query_string .= "SET ";
$query_string .= "userusername = :userusername, ";
$query_string .= "userpassword = :userpassword, ";
$query_string .= "useremail = :useremail, ";
$query_string .= "userfirstname = :userfirstname, ";
$query_string .= "userlastname = :userlastname";

return $query_string;
}

public function updateUserDetails(){
    $query_string = "UPDATE users ";
    $query_string .= "SET ";
    $query_string .= "userusername = :userusername, ";
    $query_string .= "userpassword = :userpassword, ";
    $query_string .= "useremail = :useremail, ";
    $query_string .= "userfirstname = :userfirstname, ";
    $query_string .= "userlastname = :userlastname";
    $query_string .= "WHERE userid = :userid";

    return $query_string;
}

public function updateUserRole(){
	 $query_string = "UPDATE users ";
     $query_string .= "SET ";
	 $query_string .= "userrole = :userrole";
     $query_string .= "WHERE userid = :userid";
	 
}

public function deleteUserAccount(){
    $query_string = "DELETE FROM users ";
    $query_string .= "WHERE userid = :userid ";

    return $query_string;
}

public function getMostUsers(){
	$query_string = "SELECT userid, userusername ";
$query_string .= "FROM users ";
$query_string .= "WHERE userid != :userid ";

return $query_string;
}

    public function getAllUsers(){
        $query_string = "SELECT userid, userusername ";
        $query_string .= "FROM users ";

        return $query_string;
    }
public function checkUserPassword()
{
$query_string = "SELECT userid, userusername, userpassword ";
$query_string .= "FROM users ";
$query_string .= "WHERE ";
$query_string .= "userid = :userid AND ";
$query_string .= "userusername = :userusername";

return $query_string;
}

public function getUserDetails(){
	$query_string = "SELECT userusername, useremail, userfirstname, userlastname ";
    $query_string .= "FROM users ";
    $query_string .= "WHERE ";
    $query_string .= "userid = :userid";
}

/**
* This query checks the database to see whether a user already exists. It is used by the login (authenticate) route to check that the entered username exists,
* and by the registration (registeruser) route to prevent multiple users from having the same username.
*
* @return string
*/
public function getUserID()
{
$query_string = "SELECT userid FROM users ";
$query_string .= "WHERE userusername = :userusername";

return $query_string;
}

/**
* This query checks the database to see whether a user already exists. It is used by the edituserdetails (detailedit) route to prevent multiple users from having the same username.
*
* @return string
*/
public function getUpdatedUserID()
{
$query_string = "SELECT userid FROM users ";
$query_string .= "WHERE userusername = :userusername AND userid != :userid";

return $query_string;
}

/**
* This query checks the database to see whether a user entered email address already exists. It is used by the login (authenticate) route to check that the entered email exists,
* and by the registration (registeruser) route to prevent multiple users from having the same email.
*
* @return string
*/
public function getUserEmail(){
$query_string = "SELECT userid FROM users ";
$query_string .= "WHERE useremail = :useremail";

return $query_string;
}

/**
* This query checks the database to see whether a user entered email address already exists (other than the current user having it). It is used by the edituserdetails (detailedit) route to prevent multiple users from having the same email.
*
* @return string
*/
public function getUpdatedUserEmail(){
$query_string = "SELECT userid FROM users ";
$query_string .= "WHERE useremail = :useremail AND userid != :userid";

return $query_string;
}

public function getUserRole() {
$query_string = "SELECT userrole FROM users ";
$query_string .= "WHERE userid = :userid";

return $query_string;
}

public function getCategories(){
$query_string = "SELECT categoryid, categoryname, categorydescription";
$query_string .= "FROM categories";

return $query_string;
}

public function getThreads(){
$query_string = "SELECT postid, postsubject, postdate, postcategory";
$query_string .= "FROM posts";
$query_string .= "WHERE postcategory = :postcategory";

return $query_string;

}

public function getLatestPost() {
$query_string = 'SELECT postid FROM posts ORDER BY postid DESC LIMIT 1';

return $query_string;
}

public function getReplies() {
	$query_string = "SELECT replies.postid, replies.postsubject, replies.postdate, replies.postcategory, "; 
	$query_string .= "replies.postauthor, user.userid user.userusername";
    $query_string .= "FROM replies";
    $query_string .= "WHERE replytopic = :replytopic INNER JOIN users ON replies.postauthor = users.userid";

return $query_string;
}

public function createNewCategory() {
$query_string = "INSERT INTO categories ";
$query_string .= "SET ";
$query_string .= "categoryname = :categoryname,";
$query_string .= "categorydescription = :categorydescription";

return $query_string;
}


public function createNewPost() {
$query_string = "INSERT INTO posts ";
$query_string .= "SET ";
$query_string .= "postsubject = :postsubject,";
$query_string .= "postcategory = :postcategory,";
$query_string .= "postauthor = :postauthor";

return $query_string;
}

public function addReply() {
$query_string = "INSERT INTO replies ";
$query_string .= "SET ";
$query_string .= "replycontent = :replycontent, ";
$query_string .= "replytopic = :replytopic, ";
$query_string .= "replyauthor = :replyauthor";

return $query_string;
}

public function deleteReply(){
        $query_string = "DELETE FROM replies ";
        $query_string .= "WHERE replyid = :replyid";

        return $query_string;
}

public function deleteReplies(){
        $query_string = "DELETE FROM replies ";
        $query_string .= "WHERE replytopic = :replytopic";

        return $query_string;
}

public function deletePost(){
        $query_string = "DELETE FROM posts ";
        $query_string .= "WHERE postid= :postid";

        return $query_string;
}

public function deletePosts(){
        $query_string = "DELETE FROM posts ";
        $query_string .= "WHERE postcategory= :postcategory";

        return $query_string;
}

public function deleteCategory(){
       $query_string = "DELETE FROM categories ";
        $query_string .= "WHERE categoryid= :categoryid";

        return $query_string;
}

public function createNewQuiz () {
$query_string = "INSERT INTO quizzes ";
$query_string .= "SET ";
$query_string .= "quizname = :quizname, ";
$query_string .= "quizdescription = :quizdescription";

return $query_string;
}
public function getQuizzes() {
$query_string = "SELECT quizid, quizname, quizdescription";
$query_string .= "FROM quizzes";

return $query_string;
}

public function createQuizQuestion(){
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

public function updateQuiz(){
    $query_string = "UPDATE quizzes ";
    $query_string .= "SET ";
    $query_string .= "quizname = :quizname, ";
    $query_string .= "quizdescription = :quizdescription, ";
    $query_string .= "WHERE questionid = :questionid ";

    return $query_string;
}

public function updateQuestion(){
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

    public function deleteQuestion(){
        $query_string = "DELETE FROM questions ";
        $query_string .= "WHERE questionid= :questionid";

        return $query_string;
    }
	
	public function deleteQuestions(){
		$query_string = "DELETE FROM questions ";
        $query_string .= "WHERE quizid = :quizid";

        return $query_string;
	}
	
	public function deleteQuestions(){
		$query_string = "DELETE FROM quizzes ";
        $query_string .= "WHERE quizid = :quizid";

        return $query_string;
	}
	
	public function checkChoiceA(){
	    $query_string = "SELECT answers FROM questions ";
        $query_string .= "WHERE questionid = :questionid AND choice1 = :choice1";
		
		return $query_string;
	}
	
	public function checkChoiceB(){
		$query_string = "SELECT answers FROM questions ";
        $query_string .= "WHERE questionid = :questionid AND choice2 = :choice2";
		
		return $query_string;
	}
	
	public function checkChoiceC(){
		$query_string = "SELECT answers FROM questions ";
        $query_string .= "WHERE questionid = :questionid AND choice3 = :choice3";
		
		return $query_string;
	}
	
	public function checkChoiceD(){
		$query_string = "SELECT answers FROM questions ";
        $query_string .= "WHERE questionid = :questionid AND choice4 = :choice4";
		
		return $query_string;
	}
	
public function viewQuiz(){
$query_string = "SELECT quizid, quizname, quizdescription";
$query_string .= "FROM quizzes";

    return $query_string;
}

public function viewQuestion(){
    $query_string = "SELECT questionid, question, choice1, choice2, choice3, choice4";
    $query_string .= "FROM questions";
    $query_string .= "WHERE questionid = :questionid AND quizid = :quizid";

    return $query_string;
}

public function getQuestions(){
    $query_string = "SELECT questionid, question, choice1, choice2, choice3, choice4";
    $query_string .= "FROM questions";
    $query_string .= "WHERE quizid = :quizid";

    return $query_string;
}

public function checkAnswer(){
$query_string = "SELECT ans";
$query_string .= "FROM questions";
$query_string .= "WHERE questionid= :questionid AND quizid = :quizid";

return $query_string;
}

public function createVotingSystem(){
    $query_string = "INSERT INTO voting_system ";
    $query_string .= "SET ";
    $query_string .= "systemname = :systemname, ";
    $query_string .= "systemtypeid = :systemtypeid, ";
    $query_string .= "systemsummary = :systemsummary, ";
    $query_string .= "systeminformation = :systeminformation";

    return $query_string;

}

public function viewVotingSummary(){
    $query_string = "SELECT systemid, systemname, systemsummary";
    $query_string .= "FROM voting_system";
    $query_string .= "WHERE systemtypeid = :systemtypeid";

return $query_string;
}

public function viewVotingInformation(){
        $query_string = "SELECT systemname, systeminformation";
        $query_string .= "FROM voting_system";
        $query_string .= "WHERE systemid = :systemid";

        return $query_string;
}

public function updateSystemInformation(){
    $query_string = "UPDATE voting_system ";
    $query_string .= "SET ";
    $query_string .= "systemname = :systemname, ";
    $query_string .= "systemtypeid = :systemtypeid, ";
    $query_string .= "systemsummary = :systemsummary, ";
    $query_string .= "systeminformation = :systeminformation";
    $query_string .= "WHERE systemid = :systemid";

    return $query_string;
}

    public function deleteSystem(){
        $query_string = "DELETE FROM voting_system ";
        $query_string .= "WHERE systemid = :systemid ";

        return $query_string;
    }

public function createSystemType(){
    $query_string = "INSERT INTO system_type ";
    $query_string .= "SET ";
    $query_string .= "systemtypename = :systemtypename";

    return $query_string;
}

public function updateSystemType(){
    $query_string = "UPDATE system_type ";
    $query_string .= "SET ";
    $query_string .= "systemtypename = :systemtypename";
	$query_string .= "systemtypedesc = :systemtypedesc";
	$query_string .= "WHERE systemtypeid = :systemtypeid";

    return $query_string;
}

public function deleteSystemType(){
        $query_string = "DELETE FROM system_type ";
        $query_string .= "WHERE systemtypeid = :systemtypeid ";

        return $query_string;
}

public function getSystems(){
        $query_string = "SELECT systemid, systemname";
        $query_string .= "FROM voting_system";

        return $query_string;
}

    public function getSystem(){
        $query_string = "SELECT systemname, systemtypeid, systemsummary, systeminformation";
        $query_string .= "FROM voting_system";
        $query_string .= "WHERE systemid = :systemid";

        return $query_string;
    }

    public function getSystemType(){
        $query_string = "SELECT systemtype, systemtypedesc";
        $query_string .= "FROM system_type";
        $query_string .= "WHERE systemtypeid = :systemtypeid";

        return $query_string;
    }



public function getTypes(){
        $query_string = "SELECT systemtypeid, systemtypename";
        $query_string .= "FROM system_type";

        return $query_string;
}

}

