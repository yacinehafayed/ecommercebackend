<?php
namespace helpers;

class Request {
    // Add the word 'static' right here!
    public static function getJsonData(): array
    {
        $rawData = file_get_contents('php://input');
        return json_decode($rawData, true) ?? [];
    }
}