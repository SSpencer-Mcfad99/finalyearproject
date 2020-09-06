<?php

/**
 * adddefinition.php - Makes use of the information from adddefinitions.php.
 * Passes information to GlossaryModel to perform any database queries.
 * Returns to adddefinition.php after adding the word to the glossary table.
 *
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createdefinition', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());
    $word_exists_result = doesWordExist($app, $cleaned_parameters['sanitised_word']);

    if($word_exists_result != true)
    {
        addDefinition($app, $cleaned_parameters);
        $url = $this->router->pathFor('cdef');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }
})->setName('createdefinition');

/**
 * Adds a word to the glossary database by calling the relevant method in the GlossaryModel, which deals with executing the Database Update query.
 *
 * @param $app
 * @param $cleaned_parameters
 */
function addDefinition($app, $cleaned_parameters)
{
    $model = $app->getContainer()->get('glossaryModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->addDefinition($cleaned_parameters['sanitised_word'], $cleaned_parameters['sanitised_def']);

    if($verification != true)
    {
        echo 'there was an issue adding word to glossary';
    }
}

/**
 * Calls to relevant method in GlossaryModel to check to see if word already exists in the glossary.
 *
 * @param $app
 * @param $word
 * @return mixed
 */
function doesWordExist($app, $word)
{
    $model = $app->getContainer()->get('glossaryModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesWordExist($word);
}
