<?php
function veg_clause($param)
{
    $veg_clause = "";
    switch ($param) {
        case "true":
            $veg_clause = "vegetarian=1";
            break;
        case "false":
            $veg_clause = "vegetarian IN (0, 1)";
            break;
    }
    return $veg_clause;
}