<?php

namespace App\Controllers;

use PDO;
use mysqli;
use Exception;

// ajax handler
if (isset($_POST['func'])) {
    $functionRequest = $_POST['func'];
    switch ($functionRequest) {
        case 'getDetailSiswaByNis':
            $nisn = $_POST['ns'];
            Database::getDetailSiswaByNis($nisn);
            break;
        default:
            # code...
            break;
    }
}

class Database
{
    private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'db_spp';
    private $conn;

    private
        // table function
        $table,
        // where function
        $where,
        // join
        $join,
        // order by
        $orderBy;

    public function __construct()
    {
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

            // check connection
            if ($this->conn->connect_error) {
                throw new Exception("Connection Fail!", 505);
            }
        } catch (Exception $e) {
            echo $this->conn->connect_error . '<hr>' . $e->getMessage();
            die;
        }
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function assocRead($result)
    {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function where($field, ?string $operator = '=', $value)
    {
        $this->where = "WHERE $field $operator '$value'";

        return $this;
    }

    public function join($from, $type, $to, $on)
    {
        $this->join = "$from $type $to ON $on";
        return $this;
    }

    public function orderBy($field, $orderBy = 'ASC')
    {
        $this->orderBy = "ORDER BY $field $orderBy";
        return $this;
    }

    // will be active when have learned PDO
    // public function insert($data = [])
    // {
    //     print_r(PDO::getAvailableDrivers());
    //     foreach ($data as $d) {
    //         $insert = "INSERT INTO $this->table VALUES ($d)";
    //     }
    //     die;
    // }

    /**
     * Get query from database
     * ! Don't change this function, when you want to change please change all of your code where there is this function
     * @return object mysqli result
     */
    public function get()
    {
        // standard query select
        $sql = "SELECT * FROM $this->table $this->join $this->where $this->orderBy";
        $result = $this->conn->query($sql); // return object mysqli result

        return $result;
    }

    public function payment($idpembayaran, $idpetugas, $nisn, $tglbayar, $bulan, $tahun, $idspp, $jumlah)
    {
        $siswaIdSpp = $this->table('spp')->where('id_spp', '=', $idspp)->get()->fetch_assoc();

        $pembayaran = "INSERT INTO pembayaran VALUES ('$idpembayaran', '$idpetugas', '$nisn', '$tglbayar', '$bulan', '$tahun', '$idspp', '$jumlah')";
        $this->conn->query($pembayaran);

        $nominal = (int)$siswaIdSpp['nominal'] + (int)$jumlah;

        $updateTahunSpp = "UPDATE spp SET tahun = $tahun WHERE id_spp = $idspp";
        $updateNominalSpp = "UPDATE spp SET nominal = $nominal WHERE id_spp = $idspp";

        $this->conn->query($updateTahunSpp);
        return $this->conn->query($updateNominalSpp);
    }

    public function addPetugas($idpetugas, $username, $password, $nama, $otorisasi)
    {
        try {
            $query = "INSERT INTO $this->table VALUES ('$idpetugas', '$username', '$password', '$nama', '$otorisasi')";

            if (!$this->conn->query($query)) {
                throw new Exception("Ada kesalahan! Cek fungsi ini " . '(' . __FUNCTION__ . ')', 1);
            } else {
                return $this->conn->query($query);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }
    public function deletePetugas($idpetugas)
    {
        try {
            $query = "DELETE FROM $this->table WHERE id_petugas = $idpetugas";

            if (!$this->conn->query($query)) {
                throw new Exception("Ada kesalahan! Cek fungsi ini " . '(' . __FUNCTION__ . ')', 1);
            }

            $result = $this->conn->query($query);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        return $result;
    }

    // ajax handler
    public static function getDetailSiswaByNis($nisn)
    {
        $db = new Database;
        // $result = $db->table('siswa')->where('nis', '=', $nis)->get()->fetch_assoc();
        $result = $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas')->where('nisn', '=', $nisn)->get()->fetch_assoc();

        echo 'NIS: ' . $result['nis'] . '<br>';
        echo 'NAMA: ' . $result['nama'] . '<br>';
        echo 'NO TELP: ' . $result['no_telp'] . '<br>';
        echo 'KELAS: ' . $result['nama_kelas'] . '<br>';
        echo 'KOMPETENSI: ' . $result['kompetensi_keahlian'] . '<br>';
    }
}
