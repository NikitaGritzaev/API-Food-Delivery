<?php
include_once "helpers\dish_helpers\dish_checker.php";
function route($req) {
    global $Link;

    $user_id = get_user_id($req["jwt"]);
    $dish_id = $req["steps"][3];

    check_dish($dish_id);

    $check = $Link->query(
        "SELECT 1 FROM basket
        WHERE user='$user_id' AND dish='$dish_id'"
    );
    
    if ($check->num_rows === 0) {
        $id = guuid();
        $Link->query(
            "INSERT INTO basket(id, dish, amount, user)
            VALUES ('$id', '$dish_id', 1, '$user_id')"
        );
    }
    else {
        $Link->query(
            "UPDATE basket SET amount=amount+1
            WHERE user='$user_id' AND dish='$dish_id'"
        );
    }
    setHTTPStatus(200);
}

function get_details() {
    return [
        "params_model" => new stdClass(),
        "body_model" => new stdClass(),
        "authorization" => true
    ];
}