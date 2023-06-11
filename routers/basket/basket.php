<?php
function route($req)
{
    global $Link;

    $user_id = get_user_id($req["jwt"]);

    $dishes = $Link->query(
        "SELECT
        dish.id, name, price, price*amount as totalPrice, amount, image
        FROM basket JOIN dish ON dish.id = basket.dish
        WHERE user='$user_id'"
    );

    $dishes_array = [];
    while ($cur_dish = $dishes -> fetch_assoc()) {
        $cur_dish["price"] = floatval($cur_dish["price"]);
        $cur_dish["amount"] = intval($cur_dish["amount"]);
        $cur_dish["totalPrice"] = floatval($cur_dish["totalPrice"]);
        $dishes_array[] = $cur_dish;
    }
    setHTTPStatus(200);
    echo json_encode($dishes_array);
}

function get_details()
{
    return [
        "params_model" => new stdClass(),
        "body_model" => new stdClass(),
        "authorization" => true
    ];
}