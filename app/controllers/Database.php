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
        case 'setStatusSiswa':
            $db = new Database;
            $nis = $_POST['ns'];
            $checked = $_POST['ic'];
            $db->setStatusSiswa($nis, $checked);
            break;
        case 'findPembayaranByTahun':
            $db = new Database;
            $idspp = $_POST['idspp'];
            $tahun = $_POST['tahun'];
            $db->findPembayaranByTahun($idspp, $tahun);
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
        // and
        $and,
        // join
        $join,
        // order by
        $orderBy,
        // limit
        $limit,
        // search
        $search;

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

    public function search($field, $like)
    {
        $this->search = "WHERE $field LIKE '%$like%'";
        return $this;
    }

    public function and($and)
    {
        $this->and = "AND $and";
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
        $sql = "SELECT * FROM $this->table $this->join $this->where $this->orderBy $this->limit $this->search $this->and";
        $result = $this->conn->query($sql); // return object mysqli result
        if ($result) {
            return $result;
        } else {
            return $this->conn->error;
            die;
        }
    }

    // ================================== Pembayaran
    public function payment($idpembayaran, $idpetugas, $nisn, $tglbayar, $bulan, $tahun, $idspp, $jumlah)
    {
        $db = new Database;
        // cek pembayaran ganda (tahun & bulan)
        $tahunQuery = $db->table('pembayaran')->where('tahun_dibayar', '=', $tahun)->and("nisn = $nisn")->get();
        $bulanQuery = $db->table('pembayaran')->where('bulan_dibayar', '=', $bulan)->and("nisn = $nisn")->get();

        // tahun ganda
        if ($tahunQuery->num_rows > 0 && $bulanQuery->num_rows > 0) {
            return true;
            die;
        }

        $siswaIdSpp = $this->table('spp')->where('id_spp', '=', $idspp)->get()->fetch_assoc();

        $pembayaran = "INSERT INTO pembayaran VALUES ('$idpembayaran', '$idpetugas', '$nisn', '$tglbayar', '$bulan', '$tahun', '$idspp', '$jumlah')";
        $this->conn->query($pembayaran);
        $nominal = (int)$siswaIdSpp['nominal'] - (int)$jumlah;

        // check if payment is made this year
        if ($tahun == date('Y')) {
            $updateTahunSpp = "UPDATE spp SET tahun = $tahun WHERE id_spp = $idspp";
            $updateNominalSpp = "UPDATE spp SET nominal = $nominal WHERE id_spp = $idspp";
            $this->conn->query($updateTahunSpp);
            $this->conn->query($updateNominalSpp);
        }
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
    public function addKelas($nama, $kompetensi, $kelas, $harga)
    {
        try {
            $query = "INSERT INTO $this->table VALUES ('', '$nama', '$kompetensi', $kelas, $harga)";
            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function updateKelas($idkelas, $nama, $kompetensi, $kelas, $harga)
    {
        try {
            $query = "UPDATE kelas SET nama_kelas = '$nama', kompetensi_keahlian = '$kompetensi', kelas = '$kelas', harga = '$harga' WHERE id_kelas = '$idkelas'";

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

            // update forein key in table siswa
            $getCurrentSPP = $db->table('spp')->orderBy('id_spp', 'DESC')->limit('1')->get()->fetch_assoc()['id_spp'];
            $query = "UPDATE siswa SET id_spp = '$getCurrentSPP' WHERE nis = '$nis'";
            $this->conn->query($query);

            $updateSPP = $this->setNominal();

            if (!$this->conn->query($updateSPP)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    private function setNominal()
    {
        $db = new Database;
        // set default nominal
        $nominal = $db->join('siswa', 'JOIN', 'spp', 'siswa.id_spp = spp.id_spp JOIN kelas ON siswa.id_kelas = kelas.id_kelas')->orderBy('nis', 'DESC')->limit('1')->get()->fetch_assoc();
        $idspp = $nominal['id_spp'];
        $harga = $nominal['harga'] * 12;
        return "UPDATE spp SET nominal = '$harga' WHERE id_spp = '$idspp'";
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

    public function resetSPP($password, $level, $username)
    {
        // cek level
        if ($level === '2') {
            // cek username
            $username = $this->table('petugas')->where('username', '=', $username)->get()->fetch_assoc();

            // cek password
            if ($username['password'] === $password) {
                $db = new Database;
                // get harga tiap siswa
                $getHarga = $db->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas')->get();
                $tahun = date('Y');

                // set nominal in spp by harga * 12
                foreach ($getHarga as $gh) {
                    $idspp = $gh['id_spp'];
                    $nominal = $gh['harga'] * 12;
                    $updateSpp = "UPDATE spp SET tahun = '$tahun', nominal = '$nominal' WHERE id_spp = $idspp";
                    $this->conn->query($updateSpp);
                }

                return true;
            } else {
                return false;
            }
        }
    }

    // ================================== EMAIL & TOKEN
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

    public function deleteToken($idtoken)
    {
        try {
            $query = "DELETE FROM token WHERE id_token = $idtoken";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function changepassword($email, $password)
    {
        try {
            $db = new Database;
            $nisn = $db->table('siswa')->where('email', '=', $email)->get()->fetch_assoc()['nisn'];
            $query = "UPDATE siswa SET password = '$password' WHERE nisn = $nisn";

            if (!$this->conn->query($query)) {
                throw new Exception("Something went wrong at (" . __METHOD__ . ') <hr>' .  $this->conn->error, 1);
            }

            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    // ================================== AJAX HANDLER
    public static function getDetailSiswaByNis($nisn)
    {
        $db = new Database;
        $result = $db->table('siswa')->join('siswa', 'JOIN', 'kelas', 'siswa.id_kelas = kelas.id_kelas')->where('nisn', '=', $nisn)->get()->fetch_assoc();

        echo json_encode($result);
    }

    public function setStatusSiswa($nis, $checked)
    {
        $query = "UPDATE siswa SET is_deleted = '$checked' WHERE nis = '$nis'";
        $this->conn->query($query);
    }

    public function findPembayaranByTahun($idspp, $tahun)
    {
        $db = new Database;
        $pembayaran = $db->table('pembayaran')->where('id_spp', '=', $idspp)->and('tahun_dibayar = ' . $tahun)->get();
        $list = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $bulan = [];
        foreach ($pembayaran as $p) {
            array_push($bulan, ucwords($p['bulan_dibayar']));
        }

        $merge = array_merge($bulan, $list);
        $cek = array_count_values($merge);

        foreach ($merge as $p) {
            if ($cek[$p] > 1) {
                $dibayar[] = "<span class='text-success'>$p &#10003; </span>";
            } else {
                $dibayar[] = "<span class='text-danger'>$p &#10005; </span>";
            }
        }

        $dibayar = array_slice($dibayar, count($bulan));
        echo json_encode($dibayar);
    }
}
