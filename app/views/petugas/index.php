<?php
require_once '../../app.php';

use App\Controllers\Flasher;
use App\Controllers\Session;
use App\Controllers\Dashboard;

if (!Session::has('authenticated')) {
    return redirect('../../index.php');
}

$petugas = Dashboard::readPetugas();

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">Petugas</div>
    <div class="container">
        <a href="addPetugas.php">Tambah Petugas</a>
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
                    <?php
                    // $cipher = "aes-128-gcm";
                    // $ivlen = openssl_cipher_iv_length($cipher);
                    // $iv = openssl_random_pseudo_bytes($ivlen);
                    // $encrypt = openssl_encrypt($p['id_petugas'], $cipher, 'siwa', $options = 0, $iv, $tag);
                    // var_dump(openssl_decrypt($encrypt, $cipher, 'siwa', $option = 0, $iv, $tag));
                    ?>
                    <td>
                        <a href="updatePetugas.php?idpetugas=<?= $p['id_petugas'] ?>">Edit</a>
                        <a href="deletePetugas.php?idpetugas=<?= $p['id_petugas'] ?>">Hapus</a>
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