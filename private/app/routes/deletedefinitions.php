<?php

/**
 *  deletedefinitions.php - Fetches form finddefinition.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/ddef', function(Request $request, Response $response) use ($app){

    session_start();
    if (isset($_SESSION['userid']))
    {
        if($_SESSION['userrole'] == 1)
        {
          $userrole = $_SESSION['userrole'];
          $definitions = getDefinitions($app);

          $html_output = $this->view->render($response,
            'finddefinition.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'method' => 'post',
                'action' => 'deletedefinition',
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Choose Definition to delete',
                'userrole' => $userrole,
                'definitions' => $definitions,
                'rel' => 'Delete Definition'
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

})->setName('ddef');

/** Fetches all definitions from the database using the relevant method in the GlossaryModel.
 *
 * @param $app
 * @return mixed
 */
function getDefinitions($app)
{
    $model = $app->getContainer()->get('glossaryModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $definitions = $model->retrieveDefinitionsFromDB();

    return $definitions;
}
