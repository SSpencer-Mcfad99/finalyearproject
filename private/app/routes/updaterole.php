<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 08/03/2020
 * Time: 13:43
 */


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/updaterole', function(Request $request, Response $response) use ($app){

    session_start();
    $parameters = $request->getParsedBody();

    updateUserRole($app, $parameters);

    $url = $this->router->pathFor('editrole');
    return $response->withStatus(302)->withHeader('Location', $url);
        
    

})->setName('updaterole');

/**
 * Updates the user role for a user in the RoleModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $parameters
 */
function updateUserRole($app, $parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('roleModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $userid = $parameters['userid'];
	$userrole = $parameters['userrole'];
 

    $verification = $model->updateUserRole($userid, $userrole);

    if($verification == true)
    {
        echo '<div style="text-align: center;">User role updated.</div>';
    }
    else
    {
        echo 'there was an issue updating role';
    }
}