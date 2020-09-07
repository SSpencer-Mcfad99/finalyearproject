<?php

/**
 *  forumhomepage.php - Fetches form forumhomepage.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen. Acts as a hub to get around all the forum pages.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/forum', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid']))
    {
        $userrole = $_SESSION['userrole'];
        $categories = retrieveCatsFromDB($app);
        $posts = getLatestPostsFromDb($app);
        $html_output = $this->view->render($response,
            'forumhomepage.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Forum Home',
				'userrole' => $userrole,
                'categories' => $categories,
                'posts' => $posts,
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

})->setName('forum');

/** Retrieves all categories from the database using relevant method from ForumModel.
 *
 * @param $app
 * @return mixed
 */
function retrieveCatsFromDB($app){
    $model = $app->getContainer()->get('forumModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $categories = $model->retrieveCategoriesFromDB();

    return $categories;
}

/** Gets the latest post for each category from the database using relevant method from ForumModel
 *
 * @param $app
 * @return mixed
 */
function getLatestPostsFromDb($app) {
    $model = $app->getContainer()->get('forumModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $posts = $model->getLatestPosts();

    return $posts;
}
