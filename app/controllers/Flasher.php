<?php

namespace App\Controllers;

use App\Controllers\Session;

class Flasher
{
    public static function set(string $value)
    {
        Session::set('flashMessage', $value);
    }

    public static function get()
    {
        if (Session::has('flashMessage')) {
            echo Session::get('flashMessage');
            Session::remove('flashMessage');
        }
    }
}
