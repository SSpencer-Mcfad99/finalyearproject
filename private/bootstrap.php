<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 12:55
 */

/** A file that is used to separte the public and private file structure, this reduces risk of exposure to a third party.
 *   This also 'builds' the application using the: autoload.php file from the vendor library, settings.php,
 *   dependencies.php and routes.php. These then instantiate the Slim app object.
 */

require 'vendor/autoload.php';

$settings = require __DIR__ . '/app/settings.php';

$container = new \Slim\Container($settings);

require __DIR__ . '/app/dependencies.php';

$app = new \Slim\App($container);

require __DIR__ . '/app/routes.php';

$app->run();