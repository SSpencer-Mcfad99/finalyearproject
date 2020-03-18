<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 08/03/2020
 * Time: 13:43
 */


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deleteuser', function(Request $request, Response $response) use ($app){

    session_start();
    $parameters = $request->getParsedBody();

    deleteUser($app, $parameters);

    $url = $this->router->pathFor('deleteusers');
    return $response->withStatus(302)->withHeader('Location', $url);
        
    

})->setName('deleteuser');

/**
 * Updates the user role for a user in the RoleModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $parameters
 */
function deleteUser($app, $parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $userid = $parameters['userid'];
 

    $verification = $model->deleteRole($userid);

    if($verification == true)
    {
        echo '<div style="text-align: center;">User deleted from database.</div>';
    }
    else
    {
        echo 'there was an issue deleting user';
    }
}