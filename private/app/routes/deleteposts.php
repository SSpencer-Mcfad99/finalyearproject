<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 10/03/2020
 * Time: 15:04
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/dposts', function(Request $request, Response $response) use ($app){

    session_start();
	 if (isset($_SESSION['userid']))
    {
    $userrole = $_SESSION['userrole'];
	$posts = getPosts($app);
	
    $html_output = $this->view->render($response,
        'deletepost.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'deletepost',
            'page_title' => APP_NAME,   
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Choose post to delete',
			'userrole' => $userrole,
			'posts' => $posts
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

})->setName('dposts');

function getPosts($app)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $settings = $app->getContainer()->get('settings');

    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $posts = $model->retrieveTypesFromDB();

    return $posts;
}