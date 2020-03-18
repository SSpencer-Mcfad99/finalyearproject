<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:59
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
                'page_title' => APP_NAME,   //TODO: Title and text need changing
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Home',
                'userrole' => $userrole,
                'method' => 'post',
                'action' => 'processchoice'
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