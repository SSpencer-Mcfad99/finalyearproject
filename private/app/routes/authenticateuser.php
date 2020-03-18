<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:09
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->POST('/authenticate', function (Request $request, Response $response, $args) use ($app) {

    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters);

    session_start();
    $monologWrapper = $app->getContainer()->get('monologWrapper');

    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');

    $user_id_result = checkUserID($app, $cleaned_parameters['sanitised_username']);
    $user_id_result = intval($user_id_result);

    $routeRedirect = 'login';
    if ($user_id_result != null) {
        if ($user_id_result != 'Unfortunately Login was unable to connect.  Please try again later.') {
            $check_user_password = checkUserPassword($app, $user_id_result, $cleaned_parameters['sanitised_username']);

            $user_authenticated_result = $bcrypt_wrapper->authenticatePassword($cleaned_parameters['password'], $check_user_password);

            // uses switch statement to prevent MySQL PDO error of incorrect integer value when trying to insert 'false'
            switch ($user_authenticated_result) {
                case true:
                    $user_authenticated_result = 1;
                    $_SESSION['userid'] = $cleaned_parameters['sanitised_username'];
                    $_SESSION['userrole'] = checkUserRole($app, $user_id_result);
                    $monologWrapper->addLogMessage($_SESSION['userid'] . ' logged in', 'info');
                    $routeRedirect = 'home';
                    break;
                case false:
                    $user_authenticated_result = 0;
                    $_SESSION['error'] = 'Invalid Login Attempt';
                    break;
            }
            logAttemptToDatabase($app, $user_id_result, $user_authenticated_result);
        } else {
            $_SESSION['error'] = 'Unfortunately Login was unable to connect.  Please try again later.';
            $monologWrapper->addLogMessage('Login attempt unable to connect', 'warning');
        }

        //
    } else // This signifies that there is NO SUCH USER in the database
    {
        $_SESSION['error'] = 'Invalid Login Attempt';
    }
    $url = $this->router->pathFor($routeRedirect);
    return $response->withStatus(302)->withHeader('Location', $url);
})->setName('authenticate');


function checkUserPassword($app, $userid, $username)
{
    $settings = $app->getContainer()->get('settings');
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $password_result = $model->checkUserPassword($userid, $username);

    return $password_result;
}

function checkUserID($app, $username)
{
    $settings = $app->getContainer()->get('settings');
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $userid = $model->checkUserID($username);

    return $userid;
}

function checkUserRole($app, $userid) {
    $settings = $app->getContainer()->get('settings');
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $userrole = $model->checkUserRole($userid);

    return $userrole;
}

function logAttemptToDatabase($app, $userid, $login_result)
{
    $settings = $app->getContainer()->get('settings');
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);
    $model->storeLoginAttempt($userid, $login_result);
}

function cleanParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    foreach ($tainted_parameters as $key => $param) {
        if ($key != 'password' && $key != 'rpassword') {
            $cleaned_parameters['sanitised_' . $key] = $validator->sanitiseString($param);
        } else {
            $cleaned_parameters[$key] = $tainted_parameters[$key];
        }
    }

    return $cleaned_parameters;
}