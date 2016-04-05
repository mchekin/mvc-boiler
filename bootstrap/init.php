<?php

use Mchekin\MVCBoiler\Container;
use Mchekin\MVCBoiler\Database\DatabaseHandlerFactory;
use Mchekin\MVCBoiler\Database\MySQL;

define('ROOT_DIRECTORY', __DIR__.'/..');

require_once ROOT_DIRECTORY.'/vendor/autoload.php';

try {

    // register mysql database handler (with database configuration data from json file)
    $dbFactory = new DatabaseHandlerFactory([
        'mysql' => MySQL::class,
    ], json_decode(file_get_contents(ROOT_DIRECTORY.'/config/database.json'), true));

    // resolving the database handler
    $dbHandler = $dbFactory->resolve('mysql');

    // initializing the application container
    $container = new Container([], ROOT_DIRECTORY);

    // migrating the table and it's data
    $container
        ->initTable('comments', $dbHandler, [
            ROOT_DIRECTORY.'/database/schemas/comments.sql',
            ROOT_DIRECTORY.'/database/fixtures/comments.sql',
        ]);

} catch (Exception $e) {
    echo ('Server error: '.$e->getMessage());
}
