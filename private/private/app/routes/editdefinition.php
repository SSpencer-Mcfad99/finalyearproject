<?php

/**
 * editdefinition.php - Makes use of the information from editdefinitions.php.
 * Passes information to GlossaryModel to perform any database queries.
 *
 * Returns to viewglossary.php after editing the glossary entry.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/editdefinition', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());

    $word_id = $_SESSION['wordid'];
    $word_exists_result = doesWordsExist($app, $cleaned_parameters['sanitised_word'], $word_id);

    if($word_exists_result != true)
    {
            editDefinition($app, $cleaned_parameters, $word_id);
            unset($_SESSION['wordid']);
            $url = $this->router->pathFor('vwords');
            return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('editdefinition');

/**
 * Edits a glossary entry's details in the database by calling the relevant method in the GlossaryModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $word_id
 */
function editDefinition($app, $cleaned_parameters, $word_id)
{
    $model = $app->getContainer()->get('glossaryModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->editDefinition($word_id, $cleaned_parameters['sanitised_word'], $cleaned_parameters['sanitised_def']);

    if($verification != true)
    {
        echo 'there was an issue editing the glossary entry';
    }
}

/** Checks to see if the word already exists in the database, using the relevant method in the GlossaryModel
 *
 * @param $app
 * @param $word
 * @param $id
 * @return mixed
 */
function doesWordsExist($app, $word, $id)
{
    $model = $app->getContainer()->get('glossaryModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesWordsExist($id, $word);
}
