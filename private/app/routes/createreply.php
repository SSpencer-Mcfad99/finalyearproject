<?php

/**
 * createreply.php - Makes use of the information from viewposts.php.
 * Passes information to ForumModel to perform any database queries.
 *
 * Returns to viewposts.php after adding the reply to the post.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createreply', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());
    $topic_id = $_SESSION['postid'];
    unset($_SESSION['postid']);

    addReply($app, $cleaned_parameters, $topic_id);
    $url = $this->router->pathFor("vpost");
    return $response->withStatus(302)->withHeader('Location', $url . "?id=$topic_id");
})->setName('createreply');

/**
 * Adds a reply to the post in the database by calling the relevant method in the ForumModel, which deals with executing the Database Insert query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $topic_id
 */
function addReply($app, $cleaned_parameters, $topic_id)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->addReply($cleaned_parameters['sanitised_reply_content'], $topic_id, $_SESSION['userid']);

    if($verification != true)
    {
        echo 'there was an issue creating the new reply';
    }
}
