<?php
function body_check($model, $body)
{
    $errors = [];
    foreach ($model as $field => $details) {

        if (is_null($details["required"])) {
            if (property_exists($body, $field) && is_null($body->$field)) {
                $errors[$field][] = "Field $field can't be null!";
                continue;
            }
        } else if ($details["required"] === true) {
            if (!property_exists($body, $field) || is_null($body->$field)) {
                $errors[$field][] = "Field $field is required!";
                continue;
            }
        }

        if (!property_exists($body, $field) || is_null($body->$field))
            continue;

        $correct_type = $details["type"];

        if (gettype($body->$field) != $correct_type) {
            $errors[$field][] = "Field $field should have a type: $correct_type";
            continue;
        }

        $handlerFile = realpath(dirname(__FILE__)) . "\\..\\" . $details["handler"];
        if (file_exists($handlerFile)) {

            include_once $details["handler"];
            $new_errors = call_user_func($field . "_check", $body->$field);
            if (!is_null($new_errors)) {
                foreach ($new_errors as $current_error) {
                    $errors[$field][] = $current_error;
                }
            }

        }

    }
    return $errors;
}

function body_handler($body_model, $req)
{
    global $Log_id;
    if ($body_model != new stdClass()) {
        if (is_null($req["body"])) {
            setHTTPStatus("400", "Invalid JSON in body!");
            exit;
        }
        $check = body_check($body_model, $req["body"]);
        if (!empty($check)) {
            setHTTPStatus("400");
            echo json_encode(["title" => VAL_ERR, "errors" => $check, "traceId" => $Log_id]);
            exit;
        }
    }
}