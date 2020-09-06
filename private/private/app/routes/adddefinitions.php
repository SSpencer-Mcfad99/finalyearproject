<?php

/**
 *  adddefinitions.php - Fetches form adddefinition.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/cdef', function(Request $request, Response $response) use ($app){

    session_start();
    if (isset($_SESSION['userid']))
    {
        if($_SESSION['userrole'] == 1)
        {
          $userrole = $_SESSION['userrole'];
          $html_output = $this->view->render($response,
            'adddefinition.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'method' => 'post',
                'action' => 'createdefinition',
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_text' => 'Add word details to be added to glossary',
                'userrole' => $userrole
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

})->setName('cdef');
