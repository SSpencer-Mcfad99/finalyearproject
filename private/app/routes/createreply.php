<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 10/03/2020
 * Time: 12:57
 */
 
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/createreply', function(Request $request, Response $response) use ($app){

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
		    $topic_id = $_REQUEST['postid'];
            addReply($app, $cleaned_parameters, $topic_id);

            $url = $this->router->pathFor('viewposts.php?id=" . $topic_id');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    }

    else
    {
        echo 'Sorry, there was an issue with your entered values';
        return;
    }

})->setName('createreply');

/**
 * Edits a user's existing details in the database by calling the relevant method in the DetailModel, which deals with executing the Database Update query
 *
 * @param $app
 * @param $cleaned_parameters
 */
function addReply($app, $cleaned_parameters, $topic_id)
{
    $settings = $app->getContainer()->get('settings');

    $model = $app->getContainer()->get('forumModel');
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setDatabaseConnectionSettings($settings['pdo_settings']);

    $cleaned_reply_content = $cleaned_parameters['reply'];
	$cleaned_reply_topic = $topic_id;
	$cleaned_reply_author = $_SESSION['userid'];
 

    $verification = $model->addReply($cleaned_reply_content, $cleaned_reply_topic, $cleaned_reply_author);

    if($verification == true)
    {
        echo '<div style="text-align: center;">Your reply has been added</div>';
    }
    else
    {
        echo 'there was an issue creating the new reply';
    }
}