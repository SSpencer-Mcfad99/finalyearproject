<?php

/**
 * deleteuser.php - Makes use of the information from deleteusers.php.
 * Passes information to DetailModel to perform any database queries.
 *
 * Returns to deleteusers.php after deleting the user and its entries to the loginlog from the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deleteuser', function(Request $request, Response $response) use ($app){

    session_start();
    $id = $_REQUEST['user'];
    deleteUserLoginLogs($app, $id);
    deleteUser($app, $id);

    $url = $this->router->pathFor('deleteusers');
    return $response->withStatus(302)->withHeader('Location', $url);

})->setName('deleteuser');

/**
 * Gets the username of a selected user that's not the current admin and their username is passed to the DetailModel,
 * which deals with executing the delete query.
 *
 * @param $app
 * @param $id
 */
function deleteUser($app, $id)
{
    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteUser($id);

    if($verification != true)
    {
        echo 'there was an issue deleting user';
    }
}

/** Deletes all login log entries of a specified user.
 *
 * @param $app
 * @param $id
 */
function deleteUserLoginLogs($app, $id)
{
    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteUserLoginLogs($id);

    if($verification != true)
    {
        echo 'there was an issue deleting user logs';
    }
}