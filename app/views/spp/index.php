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

$spp = Dashboard::readSPPWithSiswa();

reqFile('../templates/header.php');
reqFile('../templates/sidebar.php');
?>

<!-- page content -->
<section class="home-section">
    <div class="text">SPP</div>
    <div class="container">
        <?php if (Session::get('level') == 2) : ?>
            <form action="reset.php" method="post">
                <div class="form-group">
                    <input type="text" placeholder="Password" name="password">
                    <?php if (Session::get('level') == 2) : ?>
                        <button type="submit" class="btn" id="reset">Reset</button>
                    <?php endif; ?>
                </div>
            </form>
        <?php endif; ?>
        <table>
            <tr>
                <th>NIS</th>
                <th>Nominal</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($spp as $s) : ?>
                <tr>
                    <td><?= $s['nis'] ?></td>
                    <td>Rp<?= number_format($s['nominal'], '2', ',', '.'); ?>-</td>
                    <td><?= $s['tahun'] ?></td>
                    <td>
                        <a href="detailspp.php?ns=<?= $s['nis'] ?>"><button class="btn btn-action-primary">Detail</button></a>
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