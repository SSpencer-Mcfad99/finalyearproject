<?php

/**
 *  viewsystems.php - Fetches form viewsystems.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/viewsystems', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid']))
    {
        $userrole = $_SESSION['userrole'];
        $id = $_GET['id'];
        $systems = retrieveSystemsFromDB($app, $id);

        $html_output = $this->view->render($response,
            'viewsystems.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_text' => 'WARNING: Deleting type will also delete all systems within it!',
                'userrole' => $userrole,
                'systems' => $systems,
                'id' => $id
            ]);

        $processed_output = processOutput($app, $html_output);
        return $processed_output;
    } else {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
})->setName('viewsystems');

/** Retrieves all systems with a specified system type from the database, using the relevant method from VotingSystemModel.
 *
 * @param $app
 * @param $id
 * @return mixed
 */
function  retrieveSystemsFromDb($app, $id)
{
    $model = $app->getContainer()->get('votingSystemModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $systems = $model->getSystemOverviewFromDB($id);

    return $systems;
}
