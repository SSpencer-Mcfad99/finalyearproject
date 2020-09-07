<?php

/**
 * deletesystem.php - Makes use of the information from deletesystems.php.
 * Passes information to VotingSystemModel to perform any database queries.
 *
 * Returns to deletesystems.php after deleting the system from the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletesystem', function(Request $request, Response $response) use ($app){

    session_start();
    $system = $_REQUEST['sys'];
    deleteSystem($app, $system);

    $url = $this->router->pathFor('deletesystems');
    return $response->withStatus(302)->withHeader('Location', $url);

})->setName('deletesystem');

/**
 * Deletes the specified system using VotingSystemModel, which deals with executing the Database Delete query
 *
 * @param $app
 * @param $system
 */
function deleteSystem($app, $system)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteSystem($system);

    if($verification != true)
    {
        echo 'there was an issue deleting system';
    }
}
