<?php

/**
 * deletecategory.php - Makes use of the information from deletecategories.php.
 * Passes information to ForumModel to perform any database queries.
 *
 * Returns to deletecategories.php after deleting the category and its posts from the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletecategory', function(Request $request, Response $response) use ($app){

    session_start();
    $category = $_REQUEST['categories'];
    deleteCategory($app, $category);

    $url = $this->router->pathFor('dcat');
    return $response->withStatus(302)->withHeader('Location', $url);

})->setName('deletecategory');

/**
 * Deletes a category from the database using the ForumModel, which deals with executing the Database Delete query
 *
 * @param $app
 * @param $category
 */
function deleteCategory($app, $category)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->deleteReplies($category);
    if($verification == true)
    {
	  $verification2 = $model->deletePosts($category);
	  if($verification2 == true)
      {
	    $verification3 = $model->deleteCategory($category);
	    if($verification3 != true)
        {
          echo 'there was an issue deleting category';
        }
      }
      else
      {
        echo 'there was an issue deleting posts from category';
      }
    }
    else
    {
      echo 'there was an issue deleting replies within category';
    }
}
