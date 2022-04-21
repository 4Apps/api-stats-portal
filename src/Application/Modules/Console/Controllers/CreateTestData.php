<?php

namespace Console\Controllers;

use System\Modules\Core\Models\Config;
use System\Modules\Core\Models\Timers;
use System\Modules\Core\Exceptions\ErrorMessage;
use FourApps\ApiStats as ApiStatsModel;
use System\Modules\Core\Models\Logger;

class CreateTestData
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

        $oneRecord = $apiStats->statisticsDb->api_time_log->findOne();
        if (!empty($oneRecord)) {
            throw new ErrorMessage('Collection is not empty');
        }

        // Mark time
        Timers::markTime('Checked collection');

        // Load api_stats_export.json file
        $filename = 'api_stats_export.json';
        $fileContents = file_get_contents(APP_MODULES_PATH . '/Console/Files/' . $filename);
        if (empty($fileContents)) {
            throw new ErrorMessage("File {$filename} is empty");
        }

        // Mark time
        Timers::markTime("Loaded file {$filename}");

        $exportData = json_decode($fileContents, true);

        // Mark time
        Timers::markTime("Decoded json file {$filename}");

        $apiStats->statisticsDb->api_event_log->insertMany($exportData['api_event_log']);

        // Mark time
        Timers::markTime('Inserted api_event_log data');

        $apiStats->statisticsDb->api_time_log->insertMany($exportData['api_time_log']);

        // Mark time
        Timers::markTime('Inserted api_time_log data');

        echo Logger::debugOutput();
    }
}
