<?php

/**
 *  homepage.php - Fetches form homepage.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen. Is the first page user comes to after logging in.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/home', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    $userrole = $_SESSION['userrole'];
    if (isset($_SESSION['userid']))
    {
        $html_output = $this->view->render($response,
            'homepage.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Home',
                'userrole' => $userrole
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

})->setName('home');
