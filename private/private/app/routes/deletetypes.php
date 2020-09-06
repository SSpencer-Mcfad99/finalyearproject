<?php

/**
 *  deletetypes.php - Fetches form deletetype.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/deletetypes', function(Request $request, Response $response) use ($app){

    session_start();
    if (isset($_SESSION['userid']))
    {
       if($_SESSION['userrole'] == 1)
       {
         $userrole = $_SESSION['userrole'];
         $types = getTypes($app);

         $html_output = $this->view->render($response,
            'deletetype.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'method' => 'post',
                'action' => 'deletetype',
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Choose type to delete',
                'userrole' => $userrole,
                'types' => $types
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

})->setName('deletetypes');

/** Retrieves all types from database using relevant method in VotingSystemModel.
 *
 * @param $app
 * @return mixed
 */
function getTypes($app)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $types = $model->retrieveSystemTypesFromDB();

    return $types;
}