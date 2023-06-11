<?php
function phoneNumber_check($phone) {
    $phone = str_replace(" ", "", $phone);
    $phoneRegex = [
        "/^\+?7\([0-9]{3}\)[0-9]{3}\-?[0-9]{2}\-?[0-9]{2}$/",
        "/^\+?7\-?[0-9]{3}\-?[0-9]{3}\-?[0-9]{2}\-?[0-9]{2}$/"
    ];
    foreach ($phoneRegex as $reg) {
        if (preg_match($reg, $phone)) {
            return null;
        }
    }
    return [BAD_PHONE];
}