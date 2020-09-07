<?php

/**
 *  editdefinitions.php - Fetches form editdefinition.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/eddef', function(Request $request, Response $response) use ($app){

    session_start();
    if (isset($_SESSION['userid']))
    {
       if($_SESSION['userrole'] == 1){
         $userrole = $_SESSION['userrole'];
         $id = $_REQUEST['glossary'];
         $_SESSION['wordid'] = $id;

         $definition = getDefinition($app, $id);
         $html_output = $this->view->render($response,
            'editdefinition.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'method' => 'post',
                'action' => 'editdefinition',
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_text' => 'Edit word details to update glossary',
                'userrole' => $userrole,
                'definition' => $definition
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

})->setName('eddef');

/** Fetches a specified definition from the database using method from GlossaryModel.
 * @param $app
 * @param $id
 * @return mixed
 */
function getDefinition($app, $id){
    $model = $app->getContainer()->get('glossaryModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $definition = $model->getDefinitionFromDB($id);

    return $definition;

}
