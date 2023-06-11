<?php
include_once __DIR__ . "../../date8601.php";
function deliveryTime_check($date) {
    if (!check_iso8601($date)) return [DATE_BAD_FORMAT];
    $date = date_timestamp_get(new DateTime($date));
    $current = time();
    if ($date < $current) return [DELIVERY_PAST];
    if ($date - $current < DELIVERY_MIN) return [DELIVERY_TOO_EARLY];
    if ($date - $current > DELIVERY_MAX) return [DELIVERY_TOO_LATE];
    
    return null;
}