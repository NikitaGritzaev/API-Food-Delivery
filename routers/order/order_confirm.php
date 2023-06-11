<?php
function route($req)
{
    global $Link;

    $user_id = get_user_id($req["jwt"]);
    $order_id = $req["steps"][2];
    $order = $Link->query(
        "SELECT user, status FROM `order`
        WHERE id='$order_id'"
    )->fetch_assoc();

    if (is_null($order)) {
        setHTTPSTatus(404, "Order with id=$order_id don't in database");
        exit;
    }

    if ($order["user"] != $user_id) {
        setHTTPSTatus(403, "Order with id=$order_id belongs to another user!");
        exit;
    }

    if ($order["status"] != "InProcess") {
        setHTTPSTatus(400, "Can't update status for order with id=$order_id");
        exit;
    }

    $Link->query(
        "INSERT IGNORE INTO user_dish(user, dish)
        SELECT '$user_id', dish FROM basket
        WHERE `order`='$order_id'"
    );
    $Link->query(
        "UPDATE `order` SET status='Delivered'
        WHERE id='$order_id' AND user='$user_id'"
    );
    setHTTPStatus(200);

}

function get_details()
{
    return [
        "params_model" => new stdClass(),
        "body_model" => new stdClass(),
        "authorization" => true
    ];
}