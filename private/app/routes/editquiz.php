<?php

/**
 * editquiz.php - Makes use of the information from editquizzes.php.
 * Passes information to QuizModel to perform any database queries.
 *
 * Returns to editquizzes.php after editing the quiz.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/editquiz', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());
	$id = $_SESSION['quizid'];
	unset($_SESSION['quizid']);

	$quiz_exists_result = doesQuizzesExist($app, $id, $cleaned_parameters['sanitised_quizname']);

    if($quiz_exists_result != true)
    {
            editQuiz($app, $cleaned_parameters, $id);
            $url = $this->router->pathFor('equiz');
            return $response->withStatus(302)->withHeader('Location', $url);
	}
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('editquiz');

/**
 * Edits a quiz's details in the database by calling the relevant method in the QuizModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $quiz_id
 */
function editQuiz($app, $cleaned_parameters, $quiz_id)
{
    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->editQuiz($quiz_id, $cleaned_parameters['sanitised_quizname'], $cleaned_parameters['sanitised_quizdesc']);

    if($verification != true)
    {
        echo 'there was an issue editing the quiz';
    }
}

/** Checks to see if quiz already exists in the database using relevant query from QuizModel (doesn't check against its own values).
 *
 * @param $app
 * @param $id
 * @param $name
 * @return mixed
 */
function doesQuizzesExist($app, $id, $name)
{
    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesNewQuizExist($id, $name);
}
