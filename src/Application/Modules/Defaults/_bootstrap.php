<?php

use System\Modules\Core\Models\Config;

if (empty($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Europe/Riga';
}
date_default_timezone_set($_SESSION['timezone']);

Config::$items['view_data']['js_include'] = 'stats';
