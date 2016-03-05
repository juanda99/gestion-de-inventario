<?php

class Zend_Controller_Action_Helper_Mail extends Zend_Controller_Action_Helper_Abstract {

    function direct($url, $params) {
            $fp = fsockopen('ssl://' . $url['host'], 443, $errno, $errstr, 30);
            $out = "GET " . $url['path'] . " HTTP/1.1\r\n";
            $out.= "Host: " . $url['host'] . "\r\n";
            $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out.= "Content-Length: 0\r\n";
            $out.= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            fclose($fp);
    }
}
    
