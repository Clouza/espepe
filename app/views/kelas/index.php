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

$kelas = Dashboard::readKelas();

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Kelas</div>
    <div class="container">
        <a href="addKelas.php"><button class="btn btn-add">+ Tambah Kelas</button></a>
        <table>
            <tr>
                <th>Kelas</th>
                <th>Nama Kelas</th>
                <th>Kompetensi Keahlian</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($kelas as $k) : ?>
                <tr>
                    <td><?= $k['kelas']; ?></td>
                    <td>
                        <a href="../dashboard/search.php?qk=<?= str_replace(' ', '-', strtolower($k['nama_kelas'])); ?>">
                            <button class="btn btn-on-table"><?= $k['nama_kelas'] ?></button>
                        </a>
                    </td>
                    <td><?= $k['kompetensi_keahlian']; ?></td>
                    <td>
                        <a href="updateKelas.php?idkelas=<?= $k['id_kelas'] ?>"><button class="btn btn-action-primary">Edit</button></a>
                        <a href="deleteKelas.php?idkelas=<?= $k['id_kelas'] ?>"><button class="btn btn-action-secondary" onclick="return confirm('Anda yakin?')">Hapus</button></a>
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