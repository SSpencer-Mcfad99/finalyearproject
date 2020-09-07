<?php

/**
 *  selectdefinition.php - Fetches form finddefinition.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/edef', function (Request $request, Response $response) use ($app) {

    session_start();
    if (isset($_SESSION['userid']))
    {
        if($_SESSION['userrole'] == 1)
        {
          $userrole = $_SESSION['userrole'];
          $definitions = getDefinitionsFromDB($app);

          $html_output = $this->view->render($response,
            'finddefinition.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'method' => 'get',
                'action' => 'eddef',
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Choose Definition to delete',
                'userrole' => $userrole,
                'definitions' => $definitions,
                'rel' => 'Edit Definition'
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

})->setName('edef');

/** Fetches all definitions within the glossary using method from GlossaryModel.
 *
 * @param $app
 * @return mixed
 */
function getDefinitionsFromDB($app)
{
    $model = $app->getContainer()->get('glossaryModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $definitions = $model->retrieveDefinitionsFromDB();

    return $definitions;
}

