<?php
$secrets = json_decode(file_get_contents(__DIR__ . '/secrets.json'));
$db_config = $secrets->db;

// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['db'] = new PDO(
	    "mysql:host=$db_config->host;dbname=$db_config->dbname",
	    "$db_config->user",
	    "$db_config->pass"
    	);

spl_autoload_register(function ($class_name) {
    require 'classes/class-'. $class_name . '.php';
});

// Create necessary tables

new createTables('images', "
    CREATE TABLE images(
                id mediumint NOT NULL AUTO_INCREMENT,
                url VARCHAR(2083) NOT NULL,
                name text NOT NULL,
                PRIMARY KEY (id)
            );
    ");

new createTables('tags', "
    CREATE TABLE tags(
                id mediumint NOT NULL AUTO_INCREMENT,
                name text NOT NULL,
                PRIMARY KEY (id)
            );
    ");

new createTables('tag_relationships', "
    CREATE TABLE tag_relationships(
                tag_id mediumint NOT NULL,
                image_id mediumint NOT NULL
            );
    ");
