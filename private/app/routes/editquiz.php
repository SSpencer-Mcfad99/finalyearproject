<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 12/03/2020
 * Time: 11:09
 */
 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/editquiz', function(Request $request, Response $response) use ($app){

    session_start();
    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters);
	
	$quiz_exists_result = doesQuizExist($app, $cleaned_parameters['quizname']);

if($quiz_exists_result != true)
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
			$quiz_id = $_SESSIONS['quizid'];
            editQuiz($app, $cleaned_parameters, $quiz_id);
            unset($_SESSION['quizid']);
            $url = $this->router->pathFor('equiz');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
	}
    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('createquiz');

/**
 * Edits a user's existing details in the database by calling the relevant method in the DetailModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 */
function editQuiz($app, $cleaned_parameters, $quiz_id)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_quiz_name = $cleaned_parameters['quizname'];
	$cleaned_quiz_description = $cleaned_parameters['quizdesc'];
 

    $verification = $model->editQuiz($quiz_id, $cleaned_quiz_name, $cleaned_quiz_description);

    if($verification == true)
    {
        echo '<div style="text-align: center;">Quiz created</div>';
    }
    else
    {
        echo 'there was an issue editing the quiz';
    }
}

function doesQuizExist($app, $name)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesQuizExist($name);
}