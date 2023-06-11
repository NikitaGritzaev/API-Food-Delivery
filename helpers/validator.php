<?php

function validate_length_string($string = "", $min = 0, $max = 0) {
    $length = mb_strlen($string);
    if ($length < $min && $min >= 0) return -1;
    if ($length > $max && $max >= 0) return 1;
    return 0;
}
