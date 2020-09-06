<?php

/** Login.php - Checks to see if there are any error messages and logs if so.
 * It also fetches any messages such as log out successful after logging out.
 *
 * Loads the loginform.html.twig file and passes variables to it.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) use ($app) {

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
    if (isset($_SESSION['message']))
    {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    }

    $html_output = $this->view->render(
        $response,
        'loginform.html.twig',
        [
            'css_path' => CSS_PATH,
            'js_path' => JS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
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

/** Fetches the html content of a page and passes it to the ProcessOutput class to be processed.
 *
 * @param $app
 * @param $html_output
 * @return mixed
 */
function processOutput($app, $html_output)
{
    $process_output = $app->getContainer()->get('processOutput');
    $html_output = $process_output->processOutput($html_output);
    return $html_output;
}