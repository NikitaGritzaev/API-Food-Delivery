<?php
    function hashPass($pass, $register = true) {
        if ($register) {
            $salt = bin2hex(random_bytes(20));
            $pass = $pass . $salt;
        }

        for ($i = 1; $i <= 50; $i++) {
            $pass = hash("sha1", $pass);
        }
        
        return ["pass" => $pass, "salt" => $salt];
    }