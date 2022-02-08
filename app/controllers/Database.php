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
    public $conn;

    private
        // table function
        $table,
        // where function
        $where,
        // join
        $join,
        // order by
        $orderBy,
        // limit
        $limit;

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

    public function limit($limit)
    {
        $this->limit = "LIMIT $limit";
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
        $sql = "SELECT * FROM $this->table $this->join $this->where $this->orderBy $this->limit";
        $result = $this->conn->query($sql); // return object mysqli result
        return $result;
    }

    // ================================== Pembayaran
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

    // ================================== Petugas
    public function addPetugas($username, $password, $nama, $otorisasi)
    {
        try {
            $query = "INSERT INTO $this->table VALUES ('', '$username', '$password', '$nama', '$otorisasi')";
            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function updatePetugas($idpetugas, $username, $nama, $otorisasi)
    {
        try {
            $query = "UPDATE petugas SET username = '$username', nama_petugas = '$nama', level = '$otorisasi' WHERE id_petugas = '$idpetugas'";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
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
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }

            $result = $this->conn->query($query);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        return $result;
    }

    // ================================== Kelas
    public function addKelas($nama, $kompetensi)
    {
        try {
            $query = "INSERT INTO $this->table VALUES ('', '$nama', '$kompetensi')";
            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function updateKelas($idkelas, $nama, $kompetensi)
    {
        try {
            $query = "UPDATE kelas SET nama_kelas = '$nama', kompetensi_keahlian = '$kompetensi' WHERE id_kelas = '$idkelas'";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function deleteKelas($idkelas)
    {
        try {
            $query = "DELETE FROM $this->table WHERE id_kelas = $idkelas";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }

            $result = $this->conn->query($query);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        return $result;
    }

    // ================================== Siswa
    public function addSiswa($nisn, $nis, $email, $password, $nama, $idkelas, $alamat, $notelp)
    {
        try {
            $query = "INSERT INTO $this->table VALUES ('$nisn', '$nis', '$email', '$password', '$nama', '$idkelas', '$alamat', '$notelp', '', '')";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function updateSiswaWithSpp($nis)
    {
        try {
            $db = new Database;
            $getCurrentSPP = $db->table('spp')->orderBy('id_spp', 'DESC')->limit('1')->get()->fetch_assoc()['id_spp'];
            $query = "UPDATE siswa SET id_spp = '$getCurrentSPP' WHERE nis = '$nis'";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function updateSiswa($nisn, $nis, $email, $nama, $kelas, $alamat, $notelp)
    {
        try {
            // update siswa
            $query = "UPDATE siswa SET nisn = '$nisn', nis = '$nis', email = '$email', nama = '$nama', id_kelas = '$kelas', alamat = '$alamat', no_telp = '$notelp' WHERE nisn = '$nisn'";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }


    public function deleteSiswa($idsiswa)
    {
        try {
            // take id spp from $idsiswa
            $siswaSelected = $this->table('siswa')->where('nis', '=', $idsiswa)->get()->fetch_assoc();
            $siswaSelected = $siswaSelected['id_spp'];

            // delete siswa (not actually deleted)
            $query = "UPDATE $this->table SET is_deleted = '1' WHERE nis = $idsiswa";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }

            $result = $this->conn->query($query);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        return $result;
    }

    // ================================== SPP
    public function addSpp()
    {
        try {
            $now = date('Y');
            $query = "INSERT INTO $this->table VALUES('', '$now', '0')";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    // ================================== EMAIL
    public function addToken($token, $email)
    {
        try {
            $expired = time();
            $query = "INSERT INTO $this->table VALUES('', '$token', '$email', '$expired')";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    // ================================== AJAX HANDLER
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
