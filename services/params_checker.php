<?php
function params_checker($model, $params)
{
    $types = [
        "int" => "/^[-+]?[0-9]+$/",
        "int_N" => "/^[1-9][0-9]*$/",
        "string" => "/.+/", "bool" => "/^(true|false)$/"
    ];
    $errors = [];
    $new_params = [];
    foreach ($model as $field => $details) {
        if (is_null($params->$field)) {
            $new_params[$field] = $details["default"];
            continue;
        }

        $correct_type = $details["type"];
        $regex = $types[$correct_type];
        foreach ($params->$field as $value) {
            if (!preg_match($regex, $value)) {
                $errors[$field][] = "Value $value has incorrect type (expected $correct_type)!";
            } else if (!is_null($details["regex"]) && !preg_match($details["regex"], $value)) {
                $errors[$field][] = "Value $value is not a correct $field!";
            } else {
                if ($details["many"]) {
                    $new_params[$field][] = $value;
                }
                else {
                    $new_params[$field] = $value;
                }
            }
            if (!$details["many"]) {
                break;
            }
        }
    }
    return ["errors" => $errors, "parameters" => $new_params];
}

function params_handler($params_model, $req)
{
    global $Log_id;
    if ($params_model != new stdClass()) {
        $check = params_checker($params_model, $req["parameters"]);
        if (!empty($check["errors"])) {
            setHTTPStatus("400");
            echo json_encode([
                "title" => VAL_ERR,
                "errors" => $check["errors"],
                "traceId" => $Log_id
            ]);
            exit;
        } 
        return $check["parameters"];
    }
    return $req["parameters"];
}