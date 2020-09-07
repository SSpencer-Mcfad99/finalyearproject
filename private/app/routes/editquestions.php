<?php

/**
 *  editquestions.php - Fetches form editquestion.html.twig and outputs stored variables to it.
 * It then shows the twig file on the screen.
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/ediquestion', function(Request $request, Response $response) use ($app){

    session_start();
	 if (isset($_SESSION['userid']))
     {
       if($_SESSION['userrole'] == 1)
       {
         $userrole = $_SESSION['userrole'];
         $quizzes = getQuizzesFromDatabase($app);
	     $id = $_REQUEST['questionid'];
	     $_SESSION['questionid'] = $id;

	     $question = getQuestion($app, $id);
         $html_output = $this->view->render($response,
          'editquestion.html.twig',
          [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'method' => 'post',
            'action' => 'editquestion',
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_text' => 'Edit question details',
			'question' => $question,
            'quizzes' => $quizzes,
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

})->setName('ediquestion');

/** Fetches specified question from database using the relevant method from the QuestionModel.
 *
 * @param $app
 * @param $id
 * @return mixed
 */
function  getQuestion($app, $id) {
    $model = $app->getContainer()->get('questionModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $question = $model->getQuestion($id);

    return $question;
}

/** Fetches all quizzes from the database using relevant method from QuizModel.
 *
 * @param $app
 * @return mixed
 */
function getQuizzesFromDatabase($app){
    $model = $app->getContainer()->get('quizModel');
    $model->setDatabaseWrapper($app->getContainer()->get('databaseWrapper'));
    $model->setSqlQueries($app->getContainer()->get('sqlQueries'));
    $model->setDatabaseConnectionSettings($app->getContainer()->get('settings')['pdo_settings']);

    $quizzes = $model->retrieveQuizzesFromDB();

    return $quizzes;
}
