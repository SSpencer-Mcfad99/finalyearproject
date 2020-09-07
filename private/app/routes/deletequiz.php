<?php

/**
 * deletequiz.php - Makes use of the information from deletequizzes.php.
 * Passes information to QuizModel to perform any database queries.
 *
 * Returns to deletequizzes.php after deleting the quiz and its questions from the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletequiz', function(Request $request, Response $response) use ($app){

    session_start();
    $quiz_id = $_REQUEST['quiz'];

    deleteQuestionsinQuiz($app, $quiz_id);
    deleteQuiz($app, $quiz_id);

    $url = $this->router->pathFor('dquiz');
    return $response->withStatus(302)->withHeader('Location', $url);


})->setName('deletequiz');

/** Deletes all questions from a quiz using the relevant method in QuizModel.
 *
 * @param $app
 * @param $quiz_id
 */
function deleteQuestionsinQuiz($app, $quiz_id)
{
    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteQuestions($quiz_id);
    if($verification != true)
    {
        echo 'there was an issue deleting questions';
    }
}

/**
 * Deletes a quiz from the database by calling the relevant method in the QuizModel, which deals with executing the Database Delete query
 *
 * @param $app
 * @param $quiz_id
 */
function deleteQuiz($app, $quiz_id)
{
    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteQuiz($quiz_id);
    if($verification != true)
    {
        echo 'there was an issue deleting quiz';
    }
}
