<?php

/**
 * updatesystem.php - Makes use of the information from editsystem.php.
 * Passes information to VotingSystemModel to perform any database queries.
 *
 * Returns to homepage.php updating the system details in the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/updatesystem', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());

    $typeid = $_REQUEST['systype'];
    $systemid= $_SESSION['id'];
    unset($_SESSION['id']);
    $system_exists_result = doesSystemsExist($app, $systemid ,$cleaned_parameters['sanitised_sysname']);

    if($system_exists_result != true)
    {
           updateSystem($app, $systemid, $typeid, $cleaned_parameters);
           $url = $this->router->pathFor('home');
           return $response->withStatus(302)->withHeader('Location', "$url?id=$systemid");
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('updatesystem');

/**
 * Uses method in VotingSystemModel to see if system already exists in database (doesn't check itself obviously).
 *
 * @param $app
 * @param $id
 * @param $system
 * @return mixed
 */
function doesSystemsExist($app, $id, $system)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesSystemsExist($system, $id);
}

/**
 * Updates the details of a system in the VotingSystemModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $systemid
 * @param $typeid
 * @param $cleaned_parameters
 */
function updateSystem($app, $systemid, $typeid, $cleaned_parameters)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->updateSystem($systemid, $cleaned_parameters['sanitised_sysname'], $typeid,
        $cleaned_parameters['sanitised_syssum'], $cleaned_parameters['sanitised_sysinfo']);

    if($verification != true)
    {
        echo 'there was an issue updating system';
    }
}
