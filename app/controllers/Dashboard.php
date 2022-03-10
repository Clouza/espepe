<?php

namespace App\Controllers;

use App\Controllers\Flasher;
use App\Controllers\Database;
use Exception;

class Dashboard
{
    // ================================== General Functions & Properties
    public static $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    public static function assocRead($result)
    {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    // ================================== Pembayaran
    public static function payday($idpembayaran, $idpetugas, $nisn, $tglbayar, $bulan, $tahun, $idspp, $jumlah)
    {
        $db = new Database;

        // cek nisn
        $siswa = $db->table('siswa')->where('nisn', '=', $nisn)->get()->fetch_assoc();
        $idspp = $siswa['id_spp'];

        $check = $db->payment($idpembayaran, $idpetugas, $nisn, $tglbayar, $bulan, $tahun, $idspp, $jumlah);

        if ($check) {
            return Flasher::set("Pembayaran gagal! Tahun dan Bulan sudah ada!");
        }
        return Flasher::set("Pembayaran berhasil!");
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
        return $db->table('kelas')->orderBy('kompetensi_keahlian', 'DESC, kelas')->get();
    }

    public static function readKelasById($idkelas)
    {
        $db = new Database;
        return $db->table('kelas')->where('id_kelas', '=', $idkelas)->get()->fetch_assoc();
    }

    public static function addKelas($nama, $kompetensi, $kelas, $harga)
    {
        $db = new Database;
        $kelas = $db->table('kelas')->addKelas($nama, $kompetensi, $kelas, $harga);
    }

    public static function updateKelas($idkelas, $nama, $kompetensi, $kelas, $harga)
    {
        $db = new Database;
        $kelas = $db->table('kelas')->updateKelas($idkelas, $nama, $kompetensi, $kelas, $harga);
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
        return $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas JOIN spp ON siswa.id_spp = spp.id_spp')->where('is_deleted', '=', '0')->orderBy('nis', 'ASC')->get();
    }

    public static function addSiswa($nisn, $nis, $email, $password, $nama, $idkelas, $alamat, $notelp)
    {
        $db = new Database;

        // add siswa
        $db->table('siswa')->addSiswa($nisn, $nis, $email, $password, $nama, $idkelas, $alamat, $notelp);

        // create new row in table spp
        $db->table('spp')->addSpp();

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

    // ================================== Search
    public static function searchSiswa($like)
    {
        $db = new Database;
        $nama = $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas')->search('nama', $like)->get();
        $nis = $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas')->search('nis', $like)->get();
        $email = $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas')->search('email', $like)->get();
        if (mysqli_num_rows($nama) > 0) {
            return $nama;
        } else if (mysqli_num_rows($nis) > 0) {
            return $nis;
        } else {
            return $email;
        }
    }

    public static function searchKelas($like)
    {
        $db = new Database;
        $like = str_replace('-', ' ', $like);
        return $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas')->search('nama_kelas', $like)->and('is_deleted = 0')->get();
    }

    // ================================== SPP
    public static function readSPPWithSiswa()
    {
        $db = new Database;
        return $db->table('spp')->join('spp', 'JOIN', 'siswa', 'spp.id_spp = siswa.id_spp JOIN kelas ON siswa.id_kelas = kelas.id_kelas')->where('is_deleted', '=', '0')->orderBy('nis', 'ASC')->get();
    }

    public static function readDetailSPPWithDetailSiswa($nis)
    {
        $db = new Database;
        return $db->table('spp')->join('spp', 'JOIN', 'siswa', 'spp.id_spp = siswa.id_spp JOIN kelas ON kelas.id_kelas = siswa.id_kelas')->where('nis', '=', $nis)->get()->fetch_assoc();
    }

    public static function readBulanByPembayaran($idspp, $tahun)
    {
        $db = new Database;
        $pembayaran = $db->table('pembayaran')->where('id_spp', '=', $idspp)->and('tahun_dibayar = ' . $tahun)->get();

        $bulan = [];
        foreach ($pembayaran as $p) {
            // $bulan[] .= ucwords($p['bulan_dibayar']);
            array_push($bulan, ucwords($p['bulan_dibayar']));
        }
        return $bulan;
    }

    public static function reset($password, $level, $username)
    {
        $db = new Database;
        return $db->resetSPP($password, $level, $username);
    }
}
