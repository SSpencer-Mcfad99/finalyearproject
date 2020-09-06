<?php

/**
 *  quizzes.php - Fetches form quizzes.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/quizzes', function (Request $request, Response $response, $args) use ($app) {

    session_start();
    if (isset($_SESSION['userid'])) {
        $userrole = $_SESSION['userrole'];
        $retrieved_quizzes = selectAllQuizzesFromDb($app);

        $html_output = $this->view->render($response,
          'quizzes.html.twig',
          [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'method' => 'post',
            'action' => 'quizlet',
            'userrole' => $userrole,
            'quiz' => $retrieved_quizzes
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
})->setName('quizzes');

/** Retrieves all quizzes from the database using relevant method from QuizModel.
 *
 * @param $app
 * @return mixed
 */
function selectAllQuizzesFromDb($app) {
    $model = $app->getContainer()->get('quizModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $quizzes = $model->retrieveQuizzesFromDB();

    return $quizzes;
}