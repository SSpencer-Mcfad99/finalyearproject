<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 02/03/2020
 * Time: 12:11
 */

namespace VotingSystemsTutorial;


class QuizModel
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
	
	public function createQuiz($cleaned_quiz_name, $cleaned_quiz_description) : bool{
		$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->createNewQuiz();
        $query_params = array(':quizname' => $cleaned_quiz_name,':quizdescription' => $cleaned_quiz_description);

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
	
	public function createQuestion($cleaned_quizid, $cleaned_question, $cleaned_choice_1, $cleaned_choice_2, $cleaned_choice_3, $cleaned_choice_4, $cleaned_answer) : bool{
		$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->createQuizQuestion();
       $query_params = array(':quizid' => $cleaned_quizid, ':question' => $cleaned_question,
		':choice1' => $cleaned_choice_1, ':choice2' => $cleaned_choice_2, ':choice3' => $cleaned_choice_3, 
		':choice4' => $cleaned_choice_4, ':ans' => $cleaned_answer);

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
	
	public function editQuiz($quiz_id, $cleaned_quiz_name, $cleaned_quiz_description) : bool{
		$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->updateQuiz();
        $query_params = array(':quizid' => $quiz_id, ':replytopic' => $post_id);

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
	
	public function editQuestion($question_id, $cleaned_quizid, $cleaned_question, $cleaned_choice_1, $cleaned_choice_2, $cleaned_choice_3, $cleaned_choice_4, $cleaned_answer) : bool{
		$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->updateQuestion();
        $query_params = array(':questionid' => $question_id, ':quizid' => $cleaned_quizid, ':question' => $cleaned_question,
		':choice1' => $cleaned_choice_1, ':choice2' => $cleaned_choice_2, ':choice3' => $cleaned_choice_3, 
		':choice4' => $cleaned_choice_4, ':ans' => $cleaned_answer);

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
	
    public function deleteQuiz($quiz_id) : bool{
		$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deleteQuiz();
        $query_params = array(':quizid' => $quiz_id);

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
	
	public function deleteQuestion($question_id) : bool{
		$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deleteQuestion();
        $query_params = array(':questionid' => $question_id);

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
	
	public function deleteQuestions($quiz_id) : bool{
		$this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->deleteQuestions();
        $query_params = array(':quizid' => $quiz_id);

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

    public function retrieveQuizzesFromDB()
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getQuizzes();

        $this->database_wrapper->safeQuery($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }
	
	public function retrieveQuestionsFromDB($quizid)
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getQuestions();
		$query_params = array(':quizid' => $quizid);

        $this->database_wrapper->safeQuery($query_string, $query_params);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }
	
	    public function doesAnswerExist( $question, $choice1, $choice2, $choice3, $choice4, $ans){
		 $query_string = $this->sql_queries->checkChoiceA();
        $query_params = array(':question' => $question, ':choice1' => $choice1, ':ans' => $ans);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true) // desired behaviour for when a query has RAN SUCCESSFULLY
        {
			$result = $this->database_wrapper->safeFetchArray();

            if($result != null) //if result is not null, answer exists
            {
                return true;
            }

            else // if result is null, answer doesn't exist
            {
                return false;
            }
            return 'Unfortunately there has been a query error';
        }

        else // choice A not equal to answer, checks choice B
        {
			$query_string = $this->sql_queries->checkChoiceB();
            $query_params = array(':question' => $question, ':choice2' => $choice2, ':ans' => $ans);

            $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
            $this->database_wrapper->makeDatabaseConnection();

            $result = $this->database_wrapper->safeQuery($query_string, $query_params);
		    if($result == true) // desired behaviour for when a query has RAN SUCCESSFULLY
            {
			  $result = $this->database_wrapper->safeFetchArray();

              if($result != null) //if result is not null, answer exists
              {
                return true;
              }
              else // if result is null, answer doesn't exist
              {
                return false;
              }
            }
            else // choice B not equal to answer, checks choice C
            {
			  $query_string = $this->sql_queries->checkChoiceC();
              $query_params = array(':question' => $question, ':choice3' => $choice3, ':ans' => $ans);

              $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
              $this->database_wrapper->makeDatabaseConnection();

              $result = $this->database_wrapper->safeQuery($query_string, $query_params);
		
		      if($result == true) //desired behaviour for when a query has RAN SUCCESSFULLY
              {
			    $result = $this->database_wrapper->safeFetchArray();

                if($result != null) //if result is not null, answer exists
                {
                  return true;
                }
                else // if result is null, answer doesn't exist
                {
                  return false;
                }
              }
              else // answer is not equal to choice C, checks choice D
              {
			    $query_string = $this->sql_queries->checkChoiceD();
                $query_params = array(':question' => $question, ':choice4' => $choice4, ':ans' => $ans);

                $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
                $this->database_wrapper->makeDatabaseConnection();

                $result = $this->database_wrapper->safeQuery($query_string, $query_params);
		
		        if($result == true) //
                {
                  $result = $this->database_wrapper->safeFetchArray();

                  if($result != null) //if result is not null, answer exists
                  {
                    return true;
                  }
                  else // if result is null, answer doesn't exist
                  {
                   return false;
                  }
                }
                else //  This signifies that there was a QUERY ERROR (meaning the query has run)
                {
			     return 'Unfortunately there has been a query error';
                }   
              }    
            }    
        }
	}
	
	public function doesQuizExist($quizname){
		 $query_string = $this->sql_queries->getQuizName();
        $query_params = array(':quizname' => $quizname);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR (meaning the query has run)
        {
            return 'Unfortunately there has been a query error';
        }

        else // desired behaviour for when a query has RAN SUCCESSFULLY
        {
            $result = $this->database_wrapper->safeFetchArray();

            if($result != null) //if result is not null, quiz exists
            {
                return true;
            }

            else // if result is null, quiz doesn't exist
            {
                return false;
            }
        }
	}
}