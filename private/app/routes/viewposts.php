<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 07/01/2020
 * Time: 19:17
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/vpost', function (Request $request, Response $response, $args) use ($app) {


    session_start();
    if (isset($_SESSION['userid'])) {
        $userrole = $_SESSION['userrole'];
		$id = $_GET['id'];
    $retrieved_replies = selectRepliesFromDb($app, $id);

    $html_output = $this->view->render($response,
        'viewposts.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
			'method' => 'post',
            'action' => 'createreply',
            'userrole' => $userrole,
            'replies' => $retrieved_replies
        ]);
		
    $processed_output = processOutput($app, $html_output);
    return $processed_output;
} else {
    $_SESSION['error'] = 'Invalid access.  Please Login first.';
    $url = $this->router->pathFor('login');
    return $response->withStatus(302)->withHeader('Location', $url);

}
})->setName('vposts');

function  selectRepliesFromDb($app, $id) {
    $model = $app->getContainer()->get('forumModel');
    $settings = $app->getContainer()->get('settings');

    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $quizzes = $model->retrieveRepliesFromDB($id);

    return $quizzes;
}