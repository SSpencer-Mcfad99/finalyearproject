<?php

/**
 *  editype.php - Fetches form edittype.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

 use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/edittype', function(Request $request, Response $response) use ($app){

    session_start();
	 if (isset($_SESSION['userid']))
    {
      if($_SESSION['userrole'] == 1){
        $userrole = $_SESSION['userrole'];
	    $id = $_REQUEST['id'];
	    $type = getSystemType($id, $app);
        $html_output = $this->view->render($response,
          'edittype.html.twig',
          [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => "updatetype?id=$id",
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

})->setName('edittype');

/** Fetches specified system type from database using the relevant method from VotingSystemModel.
 *
 * @param $id
 * @param $app
 * @return mixed
 */
function getSystemType($id, $app)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $types = $model->getTypeFromDB($id);

    return $types;
}
