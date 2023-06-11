<?php
function check_dish($dish_id) {
    global $Link;
    $dish = $Link->query("SELECT * FROM dish WHERE id='$dish_id'")->fetch_assoc();
    if (is_null($dish)) {
        setHTTPStatus(404, "Dish with this id does not exist");
        exit;
    }
    return $dish;
}