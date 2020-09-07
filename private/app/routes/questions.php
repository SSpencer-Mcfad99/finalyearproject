<?php

/**
 *  questions.php - Fetches form quiz.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/quizlet', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid'])) {
        $userrole = $_SESSION['userrole'];
		$id = $_GET['id'];
		$_SESSION['quizid'] = $id;
        $retrieved_questions = selectAllQuestionsFromQuiz($id, $app);

        $html_output = $this->view->render($response,
            'quiz.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'method' => 'post',
                'action' => 'markanswers',
                'page_heading_1' => APP_NAME,
                'userrole' => $userrole,
                'questions' => $retrieved_questions
            ]);

        $processed_output = processOutput($app, $html_output);
        return $processed_output;
    }
    else
    {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
})->setName('quizlet');

/** gets all questions from a specified quiz to get the quiz on the screen.
 *
 * @param $id
 * @param $app
 * @return mixed
 */
function  selectAllQuestionsFromQuiz($id, $app) {
    $model = $app->getContainer()->get('questionModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $questions = $model->retrieveQuestionsFromDB($id);

    return $questions;
}
