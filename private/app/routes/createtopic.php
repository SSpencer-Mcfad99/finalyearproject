<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 10/03/2020
 * Time: 12:57
 */
 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createpost', function(Request $request, Response $response) use ($app){

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
		    $post_author = $_SESSION['userid'];
            addPost($app, $cleaned_parameters, $post_author);

            $url = $this->router->pathFor('');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }

    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('createpost');

/**
 * Edits a user's existing details in the database by calling the relevant method in the DetailModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 * @param $hashed_password
 */
function addPost($app, $cleaned_parameters, $post_author)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_subject_name = $cleaned_parameters['subname'];
	$cleaned_post_subject = $cleaned_parameters['catid'];
	$cleaned_post_author = $post_author;
	$cleaned_post_message = $cleaned_parameters['message']
 

    $verification = $model->addPost($cleaned_subject_name, $cleaned_post_subject, $cleaned_post_author);

    if($verification == true)
    {
	$postid = $model->getLatestPost();
	$verification2 = $model->addReply($cleaned_post_message, $postid,$cleaned_post_subject, $cleaned_post_author);
	if($verification2 == true)
    {
        echo '<div style="text-align: center;">Your Topic has been created!</div>';
    }
    else
    {
        echo 'there was an issue adding a reply to your topic';
    }
    }
    else
    {
        echo 'there was an issue creating the new topic';
    }
}