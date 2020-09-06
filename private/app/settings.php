<?php

/**
 * The settings for the web application.
 */

ini_set('display_errors', 'On');
ini_set('html_errors', 'On');

define('DIRSEP', DIRECTORY_SEPARATOR);

$app_url = dirname($_SERVER['SCRIPT_NAME']);
$css_path = $app_url . '/css/style.css';
$js_path = $app_url . '/js/password.js';

define('CSS_PATH', $css_path);
define('JS_PATH', $js_path);
define('APP_NAME', 'Voting Systems Tutorial');
define('LANDING_PAGE', $_SERVER['SCRIPT_NAME']);

define ('LOG_FILE_NAME', 'votingSystems.log');
define ('LOG_FILE_LOCATION', '../logs/');

$settings = [
    "settings" => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true,
        'class_path' => __DIR__ . '/src/',
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'auto_reload' => true,
            ]],
        'pdo_settings' => [
            'rdbms' => 'mysql',
            'host' => 'localhost',
            'db_name' => 'votesys',
            'port' => '3306',
            'user_name' => 'votesys_user',
            'user_password' => 'votesys_user_pass',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ]],
    ],
];

return $settings;
