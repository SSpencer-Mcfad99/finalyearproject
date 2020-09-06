<?php

/**
 * updatetype.php - Makes use of the information from editype.php.
 * Passes information to VotingSystemModel to perform any database queries.
 *
 * Returns to votingsystemtype.php after updating the system type in the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/updatetype', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());
    $typeid = $_REQUEST['id'];
    $type_exists_result = doesTypesExist($app, $typeid, $cleaned_parameters['sanitised_typename']);

    if($type_exists_result != true)
    {
          updateType($app, $typeid, $cleaned_parameters);
          $url = $this->router->pathFor('systemtype');
          return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }
})->setName('updatetype');

/** Checks to see if type already exists in the database using method from VotingSystemModel. Doesn't check against itself.
 *
 * @param $app
 * @param $typeid
 * @param $name
 * @return mixed
 */
function doesTypesExist($app, $typeid, $name)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesTypesExist($typeid, $name);
}
/**
 * Updates the details for a type in the VotingSystemModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $typeid
 * @param $cleaned_parameters
 */
function updateType($app, $typeid, $cleaned_parameters)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->updateSystemType($typeid, $cleaned_parameters['sanitised_typename'],$cleaned_parameters['sanitised_typedesc']);

    if($verification != true)
    {
        echo 'there was an issue updating type';
    }
}