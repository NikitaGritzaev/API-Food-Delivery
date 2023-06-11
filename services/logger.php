<?php
function log_request($req, $traceId, $status) {
    $path = __DIR__ . "/../logs/log_" . date("j-n-Y") . ".txt";
    $log_file = fopen($path, "a+");
    $cur = date("H:i:s");
    $ip = $_SERVER["REMOTE_ADDR"];
    $addr = $req["addr"];
    $method = $req["method"];

    if (!is_null($req["body"]->password)) {
        $req["body"]->password = "***";
    }

    $json_params = json_encode($req["parameters"], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    $json_body = json_encode($req["body"], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    $log_header = "[ $cur ] [ $status ] [ $method $addr ] [ $ip ] [ $traceId ]\n";
    fwrite($log_file, $log_header);
    fwrite($log_file, "params: $json_params\n");
    fwrite($log_file, "body: $json_body\n\n");
    fclose($log_file);
}