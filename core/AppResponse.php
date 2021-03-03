<?php

namespace App\Core;

class AppResponse
{

    private static $statuses = [
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => '422 Unprocessable Entity',
        500 => '500 Internal Server Error'
    ];

    public static function json(array $content = [], $code = 200, $message = null)
    {
        header_remove();
        http_response_code($code);
        header('Content-Type: application/json');
        header('Status: ' . static::$statuses[$code]);
        echo json_encode($content);
    }
}