<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 08/03/2020
 * Time: 14:56
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createsystem', function(Request $request, Response $response) use ($app){

    session_start();
    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters); 

    $system_exists_result = doesSystemExist($app, $cleaned_parameters['sanitised_system_name']);

    if($system_exists_result != true)
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

            createNewSystem($app, $cleaned_parameters);

            $url = $this->router->pathFor('csystem');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }

    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('createsystem');


function doesSystemExist($app, $system)
{ // return - if true, user exists - if false, user doesn't exist
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesSystemExist($system);
}


/**
 * Creates a new user in the database by calling the relevant method in the RegistrationModel, which deals with executing  the Database Insert query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $hashed_password
 */
function createNewSystem($app, $cleaned_parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('votingSystemModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_systemname = $cleaned_parameters['sanitised_system_name'];
    $cleaned_systemtype = $cleaned_parameters['sanitised_system_type'];
	$cleaned_systemsummary = $cleaned_parameters['sanitised_system_summary'];
    $cleaned_systeminformation = $cleaned_parameters['sanitised_system_information'];

    $verification = $model->createNewSystem($cleaned_systemname, $cleaned_systemtype, $cleaned_systemsummary , $cleaned_systeminformation);

    if($verification == true)
    {
        echo '<div style="text-align: center;">Your account has been created, please log in.</div>';
    }
    else
    {
        echo 'there was an issue creating the new user';
    }
}