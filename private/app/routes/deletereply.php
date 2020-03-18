<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 10/03/2020
 * Time: 15:00
 */


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletereply', function(Request $request, Response $response) use ($app){

    session_start();
    $parameters = $request->getParsedBody();

    deleteReply($app, $parameters);

    $url = $this->router->pathFor('dreplies');
    return $response->withStatus(302)->withHeader('Location', $url);
        
    

})->setName('deletereply');

/**
 * Updates the user role for a user in the RoleModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $parameters
 */
function deleteReply($app, $parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $typeid = $parameters['replyid'];
 

    $verification = $model->deleteReply($replyid);

    if($verification == true)
    {
        echo '<div style="text-align: center;">Reply deleted from database.</div>';
    }
    else
    {
        echo 'there was an issue deleting reply';
    }
}