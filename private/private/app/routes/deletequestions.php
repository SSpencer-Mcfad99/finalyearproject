<?php

/**
 *  deletequestions.php - Fetches form deletequestion.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/delquestion', function(Request $request, Response $response) use ($app){

    session_start();
    if (isset($_SESSION['userid']))
    {
       if($_SESSION['userrole'] == 1)
       {
         $userrole = $_SESSION['userrole'];

	     $questions = getQuestions($app);
         $html_output = $this->view->render($response,
           'deletequestion.html.twig',
           [
             'css_path' => CSS_PATH,
             'landing_page' => LANDING_PAGE,
             'method' => 'post',
             'action' => 'deletequestion',
             'initial_input_box_value' => null,
             'page_title' => APP_NAME,
             'page_heading_1' => APP_NAME,
             'page_heading_2' => 'Choose question to delete',
			 'questions' => $questions,
             'userrole' => $userrole
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

})->setName('delquestion');

/** Gets all questions from the database using the relevant method in the QuestionModel.
 *
 * @param $app
 * @return mixed
 */
function getQuestions($app) {
    $model = $app->getContainer()->get('questionModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $questions = $model->getQuestions();

    return $questions;
}