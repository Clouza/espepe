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
            if (is_null($siswa)) {
                throw new Exception("Siswa tidak ada", 404);
            }
            return (int)$siswa['nisn'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // ================================== Petugas
    public static function readPetugas()
    {
        $db = new Database;
        $join = $db->join('petugas', 'JOIN', 'otorisasi', 'petugas.level = otorisasi.id_otorisasi')->orderBy('id_petugas')->get();

        return $join;
    }

    public static function readPetugasById($idpetugas)
    {
        $db = new Database;
        return $db->table('petugas')->where('id_petugas', '=', $idpetugas)->get()->fetch_assoc();
    }

    public static function addPetugas($username, $password, $nama, $otorisasi)
    {
        $db = new Database;
        $petugas = $db->table('petugas')->addPetugas($username, $password, $nama, $otorisasi);
    }

    public static function updatePetugas($idpetugas, $username, $nama, $otorisasi)
    {
        $db = new Database;
        $petugas = $db->table('petugas')->updatePetugas($idpetugas, $username, $nama, $otorisasi);
        return Flasher::set('Petugas diubah!');
    }

    public static function deletePetugas($idpetugas)
    {
        $db = new Database;
        return $db->table('petugas')->deletePetugas($idpetugas);
    }

    public static function getRole()
    {
        $db = new Database;
        return $db->table('otorisasi')->get();
    }

    // ================================== Kelas
    public static function getKelas()
    {
        $db = new Database;
        return $db->table('kelas')->get();
    }

    public static function readKelas()
    {
        $db = new Database;
        return $db->table('kelas')->get();
    }

    public static function readKelasById($idkelas)
    {
        $db = new Database;
        return $db->table('kelas')->where('id_kelas', '=', $idkelas)->get()->fetch_assoc();
    }

    public static function addKelas($nama, $kompetensi)
    {
        $db = new Database;
        $kelas = $db->table('kelas')->addKelas($nama, $kompetensi);
    }

    public static function updateKelas($idkelas, $nama, $kompetensi)
    {
        $db = new Database;
        $kelas = $db->table('kelas')->updateKelas($idkelas, $nama, $kompetensi);
        return Flasher::set('Kelas diubah!');
    }

    public static function deleteKelas($idkelas)
    {
        $db = new Database;
        return $db->table('kelas')->deleteKelas($idkelas);
    }

    // ================================== Siswa
    public static function readSiswa()
    {
        $db = new Database;
        return $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas JOIN spp ON siswa.id_spp = spp.id_spp')->where('is_deleted', '=', '0')->get();
    }

    public static function addSiswa($nisn, $nis, $email, $password, $nama, $idkelas, $alamat, $notelp)
    {
        $db = new Database;

        // create new row in table spp
        $db->table('spp')->addSpp();

        // add siswa
        $db->table('siswa')->addSiswa($nisn, $nis, $email, $password, $nama, $idkelas, $alamat, $notelp);

        // update new row in table siswa according with table spp
        $foreign = $db->table('siswa')->where('nis', '=', $nis)->get()->fetch_assoc()['nis'];
        return $db->updateSiswaWithSpp($foreign);
    }

    public static function updateSiswa($nisn, $nis, $email, $nama, $kelas, $alamat, $notelp)
    {
        $db = new Database;
        $siswa = $db->table('petugas')->updateSiswa($nisn, $nis, $email, $nama, $kelas, $alamat, $notelp);
        return Flasher::set('Petugas diubah!');
    }

    public static function readSiswaByNis($nis)
    {
        $db = new Database;
        return $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas JOIN spp ON siswa.id_spp = spp.id_spp')->where('nis', '=', $nis)->get()->fetch_assoc();
    }

    public static function deleteSiswa($idsiswa)
    {
        $db = new Database;
        return $db->table('siswa')->deleteSiswa($idsiswa);
    }
}
