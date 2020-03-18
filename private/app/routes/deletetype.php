<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 08/03/2020
 * Time: 13:43
 */


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletetype', function(Request $request, Response $response) use ($app){

    session_start();
    $parameters = $request->getParsedBody();

    deleteType($app, $parameters);

    $url = $this->router->pathFor('deletetypes');
    return $response->withStatus(302)->withHeader('Location', $url);
        
    

})->setName('deletetype');

/**
 * Updates the user role for a user in the RoleModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $parameters
 */
function deleteType($app, $parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $typeid = $parameters['typeid'];
 

    $verification = $model->deleteType($typeid);

    if($verification == true)
    {
        echo '<div style="text-align: center;">User deleted from database.</div>';
    }
    else
    {
        echo 'there was an issue deleting user';
    }
}