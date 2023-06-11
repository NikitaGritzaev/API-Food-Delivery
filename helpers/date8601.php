<?php
function check_iso8601($date) {
    $regex_dates = [
        "/^\d{4}-\d{2}-\d{2}$/",
        "/^\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}Z?$/",
        "/^\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}Z?$/",
        "/^\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}\.\d{3}Z?$/",
        "/^\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}Z?$/"
    ];
    if (!is_string($date)) return false;

    $matches = false;
    foreach ($regex_dates as $regex) {
        if (preg_match($regex, $date)) {
            $matches = true;
            break;
        }
    }
    if (!$matches) return false;

    try {
        new DateTime($date);
        return true;
    } catch(Exception $e) {
        return false;
    }
}

function dtime_to_iso_8601($date) {
    if (is_null($date)) return null;
    if ($date[10] == " ") $date[10] = "T";
    $date .= "Z";
    return $date;
}