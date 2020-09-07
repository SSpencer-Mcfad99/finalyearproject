<?php

/**
 * checkanswers.php - Takes the results from quiz.php and checks to see if they're equal to the stored answer for a question.
 * Passes information to QuestionModel to perform any database queries.
 *
 * Goes to quizresult.php to show how many questions the user got correct.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/markanswers', function (Request $request, Response $response) use ($app) {

    session_start();
    $parameters = $request->getParsedBody();

    $quiz_id = $_SESSION['quizid'];
    unset($_SESSION['quizid']);

    $answers = getQuestionsAndAnswers($app, $quiz_id);
    $correctanswers = 0;
    $totalanswers= 0;

    foreach($answers as $answer){
        $question_id = $answer['questionid'];
        if($answer['ans'] == $parameters[$question_id])
        {
            $correctanswers = $correctanswers + 1;
        }
        $totalanswers= $totalanswers + 1;
    }

    $_SESSION['correct'] = $correctanswers;
    $_SESSION['total'] = $totalanswers;

    $url = $this->router->pathFor('result');
    return $response->withStatus(302)->withHeader('Location', $url);
})->SetName('/markanswers');

/** Uses a specified quizid to fetch all the ids and answers to all questions to prepare to check them against the user's answer to question.
 * @param $app
 * @param $quiz_id
 * @return mixed
 */
function getQuestionsAndAnswers($app, $quiz_id)
{
    $model = $app->getContainer()->get('questionModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $answers = $model->retrieveQuestionsAndAnswersFromDB($quiz_id);

    return $answers;
}
