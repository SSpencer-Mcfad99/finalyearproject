<?php

/**
 *  editroles.php - Fetches form edituserrole.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/editrole', function(Request $request, Response $response) use ($app){

    session_start();
	 if (isset($_SESSION['userid']))
     {
       if($_SESSION['userrole'] == 1)
       {
         $userrole = $_SESSION['userrole'];
	     $id = $_SESSION['userid'];

	     $users = getUsersFromDB($id, $app);
         $html_output = $this->view->render($response,
          'edituserrole.html.twig',
          [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'updaterole',
            'page_title' => APP_NAME,   
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Choose user to update their role',
			'userrole' => $userrole,
			'users' => $users
          ]);
	
         $processed_output = processOutput($app, $html_output);
         return $processed_output;
       }
       else
       {
         $url = $this->router->pathFor('home');
         return $response->withStatus(302)->withHeader('Location', $url);
       }
	 }
	 else
     {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);
     }

})->setName('editrole');

/** Fecthes all users except the logged in user from the database using the relevant method from RoleModel.
 *
 * @param $id
 * @param $app
 * @return mixed
 */
function getUsersFromDB($id, $app)
{
    $model = $app->getContainer()->get('roleModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $users = $model->retrieveUsersFromDB($id);

    return $users;
}