<?php

/**
 * deletepost.php - Makes use of the information from deleteposts.php.
 * Passes information to ForumModel to perform any database queries.
 *
 * Returns to viewcategory.php after deleting the post and its replies from the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletepost', function(Request $request, Response $response) use ($app){

    session_start();
    $post = $_REQUEST['posts'];
    $id = $_SESSION['id'];
    unset($_SESSION['id']);

    deleteReplies($app, $post);
    deletePost($app, $post);

    $url = $this->router->pathFor("vcat");
    return $response->withStatus(302)->withHeader('Location', $url . "?id=$id");

})->setName('deletepost');

/**
 * Deletes a post from the database using the relevant method in ForumModel, which deals with executing the Database Delete query
 *
 * @param $app
 * @param $post
 */
function deletePost($app, $post)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deletePost($post);

    if($verification != true)
    {
        echo 'there was an issue deleting post';
    }
}

/**Deletes all replies within a specified post using the relevant method in ForumModel.
 *
 * @param $app
 * @param $post
 */
function deleteReplies($app, $post)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteReplies($post);

    if($verification != true)
    {
        echo 'there was an issue deleting replies';
    }
}
