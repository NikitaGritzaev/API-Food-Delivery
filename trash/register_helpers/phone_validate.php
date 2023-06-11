<?php
    function get_phone($phone) {
        if (is_null($phone)) return ["string" => $phone, "error" => null, "text" => null];
        $phone = str_replace([" ", "+", "(", ")", "-"], "", $phone);
        if (!preg_match("/7[0-9]{10}/", $phone)) return ["string" => $phone, "error" => null, "text" => null];
        return ["string" => $phone, "error" => DATA_BAD_PATTERN, "text" => BAD_PHONE];
    }