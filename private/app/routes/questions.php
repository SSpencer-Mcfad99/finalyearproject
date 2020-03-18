<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 02/03/2020
 * Time: 12:46
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/quizlet', function (Request $request, Response $response, $args) use ($app) {


    session_start();
    if (isset($_SESSION['userid'])) {
        $userrole = $_SESSION['userrole'];
		$id = $_GET['id'];
        $retrieved_questions = selectAllQuestionsFromQuiz($id, $app);

        $html_output = $this->view->render($response,
            'quiz.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'userrole' => $userrole,
                'questions' => $retrieved_questions
            ]);
        $processed_output = processOutput($app, $html_output);
        return $processed_output;
    } else {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);

    }
})->setName('quizlet');

function  selectAllQuestionsFromQuiz($id, $app) {
    $model = $app->getContainer()->get('questionModel');
    $settings = $app->getContainer()->get('settings');

    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $questions = $model->retrieveQuestionsFromDB($id);

    return $questions;
}