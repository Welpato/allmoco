<?php

namespace App\Entity;

use App\Controller\IndexController;
use App\Controller\RestaurantController;
use App\Controller\SurveyController;
use App\Controller\UserController;
use App\Controller\VoteController;

/**
 * Class Menu
 */
class Menu
{
    /**
     * Simple array constant used for generate the application menu
     */
    public const MENU_LIST = [
        [
            'route' => IndexController::INDEX_ROUTE,
            'name' => 'List of results',
        ],
        [
            'route' => VoteController::MAIN_ROUTE,
            'name' => VoteController::PAGE_TITLE,
        ],
        [
            'route' => SurveyController::MAIN_ROUTE,
            'name' => SurveyController::PAGE_TITLE,
        ],
        [
            'route' => RestaurantController::MAIN_ROUTE,
            'name' => RestaurantController::PAGE_TITLE,
        ],
        /*[
            'route' => UserController::MAIN_ROUTE,
            'name' => UserController::PAGE_TITLE,
        ],*/
    ];
}
