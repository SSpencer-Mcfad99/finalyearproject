<?php

/**
 * updaterole.php - Makes use of the information from editroles.php.
 * Passes information to DetailModel to perform any database queries.
 *
 * Returns to editroles.php after updating the role of a user in the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/updaterole', function(Request $request, Response $response) use ($app){

    session_start();
    $user = $_REQUEST['userid'];
    $role = $_REQUEST['userrole'];
    updateUserRole($app, $user, $role);

    $url = $this->router->pathFor('editrole');
    return $response->withStatus(302)->withHeader('Location', $url);

})->setName('updaterole');

/**
 * Updates the user role for a user in the RoleModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $user
 * @param $role
 */
function updateUserRole($app, $user, $role)
{
    $model = $app->getContainer()->get('roleModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->updateUserRole($user, $role);

    if($verification != true)
    {
        echo 'there was an issue updating role';
    }
}