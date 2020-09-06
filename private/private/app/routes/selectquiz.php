<?php

/**
 *  selectquiz.php - Fetches form selectquiz.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/equiz', function(Request $request, Response $response) use ($app){

    session_start();
    if (isset($_SESSION['userid']))
    {
        if($_SESSION['userrole'] == 1)
        {
          $userrole = $_SESSION['userrole'];
          $quizzes = selectQuizzesFromDB($app);

          $html_output = $this->view->render($response,
            'selectquiz.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'action' => 'edquiz',
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Select quiz which you wish to edit',
                'userrole' => $userrole,
                'quizzes' => $quizzes
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

})->setName('equiz');

/** Fetches all quizzes from the database using relevant method in QuizModel.
 *
 * @param $app
 * @return mixed
 */
function selectQuizzesFromDB($app)
{
    $model = $app->getContainer()->get('quizModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $quizzes = $model->retrieveQuizzesFromDB();

    return $quizzes;
}
