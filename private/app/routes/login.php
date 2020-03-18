<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:09
 */

/** Login page */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->GET('/', function (Request $request, Response $response) use ($app) {

    session_start();
    $monologWrapper = $app->getContainer()->get('monologWrapper');

    $error_message = null;
    if (isset($_SESSION['error']))
    {
        $monologWrapper->addLogMessage($_SESSION['error'], 'notice');
        $error_message = $_SESSION['error'];
        unset($_SESSION['error']);
    }

    $message = null;
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    }

    $html_output = $this->view->render(
        $response,
        'loginform.html.twig',
        [
            'css_path' => CSS_PATH,
            'js_path' => PASS_JS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post', // post the session data
            'action' => 'authenticate',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Please log in to your account',
            'error_message' => $error_message,
            'message' => $message,
            'register' => 'register',
        ]
    );

    $processed_output = processOutput($app, $html_output);
    return $processed_output;
})->setName('login');

function processOutput($app, $html_output)
{
    $process_output = $app->getContainer()->get('processOutput');
    $html_output = $process_output->processOutput($html_output);
    return $html_output;
}