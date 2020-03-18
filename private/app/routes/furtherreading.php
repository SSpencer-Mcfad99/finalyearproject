<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 07/01/2020
 * Time: 19:17
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/furtherreading', function (Request $request, Response $response, $args) use ($app) {

    session_start();

    if (isset($_SESSION['userid']))
    {
        $userrole = $_SESSION['userrole'];
    $html_output = $this->view->render($response,
        'furtherreading.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Further Reading',
            'page_heading_3' => 'Resources about voting systems',
            'page_heading_4' => 'Statistics',
            'page_heading_5' => 'Further Reading',
            'userrole' => $userrole
        ]);
    $processed_output = processOutput($app, $html_output);
    return $processed_output;
    }
    else {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);

    }
     })->setName('furtherreading');
