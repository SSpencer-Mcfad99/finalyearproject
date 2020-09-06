<?php

/**
 *  editsystem.php - Fetches form editsystem.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

 use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/editsystem', function(Request $request, Response $response) use ($app){

    session_start();
	 if (isset($_SESSION['userid']))
     {
       $userrole = $_SESSION['userrole'];
	   $id = $_REQUEST['id'];
	   $_SESSION['id'] = $id;
	   $sys = getSystem($app, $id);
	   $types = getAllSystemTypes($app);

       $html_output = $this->view->render($response,
        'editsystem.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'updatesystem',
            'page_title' => APP_NAME,   
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Enter updated information into the fields and click button to update system',
			'userrole' => $userrole,
			'sys' => $sys,
            'types' => $types
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

})->setName('editsystem');

/** Fetches a specified system from the database using relevant method from VotingSystemModel.
 *
 * @param $app
 * @param $id
 * @return mixed
 */
function getSystem($app, $id)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $system = $model->retrieveSystemFromDB($id);

    return $system;
}

/** Fetches all the system types within the database using the specified method in VotingSystemModel.
 *
 * @param $app
 * @return mixed
 */
function getAllSystemTypes($app)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $types = $model->retrieveSystemTypesFromDB();

    return $types;
}