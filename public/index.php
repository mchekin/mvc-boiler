<?php

use App\CommentsSystem\Entity\CommentRepository;
use Mchekin\MVCBoiler\Container;
use Mchekin\MVCBoiler\Database\DatabaseHandlerFactory;
use Mchekin\MVCBoiler\Database\MySQL;
use Mchekin\MVCBoiler\Http\Request;
use Mchekin\MVCBoiler\Http\Response;
use Mchekin\MVCBoiler\Http\Router;
use Mchekin\MVCBoiler\Views\View;

define('ROOT_DIRECTORY', __DIR__.'/..' );

require_once ROOT_DIRECTORY.'/vendor/autoload.php';

try {
    // register mysql database handler (with database configuration data from json file)
    $dbFactory = new DatabaseHandlerFactory([
        'mysql' => MySQL::class,
    ], json_decode(file_get_contents(ROOT_DIRECTORY.'/config/database.json'), true));

    // get mysql database handler
    $dbHandler = $dbFactory->resolve('mysql');

    // assembling the application container with it's components
    $container = new Container([
        'commentRepo' =>new CommentRepository($dbHandler, 'comments'), // initialize Comments repo
        'request' => new Request(),
        'response' => new Response(),
        'view' => new View(ROOT_DIRECTORY.'/resources/views'), // initialize View component
        'router' => new Router(json_decode(file_get_contents(ROOT_DIRECTORY.'/config/routes.json'), true)),
    ], ROOT_DIRECTORY);

    // running the application
    $container->run();

} catch (Exception $e) {
    echo ('Server error: '.$e->getMessage());
}


