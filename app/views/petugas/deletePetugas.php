<?php
require_once '../../app.php';

use App\Controllers\Dashboard;
use App\Controllers\Flasher;

// decrypt first
// $cipher = "aes-128-gcm";
// $ivlen = openssl_cipher_iv_length($cipher);
// $iv = openssl_random_pseudo_bytes($ivlen);
// dd($idpetugas);
// var_dump(openssl_decrypt($idpetugas, $cipher, 'siwa', $option = 0, $iv, $tag));
// die;

$idpetugas = $_GET['idpetugas'];

Dashboard::deletePetugas($idpetugas);
Flasher::set('Petugas dihapus!');

redirect('index.php');
