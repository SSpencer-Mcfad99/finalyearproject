<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 08/03/2020
 * Time: 11:57
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/deleteusers', function(Request $request, Response $response) use ($app){

    session_start();
	 if (isset($_SESSION['userid']))
    {
    $userrole = $_SESSION['userrole'];
	$id = $_SESSION['userid'];
	$users = getUsers($id, $app);
	
    $html_output = $this->view->render($response,
        'deleteuser.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'deleteuser',
            'page_title' => APP_NAME,   
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Choose user to delete',
			'userrole' => $userrole,
			'users' => $users
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

})->setName('deleteusers');

function getUsers($id, $app)
{
    $model = $app->getContainer()->get('roleModel');
    $settings = $app->getContainer()->get('settings');

    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $users = $model->retrieveUsersFromDB($id);

    return $users;
}