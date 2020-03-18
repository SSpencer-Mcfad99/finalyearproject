<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 12/03/2020
 * Time: 11:00
 */
 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/deletequiz', function(Request $request, Response $response) use ($app){

    session_start();
    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters);

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
            deleteQuiz($app, $cleaned_parameters);

            $url = $this->router->pathFor('dquiz');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }

    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('deletequiz');

/**
 * Edits a user's existing details in the database by calling the relevant method in the DetailModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 */
function deleteQuiz($app, $cleaned_parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_quiz_id = $cleaned_parameters['quiz'];
 
    $verification = $model->deleteQuestions($cleaned_quiz_id);
	
	
	 if($verification == true)
    {
	$verification2 = $model->deleteQuiz($cleaned_quiz_id);
         if($verification2 == true)
    {
        echo '<div style="text-align: center;">Quiz deleted</div>';
    }
    else
    {
        echo 'there was an issue deleting quiz';
    }
    
    }
    else
    {
        echo 'there was an issue deleting quiz';
    }
    

   
}