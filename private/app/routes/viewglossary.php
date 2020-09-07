<?php

/**
 *  viewglossary.php - Fetches form viewglossary.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/vwords', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid'])) {
        $userrole = $_SESSION['userrole'];
        $retrieved_definitions = selectDefinitionsFromDb($app);

        $html_output = $this->view->render($response,
            'viewglossary.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'userrole' => $userrole,
                'definitions' => $retrieved_definitions,
            ]);

        $processed_output = processOutput($app, $html_output);
        return $processed_output;
    } else {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
})->setName('vwords');

/** Retrieves all definitions in the glossary from the database using relevant method from GlossaryModel.
 *
 * @param $app
 * @return mixed
 */
function  selectDefinitionsFromDb($app) {
    $model = $app->getContainer()->get('glossaryModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $definitions = $model->getDefinitions();

    return $definitions;
}
