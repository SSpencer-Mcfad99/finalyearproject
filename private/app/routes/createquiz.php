<?php

/**
 * createquiz.php - Makes use of the information from createquizzes.php.
 * Passes information to QuizModel to perform any database queries.
 *
 * Returns to createquizzes.php after adding the quiz to the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createquiz', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());
    $quiz_exists_result = doesQuizExist($app, $cleaned_parameters['sanitised_quizname']);

    if($quiz_exists_result != true)
    {
        createQuiz($app, $cleaned_parameters);
        $url = $this->router->pathFor('cquiz');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
       echo 'Sorry, there was an issue with your entered values';
       return;
    }

})->setName('createquiz');

/**
 * Adds a quiz to the database by calling the relevant method in the QuizModel, which deals with executing the Database Insert query
 *
 * @param $app
 * @param $cleaned_parameters
 */
function createQuiz($app, $cleaned_parameters)
{
    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->createQuiz($cleaned_parameters['sanitised_quizname'], $cleaned_parameters['sanitised_quizdesc']);

    if($verification != true)
    {
        echo 'there was an issue creating the new quiz';
    }
}

/** Checks to see if quiz already exists in database by calling relevant method in the QuizModel.
 *
 * @param $app
 * @param $name
 * @return mixed
 */
function doesQuizExist($app, $name)
{
    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesQuizExist($name);
}
