<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 07/01/2020
 * Time: 19:17
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/vcat', function (Request $request, Response $response, $args) use ($app) {


    session_start();
    if (isset($_SESSION['userid'])) {
        $userrole = $_SESSION['userrole'];
		$id = $_GET['id'];
    $retrieved_posts = selectPostsFromDb($app, $id);

    $html_output = $this->view->render($response,
        'viewcategories.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'userrole' => $userrole,
            'posts' => $retrieved_posts
        ]);
		
    $processed_output = processOutput($app, $html_output);
    return $processed_output;
} else {
    $_SESSION['error'] = 'Invalid access.  Please Login first.';
    $url = $this->router->pathFor('login');
    return $response->withStatus(302)->withHeader('Location', $url);

}
})->setName('vcat');

function  selectPostsFromDb($app, $id) {
    $model = $app->getContainer()->get('forumModel');
    $settings = $app->getContainer()->get('settings');

    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $quizzes = $model->retrievePostsFromDB($id);

    return $quizzes;
}