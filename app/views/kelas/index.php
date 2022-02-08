<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

$kelas = Dashboard::readKelas();

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Kelas</div>
    <div class="container">
        <a href="addKelas.php">Tambah Kelas</a>
        <table>
            <tr>
                <th>Nama Kelas</th>
                <th>kompetensi Keahlian</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($kelas as $k) : ?>
                <tr>
                    <td><?= $k['nama_kelas'] ?></td>
                    <td><?= $k['kompetensi_keahlian'] ?></td>
                    <td>
                        <a href="updateKelas.php?idkelas=<?= $k['id_kelas'] ?>">Edit</a>
                        <a href="deleteKelas.php?idkelas=<?= $k['id_kelas'] ?>">Hapus</a>
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