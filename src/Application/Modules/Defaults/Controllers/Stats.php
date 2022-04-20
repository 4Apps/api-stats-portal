<?php

namespace Defaults\Controllers;

use System\Modules\Core\Controllers\Controller;
use System\Modules\Core\Models\Config;
use System\Modules\Core\Models\Timers;
use FourApps\ApiStats as ApiStatsModel;

class Stats extends Controller
{
    /**
     *  Constructor
     */
    public static function construct($class = null, $method = null)
    {
        parent::construct($class, $method);
    }

    /**
     *  Stats view
     */
    public static function index($date = null)
    {
        // Mark time
        Timers::markTime('Run controller method');

        // Init connection to mongodb
        $apiStats = new ApiStatsModel(Config::$items['db']['mongo']['default']);

        if (empty($date)) {
            // Find last day there was any data present
            $cursor = $apiStats->statisticsDb->api_event_log->find([], ['sort' => ['day_time' => -1], 'limit' => 1]);
            $records = $cursor->toArray();
            if (!empty($records)) {
                $date = $records[0]['day_time'];
            } else {
                $date = time();
            }
        } else {
            $date = strtotime($date);
        }

        // Create date range
        $interval_from = strtotime("today", $date);
        $interval_to = $interval_from + 86399;

        // Get time stats
        $filter = [
            '$or' => [
                [
                    'type' => ['$in' => ['day', 'hour', 'minute']],
                    'start_time' => [
                        '$gte' => $interval_from,
                        '$lte' => $interval_to
                    ]
                ],
                [
                    'type' => 'second',
                    'count' => [
                        '$gte' => 5
                    ]
                ]
            ],
        ];
        $options = [
            'sort' => ['start_time' => 1, 'count' => -1],
        ];
        $tmp = $apiStats->statisticsDb->api_time_log->find(
            $filter,
            $options
        );
        $timeStats = [];
        foreach ($tmp as $item) {
            $itemArr = (array)$item;
            if ($itemArr['type'] === 'day') {
                $itemArr['start_time_formatted'] = date('d.m.Y', $itemArr['start_time']);
            } else {
                $itemArr['start_time_formatted'] = date('d.m.Y H:i:s', $itemArr['start_time']);
            }

            if (!isset($timeStats[$itemArr['api_name']])) {
                $timeStats[$itemArr['api_name']] = [];
            }

            $timeStats[$itemArr['api_name']][] = $itemArr;
        }
        $viewData['timeStats'] = $timeStats;

        // Get api calls stats
        $filter = [
            'start_time' => [
                '$gte' => $interval_from,
                '$lte' => $interval_to
            ]
        ];
        $options = [
            'sort' => ['start_time' => 1, 'count' => -1],
        ];
        $tmp = $apiStats->statisticsDb->api_event_log->find(
            $filter,
            $options
        );
        $apiCallStats = [];
        foreach ($tmp as $item) {
            $itemArr = (array)$item;
            $key = "{$itemArr['service']}_{$itemArr['context_name']}_{$itemArr['method_name']}";
            if (!isset($apiCallStats[$itemArr['api_name']][$key])) {
                $itemArr['count'] = 0;
                $apiCallStats[$itemArr['api_name']][$key] = $itemArr;

                // Reset stats
                $apiCallStats[$itemArr['api_name']][$key]['count'] = 0;
                $apiCallStats[$itemArr['api_name']][$key]['retries'] = 0;
                $apiCallStats[$itemArr['api_name']][$key]['retry_seconds'] = 0;
                $apiCallStats[$itemArr['api_name']][$key]['failed'] = 0;
                $apiCallStats[$itemArr['api_name']][$key]['succeeded'] = 0;
                $apiCallStats[$itemArr['api_name']][$key]['duration'] = 0;
            }

            $apiCallStats[$itemArr['api_name']][$key]['count'] += 1;
            $apiCallStats[$itemArr['api_name']][$key]['retries'] += $itemArr['retries'];
            $apiCallStats[$itemArr['api_name']][$key]['retry_seconds'] += $itemArr['retry_seconds'];
            $apiCallStats[$itemArr['api_name']][$key]['failed'] += $itemArr['failed'];
            $apiCallStats[$itemArr['api_name']][$key]['succeeded'] += $itemArr['succeeded'];

            if (empty($itemArr['success_time']) == false) {
                $apiCallStats[$itemArr['api_name']][$key]['duration'] += $itemArr['duration'];
                $apiCallStats[$itemArr['api_name']][$key]['avg_duration'] = round(
                    ($apiCallStats[$itemArr['api_name']][$key]['duration'] / $apiCallStats[$itemArr['api_name']][$key]['succeeded']),
                    5
                );
            }
        }
        $viewData['apiCallStats'] = $apiCallStats;

        // Show the view
        $viewData['date'] = $date;
        $viewData['date_formatted'] = date('d.m.Y', $date);

        // Mark time
        Timers::markTime('Before views');

        self::render(['stats.html'], $viewData);
    }
}
