<?php
function route($req)
{
    global $Link;

    $user_id = get_user_id($req["jwt"]);

    $basket = $Link->query("SELECT * FROM basket where user='$user_id'");
    if ($basket->num_rows === 0) {
        setHTTPStatus(400, "Empty basket for user with id=$user_id");
        exit;
    }

    $order_id = guuid();
    $deliveryTime = date_format(new DateTime($req["body"]->deliveryTime), "Y-m-d H:i:s.v");
    $address = $req["body"]->address;
    $price = $Link->query("SELECT SUM(price*amount) as S
                           FROM basket
                           JOIN dish ON user='$user_id'
                           AND basket.dish = dish.id")->fetch_assoc();
    $price = floatval($price["S"]);
    $Link->query(
        "INSERT INTO `order`(id, user, deliveryTime, orderTime, status, price, address)
        VALUES
        ('$order_id',
        '$user_id',
        '$deliveryTime',
        CONVERT_TZ(NOW(),'SYSTEM','Asia/Novosibirsk'),
        'InProcess',
        $price,
        '$address')"
    );
    $Link->query("UPDATE basket SET user=NULL, `order`='$order_id' WHERE user='$user_id'");

    setHTTPStatus(200);
}

function get_details()
{
    $body = [
        "address" => [
            "required" => true,
            "handler" => "helpers\user_helpers\address.php",
            "type" => "string"
        ],
        "deliveryTime" => [
            "required" => true, 
            "handler" => "helpers\order_helpers\deliveryTime.php", 
            "type" => "string"
        ]
    ];
    return [
        "params_model" => new stdClass(),
        "body_model" => $body,
        "authorization" => true
    ];
}