<?php
include_once __DIR__ . "../../validator.php";

function fullName_check($name) {
    $errors = [];
    $name = str_replace(" ", "", $name);
    $res = validate_length_string($name, 7, 60);
    if ($res == -1) $errors[] = NAME_SHORT;
    if ($res == 1) $errors[] = NAME_LONG;
    if (!preg_match("/^[a-zA-Zа-яА-Я]*$/u", $name)) $errors[] = BAD_NAME;
    return empty($errors) ? null : $errors;
}