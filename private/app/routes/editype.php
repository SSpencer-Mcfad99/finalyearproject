<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 08/03/2020
 * Time: 13:43
 */
 
 use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/edittype', function(Request $request, Response $response) use ($app){

    session_start();
	 if (isset($_SESSION['userid']))
    {
    $userrole = $_SESSION['userrole'];
	$id = $_REQUEST['types']
	$type = getSystemType($id, $app);
    $html_output = $this->view->render($response,
        'edittype.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'updatetype',
            'page_title' => APP_NAME,   
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Change contents of the following fields and click button to update type',
			'userrole' => $userrole,
			'type' => $type
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

})->setName('edittype');

function getSystemType($id, $app)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $settings = $app->getContainer()->get('settings');

    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $systems = $model->retrieveTypeFromDB($id);

    return $systems;
}