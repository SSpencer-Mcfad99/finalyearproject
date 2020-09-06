<?php

/**
 * createtype.php - Makes use of the information from createtypes.php.
 * Passes information to VotingSystemModel to perform any database queries.
 *
 * Returns to createtypes.php after adding the type to the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createtype', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());
    $type_exists_result = doesTypeExist($app, $cleaned_parameters['sanitised_typename']);

    if($type_exists_result != true)
    {
            createNewType($app, $cleaned_parameters);
            $url = $this->router->pathFor('ctype');
            return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('createtype');

/** Checks to see if system type already exists in the database using relevant method from VotingSystemModel.
 *
 * @param $app
 * @param $type
 * @return mixed
 */
function doesTypeExist($app, $type)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesTypeExist($type);
}


/**
 * Creates a new type in the database by calling the relevant method in the VotingSystemModel, which deals with executing  the Database Insert query
 *
 * @param $app
 * @param $cleaned_parameters
 */
function createNewType($app, $cleaned_parameters)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->createNewType($cleaned_parameters['sanitised_typename'], $cleaned_parameters['sanitised_typedesc']);

    if($verification != true)
    {
        echo 'there was an issue creating the new type';
    }
}