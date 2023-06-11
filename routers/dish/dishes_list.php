<?php
include_once "helpers\dish_helpers\dish_sorting.php";
include_once "helpers\dish_helpers\dish_cat.php";
include_once "helpers\dish_helpers\dish_veg.php";
include_once "helpers\dish_helpers\dish_page.php";
include_once "helpers\dish_helpers\dish_array.php";
function route($req)
{
    global $Link;

    $request_string = "SELECT dish.*, get_rating(id) as rating FROM dish ";
    $pagination_string = "SELECT COUNT(*) as count FROM dish ";
    $order_clause = sort_clause($req["parameters"]["sorting"]);
    $cat_clause = cat_clause($req["parameters"]["categories"]);
    $veg_clause = veg_clause($req["parameters"]["vegetarian"]);
    $page_clause = page_clause($req["parameters"]["page"]);

    $pagination_string .= "WHERE " . $veg_clause . $cat_clause;
    $request_string .= "WHERE " . $veg_clause . $cat_clause . $order_clause . $page_clause;

    $dishes_count = $Link->query($pagination_string)->fetch_assoc();
    $pages_count = ceil($dishes_count["count"] / PAGE_SIZE);

    if ($pages_count < intval($req["parameters"]["page"])) {
        setHTTPStatus(400, "Invalid value for attribute page");
        exit;
    }

    $dishes = $Link->query($request_string);

    $dishes_array = [];
    while ($cur_dish = $dishes->fetch_assoc()) {
        $dishes_array[] = $cur_dish;
    }

    $dishes_array = handle_dish_array($dishes_array);

    setHTTPStatus(200);
    echo json_encode([
        "dishes" => $dishes_array,
        "pagination" => [
            "size" => PAGE_SIZE,
            "count" => $pages_count,
            "current" => intval($req["parameters"]["page"])
        ]
    ]);
}

function get_details()
{
    $cat = "/^(Wok|Pizza|Soup|Dessert|Drink)$/";
    $sort = "/^(Name|Price|Rating)(Asc|Desc)$/";
    $params_model = [
        "categories" => [
            "type" => "string", 
            "regex" => $cat, 
            "default" => null, 
            "many" => true
        ],
        "sorting" => [
            "type" => "string", 
            "regex" => $sort, 
            "default" => "NameAsc", 
            "many" => false
        ],
        "page" => [
            "type" => "int_N",
            "default" => "1",
            "many" => false
        ],
        "vegetarian" => [
            "type" => "bool", 
            "default" => "false", 
            "many" => false
        ]
    ];

    return [
        "params_model" => $params_model,
        "body_model" => new stdClass(),
        "authorization" => false
    ];
}