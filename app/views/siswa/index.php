<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

if (Session::get('level') != '2') {
    return abort(404);
}

$siswa = Dashboard::readSiswa();

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Siswa</div>
    <div class="container">
        <a href="addSiswa.php"><button class="btn btn-add">+ Tambah Siswa</button></a>
        <table>
            <tr>
                <th>NISN</th>
                <th>NIS</th>
                <th>Email</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Nominal</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($siswa as $s) : ?>
                <tr>
                    <td><?= $s['nisn'] ?></td>
                    <td><?= $s['nis'] ?></td>
                    <td><?= $s['email'] ?></td>
                    <td><?= $s['nama'] ?></td>
                    <td><?= $s['nama_kelas'] ?></td>
                    <td>Rp<?= number_format($s['nominal'], '2', ',', '.'); ?>-</td>
                    <td>
                        <a href="../spp/detailspp.php?ns=<?= $s['nis']; ?>"><button class="btn btn-action-primary">Detail</button></a>
                        <a href="updateSiswa.php?ns=<?= $s['nis']; ?>"><button class="btn btn-action-secondary">Edit</button></a>
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