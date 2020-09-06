<?php

/**
 * deletereply.php - Makes use of the information from deletereplies.php.
 * Passes information to ForumModel to perform any database queries.
 *
 * Returns to deletereplies.php after deleting the reply from the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletereply', function(Request $request, Response $response) use ($app){

    session_start();
    $reply = $_REQUEST['reply'];
    $id = $_GET['id'];
    deleteReply($app, $reply);

    $url = $this->router->pathFor('delreplies');
    return $response->withStatus(302)->withHeader('Location', $url . "?id=$id");

})->setName('deletereply');

/**
 * Deletes the specified reply from a post using the ForumModel, which deals with executing the Database Delete query
 *
 * @param $app
 * @param $reply
 */
function deleteReply($app, $reply)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteReply($reply);

    if($verification != true)
    {
        echo 'there was an issue deleting reply';
    }
}