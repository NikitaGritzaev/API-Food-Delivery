<?php
    function route($req) {
        global $Redis;
        $jwt = $req["jwt"];

        $tokenParts = explode(".", $req["jwt"]);
        $payload = base64_decode($tokenParts[1]);
        $payload = json_decode($payload, true);
        try {
            $Redis->set($jwt, "", "EXAT", $payload["exp"]);
        }
        catch(Exception $e) {
            setHTTPStatus(500, $e->getMessage());
        }
        
        setHTTPStatus(200, "Logged Out");
    }
    function get_details() {
        return [
            "params_model" => new stdClass(),
            "body_model" => new stdClass(),
            "authorization" => true
        ];
    }


