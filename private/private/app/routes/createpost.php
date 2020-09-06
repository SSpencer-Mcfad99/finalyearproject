<?php

/**
 * createpost.php - Makes use of the information from viewcategories.php.
 * Passes information to VotingSystemModel to perform any database queries.
 *
 * Returns to forumhomepage.php after adding the post to the category.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createpost', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());

    $post_author = $_SESSION['userid'];
    $cat_id = $_SESSION['id'];
    unset($_SESSION['id']);
    addPost($app, $cleaned_parameters, $cat_id, $post_author);
    $post_id = getLatestPost($app, $cat_id);
    addReplytoPost($app, $cleaned_parameters, $post_id['0'], $post_author);

    $url = $this->router->pathFor('forum');
    return $response->withStatus(302)->withHeader('Location', $url);
})->setName('createpost');

/**
 * Adds a new post to the database by calling the relevant method in the ForumModel, which deals with executing the Database Insert query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $catid
 * @param $post_author
 */
function addPost($app, $cleaned_parameters, $catid, $post_author)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->addPost($cleaned_parameters['sanitised_subname'], $catid,  $post_author);

    if($verification != true)
    {
        echo 'there was an issue creating the new topic';
    }
}

/** Fetches the id of the newly created Post to add the initial message to the post.
 *
 * @param $app
 * @param $cat_id
 * @return mixed
 */
function getLatestPost($app, $cat_id)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $post = $model->getLatestPost($cat_id);

    return $post;
}

/** Adds the initial message to the post using the relevant method in the ForumModel.
 * @param $app
 * @param $cleaned_parameters
 * @param $postid
 * @param $post_author
 */
function addReplytoPost($app, $cleaned_parameters, $postid, $post_author ){

    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->addReply($cleaned_parameters['sanitised_message'], $postid, $post_author);

    if($verification != true)
    {
        echo 'there was an issue adding a reply to your topic';
    }
}