<?php

/**
 *  votingsystemtype.php - Fetches form votingsystemtype.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/systemtype', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid']))
    {
       $userrole = $_SESSION['userrole'];
       $types = getVotingSystemTypes($app);

       $html_output = $this->view->render($response,
        'votingsystemtype.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Voting System Types',
            'userrole' => $userrole,
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
})->setName('systemtype');

/** Fetches all system types from the database using relevant method from VotingSystemModel.
 *
 * @param $app
 * @return mixed
 */
function getVotingSystemTypes($app)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $types = $model->retrieveSystemTypesFromDB();
    return $types;
}
