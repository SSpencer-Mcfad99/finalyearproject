<?php

/**
 * authenticateuser.php - Makes use of the information from login.php.
 * Passes information to LoginModel to perform any database queries.
 *
 * Makes use of the BCryptWrapper to check if password is equal to hashed password in database.
 * Makes use of MonologWrapper to add user login results to a log file or to log any errors.
 *
 * Redirects to homepage if login is successful otherwise redirects to login.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/authenticate', function (Request $request, Response $response, $args) use ($app) {

    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());
    session_start();
    $monologWrapper = $app->getContainer()->get('monologWrapper');
    $user_id_result = intval(checkUserID($app, $cleaned_parameters['sanitised_username']));
    $redirect = 'login';
    if ($user_id_result != null)
    {
        if ($user_id_result != 'Unfortunately Login was unable to connect.  Please try again later.')
        {
            $authenticated_result =  $app->getContainer()->get('bcryptWrapper')->authenticatePassword($cleaned_parameters['password'], checkUserPassword($app, $user_id_result, $cleaned_parameters['sanitised_username']));

            switch ($authenticated_result)
            {
                case true:
                    $authenticated_result = 1;
                    $_SESSION['userid'] = $cleaned_parameters['sanitised_username'];
                    $_SESSION['userrole'] = checkUserRole($app, $user_id_result);
                    $monologWrapper->addLogMessage($_SESSION['userid'] . ' successfully logged in', 'info');
                    $redirect = 'home';
                    break;
                case false:
                    $authenticated_result = 0;
                    $_SESSION['error'] = 'Invalid Login Credentials';
                    break;
            }
            logAttemptToDatabase($app, $user_id_result, $authenticated_result);
        }
        else
        {
            $_SESSION['error'] = $user_id_result;
            $monologWrapper->addLogMessage('Unable to login', 'warning');
        }
    }
    else
    {
        $_SESSION['error'] = 'User does not exist in Database!';
    }

    $url = $this->router->pathFor($redirect);
    return $response->withStatus(302)->withHeader('Location', $url);
})->setName('authenticate');

/**Checks to see if the user exists in the database to assist in authorisation.
 *
 * @param $app
 * @param $username
 * @return mixed
 */
function checkUserID($app, $username)
{
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);
    $userid = $model->checkUserID($username);

    return $userid;
}

/** Checks to see if password entered matches password stored for the user in the database.
 *
 * @param $app
 * @param $userid
 * @param $username
 * @return mixed
 */
function checkUserPassword($app, $userid, $username)
{
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);
    $password_result = $model->checkUserPassword($userid, $username);

    return $password_result;
}

/** Checks whether the user is an admin or general user using method in LoginModel.
 *
 * @param $app
 * @param $userid
 * @return mixed
 */
function checkUserRole($app, $userid) {
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);
    $userrole = $model->checkUserRole($userid);

    return $userrole;
}

/** Passes all parameters excluding the passwords to the validator to sanitise them.
 *
 * @param $app
 * @param $tainted_parameters
 * @return array
 */
function cleanParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    foreach ($tainted_parameters as $key => $param)
    {
        if ($key != 'password' && $key != 'rpassword' && $key != 'cpassword')
        {
            $cleaned_parameters['sanitised_' . $key] = $app->getContainer()->get('validator')->sanitiseString($param);
        }
        else
        {
            $cleaned_parameters[$key] = $tainted_parameters[$key];
        }
    }

    return $cleaned_parameters;
}

/** Passes the userid and login result to the LoginModel so it can be stored in the login logs.
 *
 * @param $app
 * @param $userid
 * @param $login_result
 */
function logAttemptToDatabase($app, $userid, $login_result)
{
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);
    $model->storeLoginAttempt($userid, $login_result);
}

