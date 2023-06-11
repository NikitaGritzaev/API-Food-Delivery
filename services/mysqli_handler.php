<?php
class MySQLi_Handler extends MySQLi
{

    public function query($query, $resultmode = MYSQLI_STORE_RESULT, $handle = true)
    {
        $res = parent::query($query, $resultmode);
        if (!$res && $handle) {
            setHTTPStatus(500, $this->error);
            exit;
        }
        return $res;
    }
}