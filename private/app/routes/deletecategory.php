<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 10/03/2020
 * Time: 15:06
 */


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletecategory', function(Request $request, Response $response) use ($app){

    session_start();
    $parameters = $request->getParsedBody();

    deleteCategory($app, $parameters);

    $url = $this->router->pathFor('dcat');
    return $response->withStatus(302)->withHeader('Location', $url);
        
    

})->setName('deletecategory');

/**
 * Updates the user role for a user in the RoleModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $parameters
 */
function deleteCategory($app, $parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('forumSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $categoryid = $parameters['categoryid'];
 

    $verification = $model->deleteReplies($categoryid);
	 if($verification == true)
    {
	$verification2 = $model->deletePosts($categoryid);
        if($verification2 == true)
    {
	$verification3 = $model->deleteCategory($categoryid);
	 if($verification3 == true)
    {
        echo '<div style="text-align: center;">User deleted from database.</div>';
    }
    else
    {
        echo 'there was an issue deleting user';
    }
    }
    else
    {
        echo 'there was an issue deleting user';
    }
    }
    else
    {
        echo 'there was an issue deleting user';
    }

   
}