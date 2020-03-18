<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 12:58
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
    $validator = new \VotingSystemsTutorial\Validator();
    return $validator;
};

$container['databaseWrapper'] = function ($container) {
    $database_wrapper = new \VotingSystemsTutorial\DatabaseWrapper();
    return $database_wrapper;
};

$container['sqlQueries'] = function ($container) {
    $sql_queries = new \VotingSystemsTutorial\SQLQueries();
    return $sql_queries;
};

$container['processOutput'] = function ($container) {
    $output_processor = new \VotingSystemsTutorial\ProcessOutput();
    return $output_processor;
};

$container['xmlParser'] = function ($container) {
    $parser = new \VotingSystemsTutorial\XmlParser();
    return $parser;
};


$container['loginModel'] = function ($container) {
    $loginModel = new \VotingSystemsTutorial\LoginModel();
    return $loginModel;
};

$container['registrationModel'] = function ($container) {
    $regModel = new \VotingSystemsTutorial\RegistrationModel();
    return $regModel;
};

$container['bcryptWrapper'] = function ($container) {
    $bcryptWrapper = new \VotingSystemsTutorial\BcryptWrapper();
    return $bcryptWrapper;
};

$container['monologWrapper'] = function ($container) {
    $bcryptWrapper = new \VotingSystemsTutorial\MonologWrapper();
    return $bcryptWrapper;
};

$container['detailModel'] = function ($container) {
    $detailModel = new \VotingSystemsTutorial\DetailModel();
    return $detailModel;
};

$container['questionModel'] = function ($container) {
    $questionModel = new \VotingSystemsTutorial\QuestionModel();
    return $questionModel;
};

$container['quizModel'] = function ($container) {
    $quizModel = new \VotingSystemsTutorial\QuizModel();
    return $quizModel;
};

$container['roleModel'] = function ($container) {
    $roleModel = new \VotingSystemsTutorial\RoleModel();
    return $roleModel;
};

$container['sessionModel'] = function ($container) {
    $sessionModel = new \VotingSystemsTutorial\SessionModel();
    return $sessionModel;
};

$container['votingSystemModel'] = function ($container) {
    $votingSystemModel = new \VotingSystemsTutorial\VotingSystemModel();
    return $votingSystemModel;
};

$container['forumModel'] = function ($container) {
    $roleModel = new \VotingSystemsTutorial\ForumModel();
    return $forumModel;
};