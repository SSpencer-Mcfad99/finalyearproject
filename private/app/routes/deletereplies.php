<?php

/**
 *  deletereplies.php - Fetches form deletereply.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/delreplies', function(Request $request, Response $response) use ($app){

    session_start();
    if (isset($_SESSION['userid']))
    {
       if($_SESSION['userrole'] == 1){
         $userrole = $_SESSION['userrole'];
	     $id = $_REQUEST['id'];

	     $replies = getReplies($app, $id);
         $html_output = $this->view->render($response,
          'deletereply.html.twig',
          [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => "deletereply?id=$id",
            'page_title' => APP_NAME,   
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Choose reply to delete',
			'userrole' => $userrole,
			'replies' => $replies
          ]);

         $processed_output = processOutput($app, $html_output);
         return $processed_output;
       }
       else
       {
         $url = $this->router->pathFor('home');
         return $response->withStatus(302)->withHeader('Location', $url);
       }
	}
	 else
    {
        $_SESSION['error'] = 'Invalid access.  Please Login first.';
        $url = $this->router->pathFor('login');
        return $response->withStatus(302)->withHeader('Location', $url);
    }

})->setName('delreplies');

/** Gets all replies within a post using relevant method in ForumModel.
 *
 * @param $app
 * @param $id
 * @return mixed
 */
function getReplies($app, $id)
{
    $model = $app->getContainer()->get('forumModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $replies = $model->retrieveRepliesFromDB($id);

    return $replies;
}
