<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

$siswa = Dashboard::readSiswa();

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Siswa</div>
    <div class="container">
        <a href="addSiswa.php">Tambah Siswa</a>
        <table border="1">
            <tr>
                <th>NISN</th>
                <th>NIS</th>
                <th>Email</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Alamat</th>
                <th>No Telp</th>
                <th>Id SPP</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($siswa as $s) : ?>
                <tr>
                    <td><?= $s['nisn'] ?></td>
                    <td><?= $s['nis'] ?></td>
                    <td><?= $s['email'] ?></td>
                    <td><?= $s['nama'] ?></td>
                    <td><?= $s['nama_kelas'] ?></td>
                    <td><?= $s['alamat'] ?></td>
                    <td><?= $s['no_telp'] ?></td>
                    <td><?= $s['id_spp'] ?></td>
                    <td>
                        <a href="deleteSiswa.php?idsiswa=<?= $s['nis'] ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </div>
</section>

<?php if (Session::has('flashMessage')) : ?>
    <div class="flash-message">
        <span><?= Flasher::get() ?></span>
    </div>
<?php endif; ?>

<?php
reqFile('../templates/footer.php');
?>