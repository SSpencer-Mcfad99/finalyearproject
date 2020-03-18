<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 10/03/2020
 * Time: 15:03
 */


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletepost', function(Request $request, Response $response) use ($app){

    session_start();
    $parameters = $request->getParsedBody();

    deletePost($app, $parameters);

    $url = $this->router->pathFor('dposts');
    return $response->withStatus(302)->withHeader('Location', $url);
        
    

})->setName('deletepost');

/**
 * Updates the user role for a user in the RoleModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $parameters
 */
function deletePost($app, $parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $postid = $parameters['postid'];
 

    $verification = $model->deleteReplies($postid);

    if($verification == true)
    {
	    $verification2 = $model->deletePost($postid);
        if($verification2 == true)
    {
        echo '<div style="text-align: center;">Post deleted from database.</div>';
    }
    else
    {
        echo 'there was an issue deleting post';
    }
    }
    else
    {
        echo 'there was an issue deleting post';
    }
}