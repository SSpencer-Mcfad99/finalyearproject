<?php

/**
 * logout.php - Unsets the user's session and returns to the login page.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/logout', function (Request $request, Response $response, $args) use ($app){
    session_start();
    unset($_SESSION['userid']);
    unset($_SESSION['userrole']);

    $_SESSION['message'] = 'Successfully Logged out';

    $url = $this->router->pathFor('login');
    return $response->withStatus(302)->withHeader('Location', $url);
})->setName('logout');
