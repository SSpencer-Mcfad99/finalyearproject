<?php

/**
 * detailedit.php - Makes use of the information from userupdate.php.
 * Passes information to DetailModel to perform any database queries.
 *
 * Logs the user out after updating the user details within the database to ensure the user can log in with new login credentials.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/detailedit', function(Request $request, Response $response) use ($app){

    session_start();
    $cleaned_parameters = cleanParameters($app, $request->getParsedBody());

    $user_id_result = intval(checkUsersID($app, $_SESSION['userid']));

    if($user_id_result != null) {
        $user_exists_result = doesUsersExist($app, $user_id_result, $cleaned_parameters['sanitised_username'], $cleaned_parameters['sanitised_email']);

        $check_user_password = checkUserPasswords($app, $user_id_result, $_SESSION['userid']);
        $user_authenticated_result = $app->getContainer()->get('bcryptWrapper')->authenticatePassword($cleaned_parameters['cpassword'], $check_user_password);

         if($user_authenticated_result == true) {

             if ($cleaned_parameters['password'] == "" && $cleaned_parameters['rpassword'] == "") {
                 if ($user_exists_result != true && strpos($cleaned_parameters['sanitised_username'], " ") === false) {
                     editUserNoPass($app, $cleaned_parameters, $user_id_result);
                     $routeRedirect = 'logout';
                 } else {
                     echo 'Sorry, there was an issue with your entered values';
                     return;
                 }
             } elseif ($cleaned_parameters['password'] === $cleaned_parameters['rpassword']) {
                 if ($user_exists_result != true && strpos($cleaned_parameters['sanitised_username'], " ") === false) {
                     $hashed_password = hashPasswords($app, $cleaned_parameters['password']);

                     $cleaned_parameters['password'] = '';
                     $cleaned_parameters['rpassword'] = '';

                     editUser($app, $cleaned_parameters, $hashed_password, $user_id_result);
                     $routeRedirect = 'logout';
                 }
                 else
                 {
                   echo 'Sorry, there was an issue with your entered values';
                   return;
                 }
             }
             else
             {
                 echo 'Newly entered passwords need to match!';
                 return;
             }
         }
         else
         {
            echo 'Incorrect Password!';
            return;
         }
    }
    else
    {
        echo 'Invalid user!';
        return;
    }

    $url = $this->router->pathFor($routeRedirect);
    return $response->withStatus(302)->withHeader('Location', $url);
})->setName('detailedit');

/** Hashes the updated password in the BCryptWrapper to prepare to store in database.
 *
 * @param $app
 * @param $password_to_hash
 * @return string
 */
function hashPasswords($app, $password_to_hash): string
{
    $hashed_password =  $app->getContainer()->get('bcryptWrapper')->createHashedPassword($password_to_hash);
    return $hashed_password;
}

/**Checks to see if a user's email and username already exists in database (doesn't check against current username of account) using method in DetailModel.
 *
 * @param $app
 * @param $username
 * @param $id
 * @param $email
 * @return mixed
 */
function doesUsersExist($app, $username, $id, $email)
{
    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $check1 = $model->doesUsersExist($username, $id);
    $check2 = $model->doesEmailExist($email,$id);

    if($check1 === true && $check2 === true) {
        return true;
    } else {
        return false;
    }
}

/** Checks to see if user exists in database using method in LoginModel.
 *
 * @param $app
 * @param $username
 * @return mixed
 */
function checkUsersID($app, $username)
{
    $model = $app->getContainer()->get('loginModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);
    $userid = $model->checkUserID($username);

    return $userid;
}

/** Checks to see if entered password matches password stored in database using method in DetailModel.
 * @param $app
 * @param $userid
 * @param $username
 * @return mixed
 */
function checkUserPasswords($app, $userid, $username)
{
    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);
    $password_result = $model->checkUserPassword($userid, $username);

    return $password_result;
}

/**
 * Edits a user's existing details in the database by calling the relevant method in the DetailModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $hashed_password
 */
function editUser($app, $cleaned_parameters, $hashed_password, $user_id)
{
    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->editUser($cleaned_parameters['sanitised_username'], $hashed_password, $cleaned_parameters['sanitised_first_name'],
        $cleaned_parameters['sanitised_last_name'], $cleaned_parameters['sanitised_email'], $user_id);

    if($verification != true)
    {
        echo 'there was an issue editing user details';
    }
}

/** Variation of editUser except it doesn't update the password. Method in DetailModel.
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $user_id
 */
function editUserNoPass($app, $cleaned_parameters, $user_id)
{
    $model = $app->getContainer()->get('detailModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $verification = $model->editUserNoPass($cleaned_parameters['sanitised_username'], $cleaned_parameters['sanitised_first_name'],
        $cleaned_parameters['sanitised_last_name'], $cleaned_parameters['sanitised_email'], $user_id);

    if($verification != true)
    {
        echo 'there was an issue editing user details';
    }
}