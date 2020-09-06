<?php

/**
 * editquestion.php - Makes use of the information from editquestions.php.
 * Passes information to QuizModel to perform any database queries.
 *
 * Returns to editquestions.php after editing the questions in the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/editquestion', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());

    $question_id = $_SESSION['questionid'];
    $question_exists_result = doesQuestionsExist($app, $question_id, $cleaned_parameters['sanitised_question'], $cleaned_parameters['sanitised_choicea'], $cleaned_parameters['sanitised_choiceb'], $cleaned_parameters['sanitised_choicec'], $cleaned_parameters['sanitised_choiced'], $cleaned_parameters['sanitised_answer']);

    if(($question_exists_result != true) && (($cleaned_parameters['sanitised_choicea'] === $cleaned_parameters['sanitised_answer']) || ($cleaned_parameters['sanitised_choiceb'] === $cleaned_parameters['sanitised_answer']) ||
            ($cleaned_parameters['sanitised_choicec'] === $cleaned_parameters['sanitised_answer']) || ($cleaned_parameters['sanitised_choiced'] === $cleaned_parameters['sanitised_answer'])))
    {
            $quiz_id = $_REQUEST['quizid'];
            editQuestion($app, $cleaned_parameters, $quiz_id, $question_id);
			unset($_SESSION['questionid']);

            $url = $this->router->pathFor('edquestion');
            return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('editquestion');

/**
 * Edits a question's details in the database by calling the relevant method in the QuestionModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $quiz_id
 * @param $question_id
 */
function editQuestion($app, $cleaned_parameters, $quiz_id, $question_id)
{
    $model = $app->getContainer()->get('questionModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->editQuestion($question_id, $quiz_id, $cleaned_parameters['sanitised_question'], $cleaned_parameters['sanitised_choicea'],
        $cleaned_parameters['sanitised_choiceb'], $cleaned_parameters['sanitised_choicec'], $cleaned_parameters['sanitised_choiced'],
        $cleaned_parameters['sanitised_answer']);

    if($verification != true)
    {
        echo 'there was an issue editing the question';
    }
}

/** Checks to see if question exists in the database using relevant method in QuestionModel.
 *
 * @param $app
 * @param $id
 * @param $question
 * @param $choice1
 * @param $choice2
 * @param $choice3
 * @param $choice4
 * @param $ans
 * @return mixed
 */
function doesQuestionsExist($app, $id, $question, $choice1, $choice2, $choice3, $choice4, $ans)
{
    $model = $app->getContainer()->get('questionModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesQuestionsExist($id, $question, $choice1, $choice2, $choice3, $choice4, $ans);
}