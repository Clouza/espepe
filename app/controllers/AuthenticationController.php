<?php

namespace App\Controllers;

use App\Controllers\Database;
use App\Controllers\Session;
use App\Controllers\Flasher as Flash;

class AuthenticationController
{
    public static function checkLog($post)
    {
        $db = new Database;

        // check if $_POST not empty
        if (!empty($_POST)) {
            $user = $post['user'];
            $password = $post['password'];

            // check is user login with email
            // $emailCheck = $db->table('siswa')->where('email', '=', $user)->get();
            // $emailFetch = $emailCheck->fetch_assoc();
            // if ($emailCheck->num_rows > 0 && $emailFetch['password'] == $password) {
            //     $match = $db->table('siswa')->where('password', '=', $password)->get();
            //     if ($match->num_rows > 0) {
            //         Session::set('authenticated', $user);
            //         Session::set('profile', $emailFetch['nama']);
            //         return redirect('app/views/dashboard.php');
            //     } else {
            //         return Flash::set('Email atau Password salah');
            //     }
            // }

            // check is user login with nis
            $nisCheck = $db->table('siswa')->where('nis', '=', $user)->get();
            $nisFetch = $nisCheck->fetch_assoc();
            if ($nisCheck->num_rows > 0) {
                $match = $db->table('siswa')->where('password', '=', $password)->get();
                if ($match->num_rows > 0 && $nisFetch['password'] == $password) {
                    Session::set('authenticated', $user);
                    Session::set('profile', $nisFetch['nama']);
                    Session::set('nisn', $nisFetch['nisn']);
                    return redirect('app/views/dashboard/index.php');
                } else {
                    return Flash::set('Password salah atau kamu bukan pemilik akun ini? Jangan begitu ya lain kali, waterpark men');
                }
            }

            // check is user login with username
            $usernameCheck = $db->table('petugas')->where('username', '=', $user)->get();
            $usernameFetch = $usernameCheck->fetch_assoc();
            if ($usernameCheck->num_rows > 0 && $usernameFetch['password'] == $password) {
                $match = $db->table('petugas')->where('password', '=', $password)->get();
                if ($match->num_rows > 0) {
                    Session::set('authenticated', $user);
                    Session::set('profile', $usernameFetch['nama_petugas']);
                    Session::set('level', $usernameFetch['level']);
                    Session::set('idpetugas', $usernameFetch['id_petugas']);
                    return redirect('app/views/dashboard/index.php');
                } else {
                    return Flash::set('Username atau Password salah');
                }
            }

            return Flash::set('NIS tidak ada, na ha yo panik');
        }
    }

    public static function logout($post)
    {
        Session::clear();
        return redirect('../../../index.php');
    }
}
