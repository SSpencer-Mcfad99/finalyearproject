<?php

/**
 *  viewcategory.php - Fetches form viewcategories.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/vcat', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid'])) {
        $userrole = $_SESSION['userrole'];
		$id = $_GET['id'];
		$_SESSION['id'] = $id;
        $retrieved_posts = selectPostsFromDb($app, $id);

        $html_output = $this->view->render($response,
         'viewcategories.html.twig',
         [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'method' => 'post',
            'action' => 'createpost',
            'userrole' => $userrole,
            'posts' => $retrieved_posts,
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
})->setName('vcat');

/** Fetches all posts within a specified category from database using specified method in ForumModel.
 *
 * @param $app
 * @param $id
 * @return mixed
 */
function selectPostsFromDb($app, $id) {
    $model = $app->getContainer()->get('forumModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $posts = $model->retrievePostsFromDB($id);

    return $posts;
}
