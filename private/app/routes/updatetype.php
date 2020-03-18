<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 08/03/2020
 * Time: 13:43
 */


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/updatetype', function(Request $request, Response $response) use ($app){

    session_start();
    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters);

    updateType($app, $cleaned_parameters);

    $url = $this->router->pathFor('editsyst');
    return $response->withStatus(302)->withHeader('Location', $url);
        
    

})->setName('updatetype');

/**
 * Updates the user role for a user in the RoleModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $parameters
 */
function updateType($app, $typeid, $cleaned_parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

	$typename = $cleaned_parameters['systemtypename'];
	$typedesc = $cleaned_parameters['systemtypedesc'];
 

    $verification = $model->updateType($typeid, $typename, $typedesc);

    if($verification == true)
    {
        echo '<div style="text-align: center;">User role updated.</div>';
    }
    else
    {
        echo 'there was an issue updating role';
    }
}