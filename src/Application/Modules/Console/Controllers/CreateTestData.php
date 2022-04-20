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

        // Load api event data
        $filename = 'api_event_log.json';
        $fileContents = file_get_contents(APP_MODULES_PATH . '/Console/Files/' . $filename);
        if (empty($fileContents)) {
            throw new ErrorMessage("File {$filename} is empty");
        }

        // Mark time
        Timers::markTime("Loaded file {$filename}");

        $dataToInsert = [];
        $fileContents = explode("\n", $fileContents);
        foreach ($fileContents as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            $dataToInsert[] = json_decode($line, true);
        }
        $apiStats->statisticsDb->api_event_log->insertMany($dataToInsert);

        // Mark time
        Timers::markTime('Inserted api_event_log data');

        // Load api time data
        $filename = 'api_time_log.json';
        $fileContents = file_get_contents(APP_MODULES_PATH . '/Console/Files/' . $filename);
        if (empty($fileContents)) {
            throw new ErrorMessage("File {$filename} is empty");
        }

        // Mark time
        Timers::markTime("Loaded file {$filename}");

        $dataToInsert = [];
        $fileContents = explode("\n", $fileContents);
        foreach ($fileContents as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            $dataToInsert[] = json_decode($line, true);
        }
        $apiStats->statisticsDb->api_time_log->insertMany($dataToInsert);

        // Mark time
        Timers::markTime('Inserted api_time_log data');

        echo Logger::debugOutput();
    }
}
