<?php

/**
 * aboutus.php - A general page with some information about the website
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/about', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid']))
    {
        $userrole = $_SESSION['userrole'];
        $html_output = $this->view->render($response,
            'aboutus.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'About us',
                'page_heading_3' => 'Who are we?',
                'page_heading_4' => 'What is our goal?',
                'page_heading_5' => 'When did we start?',
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
})->setName('about');