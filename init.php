<?php

/* 
 * Created by Sachik Sergey
 * box@serginho.ru
 */

/* @var $app \Door\Core\Application */
$app->router->register_controller("bspanel/edit", "/Door/BSPanel/Controller/Edit");
$app->router->register_controller("bspanel/list", "/Door/BSPanel/Controller/ModelsList");
$app->router->register_controller("bspanel/login", "/Door/BSPanel/Controller/Login");
$app->router->register_controller("bspanel/logout", "/Door/BSPanel/Controller/Logout");
$app->router->register_controller("bspanel/panel", "/Door/BSPanel/Controller/Panel");

$app->router->register_wrapper("bspanel/left_menu", "/Door/BSPanel/Wrapper/LeftMenu");

