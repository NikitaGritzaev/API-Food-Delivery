<?php
function setHTTPStatus($status = 200, $response = null)
{
    global $Log_id;
    $header_message = "";
    switch ($status) {
        default:
        case 200:
            $header_message = "OK";
            break;
        case 400:
            $header_message = "Bad Request";
            break;
        case 401:
            $header_message = "Unauthorized";
            break;
        case 403:
            $header_message = "Forbidden";
            break;
        case 404:
            $header_message = "Not Found";
            break;
        case 405:
            $header_message = "Method Not Allowed";
            break;
        case 409:
            $header_message = "Conflict";
            break;
        case 415:
            $header_message = "Unsupported Media Type";
            break;
        case 500:
            $header_message = "Internal Server Error";
            break;
    }
    log_request(get_request(), $Log_id, $status);
    $protocol = (isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : "HTTP/1.0");
    header($protocol . " " . $status . " " . $header_message);
    if (!is_null($response)) {
        $server_response = ["message" => $response, "status" => intval($status)];
        if ($status == 500) {
            $server_response["traceId"] = $Log_id;
        }
        echo json_encode($server_response);
    }
}