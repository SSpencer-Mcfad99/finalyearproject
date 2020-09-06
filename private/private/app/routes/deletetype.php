<?php

/**
 * deletetype.php - Makes use of the information from deletetypes.php.
 * Passes information to VotingSystemModel to perform any database queries.
 *
 * Returns to deletetypes.php after deleting the type and its systems from the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletetype', function(Request $request, Response $response) use ($app){

    session_start();
    $id = $_REQUEST['systype'];
    deleteSystemsWithinType($app, $id);
    deleteType($app, $id);

    $url = $this->router->pathFor('systemtype');
    return $response->withStatus(302)->withHeader('Location', $url);

})->setName('deletetype');

/**
 * Deletes the voting system type by using the type id.
 *
 * @param $app
 * @param $id
 */
function deleteType($app, $id)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteSystemType($id);

    if($verification != true)
    {
        echo 'there was an issue deleting type';
    }
}

/**
 * Deletes all the voting systems within the type to prepare for deleting type.
 *
 * @param $app
 * @param $id
 */
function deleteSystemsWithinType($app, $id)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteSystems($id);

    if($verification != true)
    {
        echo 'there was an issue deleting systems';
    }
}