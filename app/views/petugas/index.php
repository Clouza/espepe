<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

$petugas = Dashboard::readPetugas();

if (isset($_POST['delete'])) {
    $idpetugas = $_POST['idpetugas'];
    Dashboard::deletePetugas($idpetugas);
    Flasher::set('Petugas dihapus!');
}

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Petugas</div>
    <div class="container">
        <a href="addPetugas.php">Tambah Petugas</a>
        <table border="1">
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
                        <form action="" method="post">
                            <input type="hidden" name="idpetugas" value="<?= $p['id_petugas']; ?>">
                            <button type="submit" name="delete">Hapus</button>
                        </form>
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