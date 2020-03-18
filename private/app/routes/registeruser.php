<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:09
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/registeruser', function(Request $request, Response $response) use ($app){

    session_start();
    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters); 

    $username_exists_result = doesUsernameExist($app, $cleaned_parameters['sanitised_username']);
    $email_exists_result = doesEmailExist($app, $cleaned_parameters['sanitised_email']);

    // Long if statement ensures that all the input form parameters are valid: e.g 2 password fields are identical, that the usernames and emails don't already exist in the database,
    // and that there are no spaces in the username field
    if($username_exists_result != true && $cleaned_parameters['password'] === $cleaned_parameters['rpassword'] &&
        $email_exists_result != true && strpos($cleaned_parameters['sanitised_username'], " ") === false )
    {
        // ensures that there are no nulls in the passed values
        $check_nulls = array();
        foreach($cleaned_parameters as $key=>$value)
        {
            if($value != null)
            {
                $check_nulls[$key]=false;
            }
            else
            {
                $check_nulls[$key]=true;
            }
        }

        //
        if(!(in_array(true, $check_nulls)))
        {
            $hashed_password = hashPassword($app, $cleaned_parameters['password']);

            $cleaned_parameters['password'] = ''; // clears the original password completely
            $cleaned_parameters['rpassword'] = ''; // clears the (repeated) original password completely

            createNewUser($app, $cleaned_parameters, $hashed_password);

            $url = $this->router->pathFor('login');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }

    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('registeruser');

function hashPassword($app, $password_to_hash): string
{
    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
    $hashed_password = $bcrypt_wrapper->createHashedPassword($password_to_hash);
    return $hashed_password;
}


function doesUsernameExist($app, $username)
{ // return - if true, user exists - if false, user doesn't exist
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesUsernameExist($username);
}

function doesEmailExist($app, $email)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesEmailExist($email);
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
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('registrationModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_username = $cleaned_parameters['sanitised_username'];
    $cleaned_firstname = $cleaned_parameters['sanitised_first_name'];
    $cleaned_lastname = $cleaned_parameters['sanitised_last_name'];
    $cleaned_email = $cleaned_parameters['sanitised_email'];

    $verification = $model->createNewUser($cleaned_username, $hashed_password, $cleaned_firstname, $cleaned_lastname, $cleaned_email);

    if($verification == true)
    {
        echo '<div style="text-align: center;">Your account has been created, please log in.</div>';
    }
    else
    {
        echo 'there was an issue creating the new user';
    }
}