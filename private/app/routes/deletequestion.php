<?php

/**
 * deletequestion.php - Makes use of the information from deletequestions.php.
 * Passes information to QuestionModel to perform any database queries.
 *
 * Returns to deletequestions.php after deleting the question from the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletequestion', function(Request $request, Response $response) use ($app){

    session_start();
    $question_id = $_REQUEST['question'];
    deleteQuestion($app, $question_id);

    $url = $this->router->pathFor('delquestion');
    return $response->withStatus(302)->withHeader('Location', $url);

})->setName('deletequestion');

/**
 * Deletes a question in the database by calling the relevant method in the QuestionModel, which deals with executing the Database Delete query
 *
 * @param $app
 * @param $question_id
 */
function deleteQuestion($app, $question_id)
{
    $model = $app->getContainer()->get('questionModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteQuestion($question_id);

    if($verification != true)
    {
        echo 'there was an issue deleting question!';
    }
}
