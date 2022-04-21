<?php

use System\Modules\Core\Models\Config;
use System\Modules\Presentation\Models\Menu\Menu;
use Defaults\Data\MainMenu;
use System\Modules\Core\Models\Load;
use System\Modules\Utils\Models\Sessions\SessionsMongoDb;

// Send content type and charset header
header('Content-type: text/html; charset=utf-8');

// Set locales
// setlocale(LC_TIME, 'lv_LV.utf8', 'lv_LV.UTF-8');
// setlocale(LC_NUMERIC, 'lv_LV.utf8', 'lv_LV.UTF-8');
// setlocale(LC_CTYPE, 'lv_LV.utf8', 'lv_LV.UTF-8');
// date_default_timezone_set('Europe/Riga');

// Start mongoDB connection
$client = new \MongoDB\Client(Config::$items['db']['mongo']['default']['string']);
Config::$items['mdb_conn'] = $client;
Config::$items['mdb_db'] = $client->{Config::$items['db']['mongo']['default']['dbname']};

// Init session
$sessions = new SessionsMongoDb(
    Config::$items['db']['mongo']['default']['string'],
    Config::$items['db']['mongo']['default']['dbname']
);
$sessions->register();
$sessions->start();

// register twig functions
Menu::registerTwig();

// Default menu
Menu::registerMenu(new MainMenu());

// Make sure we don't have anything weird here sent from the browser
if (!empty($_COOKIE['color_theme'])) {
    if (!in_array($_COOKIE['color_theme'], Config::$items['color_themes'])) {
        $_COOKIE['color_theme'] = 'default';
    }
}
