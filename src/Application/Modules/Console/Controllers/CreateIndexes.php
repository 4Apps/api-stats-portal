<?php

namespace Console\Controllers;

use System\Modules\Core\Models\Config;
use System\Modules\Core\Models\Timers;
use System\Modules\Core\Exceptions\ErrorMessage;
use FourApps\ApiStats as ApiStatsModel;
use System\Modules\Core\Models\Logger;

class CreateIndexes
{
    /**
     *  Stats view
     */
    public static function __callStatic($name, $arguments)
    {
        // Mark time
        Timers::markTime('Start controller method');

        // Init connection to mongodb
        $apiStats = new ApiStatsModel(Config::$items['db']['mongo']['default']);

        // Mark time
        Timers::markTime('Connected to mongodb');

        $apiStats->statisticsDb->api_event_log->createIndex(['day_time' => 1]);
        $apiStats->statisticsDb->api_event_log->createIndex(['start_time' => 1, 'count' => -1]);

        $apiStats->statisticsDb->api_time_log->createIndex(['start_time' => 1, 'count' => -1]);
        $apiStats->statisticsDb->api_time_log->createIndex(['start_time' => 1, 'api_name' => 1, 'type' => 1]);

        // Mark time
        Timers::markTime('Indexes created');

        echo Logger::debugOutput();
    }
}
