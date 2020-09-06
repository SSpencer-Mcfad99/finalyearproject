<?php
/**
 * Builds up all references to every class under the src folder as well as TWIG using composer. Composer then
 * fetches these class based upon name spaces.
 */

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig'],
        [
            'debug' => true
        ]
    );

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['validator'] = function ($container) {
    $validator = new \votingSystemTutorial\Validator();
    return $validator;
};

$container['databaseWrapper'] = function ($container) {
    $database_wrapper = new \votingSystemTutorial\DatabaseWrapper();
    return $database_wrapper;
};

$container['sqlQueries'] = function ($container) {
    $sql_queries = new \votingSystemTutorial\SQLQueries();
    return $sql_queries;
};

$container['processOutput'] = function ($container) {
    $output_processor = new \votingSystemTutorial\ProcessOutput();
    return $output_processor;
};

$container['loginModel'] = function ($container) {
    $loginModel = new \votingSystemTutorial\LoginModel();
    return $loginModel;
};

$container['registrationModel'] = function ($container) {
    $regModel = new \votingSystemTutorial\RegistrationModel();
    return $regModel;
};

$container['bcryptWrapper'] = function ($container) {
    $bcryptWrapper = new \votingSystemTutorial\BcryptWrapper();
    return $bcryptWrapper;
};

$container['monologWrapper'] = function ($container) {
    $monologWrapper = new \votingSystemTutorial\MonologWrapper();
    return $monologWrapper;
};

$container['detailModel'] = function ($container) {
    $detailModel = new \votingSystemTutorial\DetailModel();
    return $detailModel;
};

$container['questionModel'] = function ($container) {
    $questionModel = new \votingSystemTutorial\QuestionModel();
    return $questionModel;
};

$container['quizModel'] = function ($container) {
    $quizModel = new \votingSystemTutorial\QuizModel();
    return $quizModel;
};

$container['roleModel'] = function ($container) {
    $roleModel = new \votingSystemTutorial\RoleModel();
    return $roleModel;
};

$container['votingSystemModel'] = function ($container) {
    $votingSystemModel = new \votingSystemTutorial\VotingSystemModel();
    return $votingSystemModel;
};

$container['forumModel'] = function ($container) {
    $forumModel = new \votingSystemTutorial\ForumModel();
    return $forumModel;
};

$container['glossaryModel'] = function ($container) {
    $glossaryModel = new \votingSystemTutorial\GlossaryModel();
    return $glossaryModel;
};