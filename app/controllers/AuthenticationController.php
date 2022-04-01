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
            $user = htmlspecialchars($post['user']);
            $password = htmlspecialchars($post['password']);

            // check if user login with username
            $usernameCheck = $db->table('petugas')->where('username', '=', $user)->get();
            if ($usernameCheck->num_rows > 0) {
                // cek password
                $match = $db->table('petugas')->where('password', '=', $password)->get();
                if ($match->num_rows > 0) {
                    $match = $match->fetch_assoc();
                    Session::set('authenticated', $user);
                    Session::set('profile', $match['nama_petugas']);
                    Session::set('level', $match['level']);
                    Session::set('idpetugas', $match['id_petugas']);
                    return redirect('app/views/dashboard/index.php');
                } else {
                    return Flash::set('Username atau Password salah');
                }
            }

            // check if user login with email
            $emailCheck = $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas')->where('email', '=', $user)->get();
            if ($emailCheck->num_rows > 0) {
                // check password
                $match = $db->table('siswa')->where('password', '=', $password)->get();
                if ($match->num_rows > 0) { // before hashing
                    $match = $match->fetch_assoc();
                    Session::set('authenticated', $user);
                    Session::set('profile', $match['nama']);
                    Session::set('kelas', $match['nama_kelas']);
                    Session::set('nisn', $match['nisn']);
                    Session::set('nis', $match['nis']);
                    return redirect('app/views/dashboard/index.php');
                } else { // after hashing
                    $emailCheck = $emailCheck->fetch_assoc();
                    $password = passcheck($password, $emailCheck['password']); // true
                    if ($password) { // password match
                        $match = $emailCheck;
                        Session::set('authenticated', $user);
                        Session::set('profile', $match['nama']);
                        Session::set('kelas', $match['nama_kelas']);
                        Session::set('nisn', $match['nisn']);
                        Session::set('nis', $match['nis']);
                        return redirect('app/views/dashboard/index.php');
                    } else {
                        return Flash::set('NIS salah atau Password salah');
                    }
                }
            }

            // check if user login with nis
            $nisCheck = $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas')->where('nis', '=', $user)->get();
            if ($nisCheck->num_rows > 0) {
                // check password
                $match = $db->table('siswa')->where('password', '=', $password)->get();
                if ($match->num_rows > 0) { // before hashing
                    $match = $match->fetch_assoc();
                    Session::set('authenticated', $user);
                    Session::set('profile', $match['nama']);
                    Session::set('kelas', $match['nama_kelas']);
                    Session::set('nisn', $match['nisn']);
                    Session::set('nis', $match['nis']);
                    return redirect('app/views/dashboard/index.php');
                } else { // after hashing
                    $nisCheck = $nisCheck->fetch_assoc();
                    $password = passcheck($password, $nisCheck['password']); // true
                    if ($password) { // password match
                        $match = $nisCheck;
                        Session::set('authenticated', $user);
                        Session::set('profile', $match['nama']);
                        Session::set('kelas', $match['nama_kelas']);
                        Session::set('nisn', $match['nisn']);
                        Session::set('nis', $match['nis']);
                        return redirect('app/views/dashboard/index.php');
                    } else {
                        return Flash::set('NIS salah atau Password salah');
                    }
                }
            }

            return Flash::set('Silahkan masukkan NIS / Email / Username!');
        }
    }

    public static function logout($post)
    {
        Session::clear();
        return redirect('../../../index.php');
    }
}
