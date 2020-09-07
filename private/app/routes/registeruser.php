<?php

/**
 * registeruser.php - Makes use of the information from register.php.
 * Passes information to RegistrationModel to perform any database queries.
 *
 * Returns to login.php after adding a user account to the database.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/registeruser', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());
    $user_exists_result = doesUserExist($app, $cleaned_parameters['sanitised_username'], $cleaned_parameters['sanitised_email']);

    if($user_exists_result != true && $cleaned_parameters['password'] === $cleaned_parameters['rpassword'] &&
        strpos($cleaned_parameters['sanitised_username'], " ") === false )
    {
        $hashed_password = hashPassword($app, $cleaned_parameters['password']);
        $cleaned_parameters['password'] = '';
        $cleaned_parameters['rpassword'] = '';
        createNewUser($app, $cleaned_parameters, $hashed_password);

        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);
    }
    else
    {
        echo 'Sorry, there was an issue with your entered values';
    }

})->setName('registeruser');

/** Hashes an entered password using the createHashedMethod within the BCryptWrapper.
 *
 * @param $app
 * @param $password_to_hash
 * @return string
 */
function hashPassword($app, $password_to_hash): string
{
    $hashed_password = $app->getContainer()->get('bcryptWrapper')->createHashedPassword($password_to_hash);
    return $hashed_password;
}

/** Checks to see if someone has used a specified username and email to register using methods from RegistrationModel.
 * Returns a value based on if both checks are passed.
 *
 * @param $app
 * @param $username
 * @param $email
 * @return mixed
 */
function doesUserExist($app, $username, $email)
{
    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $check = $model->doesUserExist($username, $email);

    if($check === true) {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * Creates a new user in the database by calling the relevant method in the RegistrationModel, which deals with executing  the Database Insert query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $hashed_password
 */
function createNewUser($app, $cleaned_parameters, $hashed_password)
{
    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->createNewUser($cleaned_parameters['sanitised_username'], $hashed_password, $cleaned_parameters['sanitised_first_name'], $cleaned_parameters['sanitised_last_name'], $cleaned_parameters['sanitised_email']);

    if($verification != true)
    {
        echo 'there was an issue creating the new user';
    }
}
