<?php
include_once __DIR__ . "../../validator.php";
function email_check($email) {
    $errors = [];
    $res = validate_length_string($email, 7, 30);
    if ($res == -1) $errors[] = EMAIL_SHORT;
    if ($res == 1) $errors[] = EMAIL_LONG;

    $regex = "/^[a-zA-Z0-9]+@[a-zA-Z0-9]{2,}.[a-zA-Z0-9]{2,}$/";
    if (!preg_match($regex, $email)) $errors[] = BAD_EMAIL;
    return empty($errors) ? null : $errors;
}