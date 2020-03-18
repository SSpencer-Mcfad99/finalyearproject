<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 08/03/2020
 * Time: 12:01
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/detailedit', function(Request $request, Response $response) use ($app){

    session_start();
    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters);

    $username_exists_result = doesUsernameExist($app, $cleaned_parameters['sanitised_username']);
    $email_exists_result = doesEmailExist($app, $cleaned_parameters['sanitised_email']);

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

            editUser($app, $cleaned_parameters, $hashed_password);

            $url = $this->router->pathFor('login');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }

    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('detailedit');

function hashPassword($app, $password_to_hash): string
{
    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
    $hashed_password = $bcrypt_wrapper->createHashedPassword($password_to_hash);
    return $hashed_password;
}

function doesUsernameExist($app, $username)
{ // return - if true, user exists - if false, user doesn't exist
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesUsernameExist($username);
}

function doesEmailExist($app, $email)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesEmailExist($email);
}

/**
 * Edits a user's existing details in the database by calling the relevant method in the DetailModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $hashed_password
 */
function editUser($app, $cleaned_parameters, $hashed_password)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_username = $cleaned_parameters['sanitised_username'];
    $cleaned_firstname = $cleaned_parameters['sanitised_first_name'];
    $cleaned_lastname = $cleaned_parameters['sanitised_last_name'];
    $cleaned_email = $cleaned_parameters['sanitised_email'];

    $verification = $model->editUser($cleaned_username, $hashed_password, $cleaned_firstname, $cleaned_lastname, $cleaned_email);

    if($verification == true)
    {
        echo '<div style="text-align: center;">Your details have been updated</div>';
    }
    else
    {
        echo 'there was an issue creating the new user';
    }
}