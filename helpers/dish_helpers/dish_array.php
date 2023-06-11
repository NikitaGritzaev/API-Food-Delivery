<?php
function handle_dish_array($dishes_array)
{
    foreach ($dishes_array as $key => $value) {
        if ($dishes_array[$key]["vegetarian"] == "0") {
            $dishes_array[$key]["vegetarian"] = false;
        }
        else {
            $dishes_array[$key]["vegetarian"] = true;
        }
        $dishes_array[$key]["price"] = floatval($dishes_array[$key]["price"]);
    }
    return $dishes_array;
}