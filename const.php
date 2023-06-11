<?php
define("API_NAME", "Delivery.API");
define("DB_ADDRESS", "127.0.0.1");
define("DB_NAME", "food");
define("DB_USER", "food_operator");
define("DB_USER_PASS", "hhoC9]XE/hMeI]1J");

define("DELIVERY_MIN", 10800);
define("DELIVERY_MAX", 259200);
define("YEARS_18", 567648000);
define("YEAR_1900", -2208988800);

define("VAL_ERR", "One or more validation errors occured!");

define("PASSWORD_SHORT", "The password field can't be shorter than 8 characters!");
define("PASSWORD_LONG", "The password field can't be longer than 30 characters!");
define("BAD_PASSWORD_SPACE", "Password should not contain any white space!");
define("BAD_PASSWORD_LOWERCASE", "Password should contain at least one lowercase character!");
define("BAD_PASSWORD_UPPERCASE", "Password should contain at least one uppercase character!");
define("BAD_PASSWORD_DIGIT", "Password should contain at least one digit!");
define("BAD_PASSWORD_EXTRA", "Password should contain at least one extra symbol!");

define("NAME_SHORT", "The name field can't be shorter than 7 characters!");
define("NAME_LONG", "The name field can't be longer than 60 characters!");
define("BAD_NAME", "Name is incorrect!");

define("BAD_GENDER", "Gender should be Male or Female!");

define("BAD_PHONE", "The phoneNumber field is not a valid phone number!");

define("ADDRESS_SHORT", "The address field can't be shorter than 6 characters!");
define("ADDRESS_LONG", "The address field can't be longer than 100 characters!");

define("EMAIL_SHORT", "The email field can't be shorter than 6 characters!");
define("EMAIL_LONG", "The email field can't be longer than 100 characters!");
define("BAD_EMAIL", "Email is incorrect!");

define("DATE_BAD_FORMAT", "Incorrect date format!");
define("DATE_YOUNG", "User should be minimum 18 years old!");
define("DATE_OLD", "Too old birthday date!");
define("DATE_FUTURE", "Date cannot be greater than the current time!");

define("BAD_AUTH", "User with this email does not exist or password is incorrect");

define("DELIVERY_TOO_LATE", "DELIVERY DATE MUST BE WITHIN 3 DAYS!");
define("DELIVERY_TOO_EARLY", "DELIVERY DATE MUST BE NO EARLIER THAN 3 HOURS!");
define("DELIVERY_PAST", "DELIVERY DATE CANNOT BE IN THE PAST!");

define("PAGE_SIZE", 5);

define("JWT_NULL", -1);
define("JWT_INVALID", 0);
define("JWT_INVALID_SIGNATURE", 1);
define("JWT_EXPIRED", 2);
define("JWT_LOGOUT", 3);
define("JWT_VALID", 4);