<?php
function route($req)
{
    global $Link;

    $user_id = get_user_id($req["jwt"]);
    $order_id = $req["steps"][2];
    $order = $Link->query("SELECT * FROM `order` where id='$order_id'")->fetch_assoc();
    if (is_null($order)) {
        setHTTPSTatus(404, "Order with id=$order_id don't in database");
        exit;
    }

    if ($order["user"] != $user_id) {
        setHTTPSTatus(403, "Order with id=$order_id belongs to another user!");
        exit;
    }

    unset($order["user"]);
    $order["price"] = floatval($order["price"]);
    $dishes = $Link->query(
        "SELECT dish.id, name, price, amount, price * amount as totalPrice, image
        FROM basket JOIN dish ON basket.dish=dish.id AND `order`='$order_id'"
    );

    $dishes_array = [];
    while ($cur_dish = $dishes->fetch_assoc()) {
        $dishes_array[] = $cur_dish;
    }

    foreach ($dishes_array as $key => $value) {
        $dishes_array[$key]["price"] = floatval($dishes_array[$key]["price"]);
        $dishes_array[$key]["amount"] = floatval($dishes_array[$key]["amount"]);
        $dishes_array[$key]["totalPrice"] = floatval($dishes_array[$key]["totalPrice"]);
    }

    $order["dishes"] = $dishes_array;
    setHTTPStatus(200);
    echo json_encode($order);
}

function get_details()
{
    return [
        "params_model" => new stdClass(),
        "body_model" => new stdClass(),
        "authorization" => true
    ];
}