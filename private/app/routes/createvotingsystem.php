<?php

/**
 * createvotingsystem.php - Makes use of the information from createsystem.php.
 * Passes information to VotingSystemModel to perform any database queries.
 *
 * Returns to createssystem.php after adding the voting system to the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createsystem', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());
    $system_exists_result = doesSystemExist($app, $cleaned_parameters['sanitised_sysname']);

    if($system_exists_result != true)
    {
            createNewSystem($app, $cleaned_parameters);
            $url = $this->router->pathFor('csystem');
            return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('createsystem');

/** Checks to see if system already exists in the database using the relevant method from VotingSystemModel.
 *
 * @param $app
 * @param $system
 * @return mixed
 */
function doesSystemExist($app, $system)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesSystemExist($system);
}


/**
 * Creates a new system in the database by calling the relevant method in the VotingSystemModel, which deals with executing  the Database Insert query
 *
 * @param $app
 * @param $cleaned_parameters
 */
function createNewSystem($app, $cleaned_parameters)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->createNewSystem($cleaned_parameters['sanitised_sysname'], $cleaned_parameters['sanitised_systype'], $cleaned_parameters['sanitised_syssum'], $cleaned_parameters['sanitised_sysinfo']);

    if($verification != true)
    {
        echo 'there was an issue creating the new system';
    }
}
