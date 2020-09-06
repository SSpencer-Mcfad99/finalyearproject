<?php

/**
 *  viewposts.php - Fetches form viewposts.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/vpost', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid'])) {
        $userrole = $_SESSION['userrole'];
		$id = $_GET['id'];
		$_SESSION['postid'] = $id;
        $retrieved_replies = selectRepliesFromDb($app, $id);

        $html_output = $this->view->render($response,
          'viewposts.html.twig',
          [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
			'method' => 'post',
            'action' => 'createreply',
            'userrole' => $userrole,
            'replies' => $retrieved_replies,
            'id' => $id
          ]);
		
         $processed_output = processOutput($app, $html_output);
         return $processed_output;
    }
    else
    {
         $_SESSION['error'] = 'Invalid access.  Please Login first.';
         $url = $this->router->pathFor('login');
         return $response->withStatus(302)->withHeader('Location', $url);
    }
})->setName('vpost');

/** Fetches all replies within a specified post from the database, using a relevant method from ForumModel.
 *
 * @param $app
 * @param $id
 * @return mixed
 */
function  selectRepliesFromDb($app, $id) {
    $model = $app->getContainer()->get('forumModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $replies = $model->retrieveRepliesFromDB($id);

    return $replies;
}