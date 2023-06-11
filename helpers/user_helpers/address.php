<?php
include_once __DIR__ . "../../validator.php";
function address_check($addr) {
    $res = validate_length_string($addr, 6, 100);
    if ($res == -1) return [ADDRESS_SHORT];
    if ($res == 1) return [ADDRESS_LONG];
    return null;
}