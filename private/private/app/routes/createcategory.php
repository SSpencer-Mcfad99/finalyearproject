<?php

/**
 * createcategory.php - Makes use of the information from createcategories.php.
 * Passes information to VotingSystemModel to perform any database queries.
 *
 * Returns to createcategories.php after adding the category to the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createcategory', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());

    $category_exists_result = doesCategoryExist($app, $cleaned_parameters['sanitised_catname']);
    if($category_exists_result != true)
    {
        addCategory($app, $cleaned_parameters);
        $url = $this->router->pathFor('ccat');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('createcategory');

/**
 * Adds a category to the database by calling the relevant method in the ForumModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 */
function addCategory($app, $cleaned_parameters)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->addCategory($cleaned_parameters['sanitised_catname'], $cleaned_parameters['sanitised_catdesc']);

    if($verification != true)
    {
        echo 'there was an issue creating the new category';
    }
}

/**
 * Refers to relevant method in ForumModel to check if the category already exists in the database.
 *
 * @param $app
 * @param $category
 * @return mixed
 */
function doesCategoryExist($app, $category)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesCategoryExist($category);
}