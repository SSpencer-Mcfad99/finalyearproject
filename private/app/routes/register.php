<?php

/**
 *  register.php - Fetches form register.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen. Registration form to create user account.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/register', function(Request $request, Response $response) use ($app){

    session_start();
    $html_output = $this->view->render($response,
        'register.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'registeruser',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_text' => 'Enter Registration details',
        ]);

    $processed_output = processOutput($app, $html_output);
    return $processed_output;
})->setName('register');
