<?php
function cat_clause($param)
{
    $cat_clause = "";
    switch (is_null($param)) {
        case true:
            break;
        default:
            $cats = $param;
            foreach ($cats as $key => $value) {
                $cats[$key] = "'$value'";
            }
            $cat_clause = " AND category IN (" . implode(", ", $cats) . ")";
            break;
    }
    return $cat_clause;
}