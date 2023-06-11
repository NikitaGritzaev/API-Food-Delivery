<?php
include_once __DIR__ . "../../validator.php";
function password_check($pass) {
    $errors = [];
    $res = validate_length_string($pass, 8, 30);
    if ($res == -1) $errors[] = PASSWORD_SHORT;
    if ($res == 1) $errors[] = PASSWORD_LONG;
    if (!preg_match("/[0-9]/", $pass)) $errors[] = BAD_PASSWORD_DIGIT;
    if (!preg_match("/[A-ZА-ЯЁ]/u", $pass)) $errors[] = BAD_PASSWORD_UPPERCASE;
    if (!preg_match("/[a-zа-яё]/u", $pass)) $errors[] = BAD_PASSWORD_LOWERCASE;
    if (!preg_match("/[\W_]/", $pass)) $errors[] = BAD_PASSWORD_EXTRA;
    if (preg_match("/\s/", $pass)) $errors[] = BAD_PASSWORD_SPACE;
    return empty($errors) ? null : $errors;
}