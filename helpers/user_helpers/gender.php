<?php
function gender_check($gender) {
    if ($gender !== "Male" && $gender !== "Female") return [BAD_GENDER];
    return null;
}