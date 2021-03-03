<?php

namespace App\Models;

class User
{
    private static $table = 'users';

    public static function fromRequest($data)
    {
        return [
            'username' => (string) $_POST['username'],
            'points' => sprintf("%.2f", $_POST['points'])
        ];
    }

    public static function unserialize($data)
    {
        return [
            'id' => (int) $data['id'],
            'username' => (string) $data['username'],
            'points' => round((float) $data['points'], 2)
        ];
    }

    public static function getTable()
    {
        return static::$table;
    }
}