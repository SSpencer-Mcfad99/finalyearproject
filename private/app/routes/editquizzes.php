<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:09
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/edquiz', function(Request $request, Response $response) use ($app){

    session_start();
	 if (isset($_SESSION['userid']))
    {
    $userrole = $_SESSION['userrole'];
	$id = $_REQUEST['quiz'];
	$_SESSION['quizid'] = $id;
	$quiz = getQuiz($app, $id);
    $html_output = $this->view->render($response,
        'editquiz.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'edquiz',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Edit quiz details',
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

})->setName('edquiz');

function  getQuiz($app, $id) {
    $model = $app->getContainer()->get('quizModel');
    $settings = $app->getContainer()->get('settings');

    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $quizzes = $model->getQuiz($id);;

    return $quizzes;
}