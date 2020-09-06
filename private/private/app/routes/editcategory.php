<?php

/**
 * editcategory.php gets the information the admin input into the fields in editcategory.html.twig as well as the session
 * data containing the category id and the unsets it once applied to a variable.
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/editcat', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());

    $id = $_SESSION['id'];
    unset($_SESSION['id']);
    $category_exists_result = doesNewCategoryExist($app, $cleaned_parameters['sanitised_catname'], $id);

    if($category_exists_result != true) {
            editCategory($app, $cleaned_parameters, $id);
            $url = $this->router->pathFor('forum');
            return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('editcat');

/**
 * Edits a category's existing details in the database by calling the relevant method in the ForumModel,
 * which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $id
 */
function editCategory($app, $cleaned_parameters, $id)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->editCategory($cleaned_parameters['sanitised_catname'], $cleaned_parameters['sanitised_catdesc'] , $id);

    if($verification != true)
    {
        echo 'there was an issue editing category';
    }
}

/**
 * Updated version of doesCategoryExist in addCategory.php that checks to see if category exists (other than if the
 * category has the name itself by using an id. ForumModel deals with executing the SQL query.
 *
 * @param $app
 * @param $category
 * @param $id
 * @return mixed
 */
function doesNewCategoryExist($app, $category, $id)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesNewCategoryExist($category, $id);
}