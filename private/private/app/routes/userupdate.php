<?php

/**
 *  userupdate.php - Fetches form edituserdetails.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/userupdate', function(Request $request, Response $response) use ($app){

    session_start();
	 if (isset($_SESSION['userid']))
    {
      $userrole = $_SESSION['userrole'];
	  $username = $_SESSION['userid'];
	  $details = selectUserDetailsFromDb($app, $username);

      $html_output = $this->view->render($response,
        'edituserdetails.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'detailedit',
            'page_title' => APP_NAME,   
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Enter updated user details',
			'userrole' => $userrole,
			'user' => $details
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

})->setName('userupdate');

/**Fetches all information about a user except userrole and password from the database using relevant method in DetailModel.
 *
 * @param $app
 * @param $username
 * @return mixed
 */
function selectUserDetailsFromDb($app, $username)
{
    $model = $app->getContainer()->get('detailModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $details = $model->retrieveDetailsFromDB($username);

    return $details;
}