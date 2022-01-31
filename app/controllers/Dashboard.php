<?php

namespace App\Controllers;

use App\Controllers\Flasher;
use App\Controllers\Database;
use Exception;

class Dashboard
{
    // ================================== Pembayaran
    public static function payday($idpembayaran, $idpetugas, $nisn, $tglbayar, $bulan, $tahun, $idspp, $jumlah)
    {
        $db = new Database;

        // cek nisn
        $siswa = $db->table('siswa')->where('nisn', '=', $nisn)->get()->fetch_assoc();
        $idspp = $siswa['id_spp'];

        $db->payment($idpembayaran, $idpetugas, $nisn, $tglbayar, $bulan, $tahun, $idspp, $jumlah);

        return Flasher::set('Pembayaran ditambahkan');
    }

    public static function getSiswa()
    {
        $db = new Database;
        return $db->table('siswa')->orderBy('nis')->get();
    }

    // ================================== History
    public static function getHistory($nisn)
    {
        $db = new Database;
        return $db->table('pembayaran')->join('pembayaran', 'JOIN', 'petugas', 'pembayaran.id_petugas = petugas.id_petugas')->where('nisn', '=', $nisn)->orderBy('tgl_bayar', 'DESC')->get();
    }

    public static function findSiswaByNis($nis)
    {
        $db = new Database;
        try {
            $siswa = $db->table('siswa')->where('nis', '=', $nis)->get()->fetch_assoc();

            if (!is_null($siswa)) {
                return (int)$siswa['nisn'];
            }
            throw new Exception("Siswa tidak ada", 404);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // ================================== Petugas
    public static function readPetugas()
    {
        $db = new Database;
        $join = $db->join('petugas', 'JOIN', 'otorisasi', 'petugas.level = otorisasi.id_otorisasi')->get();

        return $join;
    }

    public static function addPetugas($idpetugas, $username, $password, $nama, $otorisasi)
    {
        $db = new Database;
        $petugas = $db->table('petugas')->addPetugas($idpetugas, $username, $password, $nama, $otorisasi);

        if ($petugas) { //true
            Flasher::set('Petugas ditambahkan!');
        }
    }

    public static function deletePetugas($idpetugas)
    {
        $db = new Database;
        $db->table('petugas')->deletePetugas($idpetugas);

        return refresh();
    }

    public static function getRole()
    {
        $db = new Database;
        return $db->table('otorisasi')->get();
    }
}
