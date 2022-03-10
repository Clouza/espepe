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

$petugas = Dashboard::readPetugas();

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Petugas</div>
    <div class="container">
        <a href="addPetugas.php"><button class="btn btn-add">+ Tambah Petugas</button></a>
        <table>
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Nama</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($petugas as $p) : ?>
                <tr>
                    <td><?= $p['username'] ?></td>
                    <td><?= $p['password'] ?></td>
                    <td><?= $p['nama_petugas'] ?></td>
                    <td><?= $p['nama_otorisasi'] ?></td>
                    <td>
                        <a href="updatePetugas.php?idpetugas=<?= $p['id_petugas'] ?>"><button class="btn btn-action-primary">Edit</button></a>
                        <a href="deletePetugas.php?idpetugas=<?= $p['id_petugas'] ?>"><button class="btn btn-action-secondary">Hapus</button></a>
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