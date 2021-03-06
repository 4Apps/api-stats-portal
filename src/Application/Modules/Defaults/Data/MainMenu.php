<?php

namespace Defaults\Data;

use System\Modules\Core\Models\Router;
use System\Modules\Core\Models\Config;
use System\Modules\Presentation\Models\Menu\Menu;
use System\Modules\Presentation\Models\Menu\MenuType;

class MainMenu extends Menu
{
    public function __construct()
    {
        $this->type = MenuType::MAIN_MENU;
        $this->menuList = [
            // 'example' => [
            //     'title' => 'Example',
            //     'url' => '%base_url/defaults/welcome/example',
            //     'show' => function () {
            //         return Config::$items['debug'] == true;
            //     },
            //     'active' => function () {
            //         return Router::$method == 'example';
            //     }
            // ],
            // 'example2' => [
            //     'title' => 'Example 2',
            //     'url' => '%base_url/defaults/welcome/example',
            //     'show' => function () {
            //         return Config::$items['debug'] == true;
            //     },
            //     'active' => function () {
            //         return Router::$method == 'example';
            //     }
            // ],
        ];
    }
}
