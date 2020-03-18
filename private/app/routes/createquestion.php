<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 12/03/2020
 * Time: 10:34
 */
 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createquestion', function(Request $request, Response $response) use ($app){

    session_start();
    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanParameters($app, $tainted_parameters);
	
    $answer_exists_result = doesAnswerExist($app, $cleaned_parameters['question'], $cleaned_parameters['choicea'], $cleaned_parameters['choiceb'], $cleaned_parameters['choicec'], $cleaned_parameters['choiced'], $cleaned_parameters['answer'],);
    
 if($answer_exists_result == true)
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
            addQuestion($app, $cleaned_parameters);

            $url = $this->router->pathFor('cquestion');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }

    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('createquestion');

/**
 * Edits a user's existing details in the database by calling the relevant method in the DetailModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $hashed_password
 */
function addQuestion($app, $cleaned_parameters)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_quizid = $cleaned_parameters['quizid'];
	$cleaned_question = $cleaned_parameters['question'];
	$cleaned_choice_1 = $cleaned_parameters['choicea'];
	$cleaned_choice_2 = $cleaned_parameters['choiceb'];
	$cleaned_choice_3 = $cleaned_parameters['choicec'];
	$cleaned_choice_4 = $cleaned_parameters['choiced'];
	$cleaned_answer = $cleaned_parameters['answer'];

    $verification = $model->addQuestion($cleaned_quizid, $cleaned_question, $cleaned_choice_1, $cleaned_choice_2, $cleaned_choice_3, $cleaned_choice_4, $cleaned_answer);

    if($verification == true)
    {
        echo '<div style="text-align: center;">Your details have been updated</div>';
    }
    else
    {
        echo 'there was an issue creating the new user';
    }
}

function doesAnswerExist($app, $question, $choice1, $choice2, $choice3, $choice4, $ans)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('quizModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    return $model->doesAnswerExist($question, $choice1, $choice2, $choice3, $choice4, $ans);
}