<?php
include_once "helpers\dish_helpers\dish_checker.php";
function route($req) {
    global $Link;

    $dish_id = $req["steps"][2];
    $dish = check_dish($dish_id);

    $rating = $Link->query("SELECT get_rating('$dish_id')")->fetch_assoc();
    setHTTPStatus(200);
    echo json_encode([
        "id" => $dish["id"],
        "name" => $dish["name"],
        "description" => $dish["description"],
        "price" => floatval($dish["price"]),
        "image" => $dish["image"],
        "vegetarian" => boolval($dish["vegetarian"]),
        "rating" => is_null($rating["rating"]) ? null : floatval($rating["rating"]),
        "category" => $dish["category"]
    ]);
}

function get_details() {
    return [
        "params_model" => new stdClass(),
        "body_model" => new stdClass(),
        "authorization" => false
    ];
}