<?php
function check_phone_format($phone) {
    if (is_null($phone)) return false;
    $phone = str_replace([" ", "+", "(", ")", "-"], "", $phone);
    if (preg_match("/7[0-9]{10}/", $phone)) return ["string" => $phone, "error" => null, "text" => null];
    return ["string" => $phone, "error" => "DATA_BAD_PATTERN", "text" => BAD_PHONE];
}

function check_date_format($date) {
    $dateTime = \DateTime::createFromFormat(\DateTime::ISO8601, $date);

    if ($dateTime) {
        return $dateTime->format(\DateTime::ISO8601) === $date;
    }
    return false;
}