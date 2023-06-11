<?php
include_once __DIR__ . "../../../helpers/date8601.php";
function route($req)
{
    global $Link;
    $user_id = get_user_id($req["jwt"]);

    $orders = $Link->query(
        "SELECT
        deliveryTime,
        orderTime,
        status,
        price,
        id
        FROM `order`
        WHERE user='$user_id'"
    );

    $orders_array = [];
    while ($cur_order = $orders->fetch_assoc()) {
        $cur_order["price"] = floatval($cur_order["price"]);
        $cur_order["deliveryTime"] = dtime_to_iso_8601($cur_order["deliveryTime"]);
        $cur_order["orderTime"] = dtime_to_iso_8601($cur_order["orderTime"]);
        $orders_array[] = $cur_order;
    }
    setHTTPStatus(200);
    echo json_encode($orders_array);
}

function get_details()
{
    return [
        "params_model" => new stdClass(),
        "body_model" => new stdClass(),
        "authorization" => true
    ];
}