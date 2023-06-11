<?php
function sort_clause($param)
{
    $order_clause = " ORDER BY NAME ASC";
    switch ($param) {
        case "NameAsc":
            $order_clause = " ORDER BY NAME ASC";
            break;
        case "NameDesc":
            $order_clause = " ORDER BY NAME DESC";
            break;
        case "PriceAsc":
            $order_clause = " ORDER BY PRICE ASC";
            break;
        case "PriceDesc":
            $order_clause = " ORDER BY PRICE DESC";
            break;
        case "RatingAsc":
            $order_clause = " ORDER BY RATING ASC";
            break;
        case "RatingDesc":
            $order_clause = " ORDER BY RATING DESC";
            break;
    }
    return $order_clause;
}