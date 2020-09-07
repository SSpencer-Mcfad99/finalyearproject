<?php

/**
 *  quizresult.php - Fetches form result.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/result', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid'])) {
        $userrole = $_SESSION['userrole'];
        $correct = $_SESSION['correct'];
        $total = $_SESSION['total'];
        unset($_SESSION['correct']);
        unset($_SESSION['total']);

        $html_output = $this->view->render($response,
            'result.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Results:',
                'userrole' => $userrole,
                'correct' => $correct,
                'total' => $total
            ]);

        $processed_output = processOutput($app, $html_output);
        return $processed_output;
    } else {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
})->setName('result');
