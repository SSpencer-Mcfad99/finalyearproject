<?php

/**
 * deletedefinition.php - Makes use of the information from deletedefinitions.php.
 * Passes information to GlossaryModel to perform any database queries.
 *
 * Returns to deletedefinitions.php after deleting the word from the glossary.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletedefinition', function(Request $request, Response $response) use ($app){

    session_start();
    $word_id = $_REQUEST['glossary'];
    deleteDefinition($app, $word_id);

    $url = $this->router->pathFor("vwords");
    return $response->withStatus(302)->withHeader('Location', $url);

})->setName('deletedefinition');

/**
 * Deletes an entry in the glossary using the GlossaryModel, which deals with executing the Database Delete query
 *
 * @param $app
 * @param $word_id
 */
function deleteDefinition($app, $word_id)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('glossaryModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $verification = $model->deleteDefinition($word_id);

    if($verification != true)
    {
        echo 'there was an issue deleting glossary entry';
    }
}
