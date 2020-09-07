<?php

/**
 * createquestion.php - Makes use of the information from createquestions.php.
 * Passes information to QuestionModel to perform any database queries.
 *
 * Returns to createquestions.php after adding the question to the quiz.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createquestion', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app,$request->getParsedBody());
    $question_exists_result = doesQuestionExist($app, $cleaned_parameters['sanitised_question']);

    if(($question_exists_result != true) && (($cleaned_parameters['sanitised_choicea'] === $cleaned_parameters['sanitised_answer']) || ($cleaned_parameters['sanitised_choiceb'] === $cleaned_parameters['sanitised_answer']) ||
      ($cleaned_parameters['sanitised_choicec'] === $cleaned_parameters['sanitised_answer']) || ($cleaned_parameters['sanitised_choiced'] === $cleaned_parameters['sanitised_answer'])))
    {
        addQuestion($app, $cleaned_parameters);
        $url = $this->router->pathFor('cquestion');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('createquestion');

/**
 * Adds a question to the database by calling the relevant method in the QuestionModel, which deals with executing the Database Insert query
 *
 * @param $app
 * @param $cleaned_parameters
 */
function addQuestion($app, $cleaned_parameters)
{
    $model = $app->getContainer()->get('questionModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->createQuestion($cleaned_parameters['sanitised_quizid'], $cleaned_parameters['sanitised_question'], $cleaned_parameters['sanitised_choicea'],
        $cleaned_parameters['sanitised_choiceb'], $cleaned_parameters['sanitised_choicec'], $cleaned_parameters['sanitised_choiced'],
        $cleaned_parameters['sanitised_answer']);

    if($verification != true)
    {
        echo 'there was an issue creating the new question';
    }
}

/** Checks to see if question already exists in the database using relevant method in QuestionModel.
 *
 * @param $app
 * @param $question
 * @return mixed
 */
function doesQuestionExist($app, $question)
{
    $model = $app->getContainer()->get('questionModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    return $model->doesQuestionExist($question);
}
