<?php
include_once __DIR__ . "../../date8601.php";
function birthDate_check($date) {
    if (!check_iso8601($date)) return [DATE_BAD_FORMAT];
    $date = date_timestamp_get(new DateTime($date));
    $current = time();
    if ($date > $current) return [DATE_FUTURE];
    if ($current - $date < YEARS_18) return [DATE_YOUNG];
    if ($date < YEAR_1900) return [DATE_OLD];
    return null;
}