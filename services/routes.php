<?php
function routes_transform()
{
    global $Routes;
    $id_reg = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}";
    foreach ($Routes as $key => $value) {
        $value["pattern"] = preg_replace("/\//", "\/", $value["pattern"]);
        $value["pattern"] = preg_replace("/\{id\}/", $id_reg, $value["pattern"]);
        $value["pattern"] = "/^" . $value["pattern"] . "$/";
        $Routes[$key]["pattern"] = $value["pattern"];
    }
    return $Routes;
}

global $Routes;
$Routes = [
    [
        "pattern" => "api/account/register",
        "method" => "POST",
        "handler" => "/users/register.php"
    ],
    [
        "pattern" => "api/account/login",
        "method" => "POST",
        "handler" => "/users/login.php"
    ],
    [
        "pattern" => "api/account/logout", 
        "method" => "POST", 
        "handler" => "/users/logout.php"],
    [
        "pattern" => "api/account/profile",
        "method" => "GET",
        "handler" => "/users/profile_get.php"],
    [
        "pattern" => "api/account/profile",
        "method" => "PUT",
        "handler" => "/users/profile_put.php"
    ],
    [
        "pattern" => "api/dish",
        "method" => "GET",
        "handler" => "/dish/dishes_list.php"
    ],
    [
        "pattern" => "api/dish/{id}",
        "method" => "GET",
        "handler" => "/dish/dish_info.php"
    ],
    [
        "pattern" => "api/dish/{id}/rating/check", 
        "method" => "GET", 
        "handler" => "/dish/dish_check_rating.php"
    ],
    [
        "pattern" => "api/dish/{id}/rating", 
        "method" => "POST", 
        "handler" => "/dish/dish_set_rating.php"
    ],
    [
        "pattern" => "api/basket", 
        "method" => "GET", 
        "handler" => "/basket/basket.php"
    ],
    [
        "pattern" => "api/basket/dish/{id}", 
        "method" => "POST", 
        "handler" => "/basket/basket_add.php"
    ],
    [
        "pattern" => "api/basket/dish/{id}",
        "method" => "DELETE",
        "handler" => "/basket/basket_edit.php"
    ],
    [
        "pattern" => "api/order/{id}",
        "method" => "GET",
        "handler" => "/order/order_info.php"],
    [
        "pattern" => "api/order",
        "method" => "GET",
        "handler" => "/order/order_list.php"
    ],
    [
        "pattern" => "api/order",
        "method" => "POST",
        "handler" => "/order/order_create.php"
    ],
    [
        "pattern" => "api/order/{id}/status",
        "method" => "POST",
        "handler" => "/order/order_confirm.php"
    ]
];

$routes = routes_transform();