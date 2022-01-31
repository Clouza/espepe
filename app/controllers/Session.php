<?php

namespace App\Controllers;

use Exception;
use App\Controllers\SessionInterface;

class Session implements SessionInterface
{
    public static function get($key)
    {
        if (Session::has($key)) {
            return $_SESSION[$key];
        }
        return null;
    }

    public static function set($key, $value): void
    {
        // $thiss = new self;
        $_SESSION[$key] = $value;
        // return $thiss;
    }

    public static function remove($key): void
    {
        if (Session::has($key)) {
            unset($_SESSION[$key]);
        } else {
            throw new Exception('Session Not Found', 404);
        }
    }

    public static function clear(): void
    {
        session_unset();
    }

    public static function has($key): bool
    {
        return array_key_exists($key, $_SESSION);
    }
}
