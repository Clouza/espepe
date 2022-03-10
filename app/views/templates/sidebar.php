<?php

use App\Controllers\AuthenticationController as Auth;
use App\Controllers\Session;

if (isset($_POST['logout'])) {
    Auth::logout($_POST['logout']);
}
?>

<body>
    <!-- https://dribbble.com/tags/navigation_bar -->
    <div class="sidebar">
        <div class="logo-details">
            <div class="logo-name">ESPEPE</div>
            <i class='bx bx-menu' id="btn"></i>
        </div>

        <!-- content sidebar -->
        <ul class="nav-list">
            <?php if (Session::get('level') == 2) : ?>
                <li id="search">
                    <i class='bx bx-search'></i>
                    <form action="../dashboard/search.php" method="GET">
                        <input type="text" name="q" placeholder="Cari Siswa...">
                        <input type="submit" hidden>
                    </form>
                    <span class="tooltip">Cari Siswa</span>
                </li>
                <div class="divider"></div>
            <?php endif; ?>


            <?php if (Session::get('level') == 2) : ?>
                <li>
                    <a href="../kelas/index.php" class="<?= (currentApp() == 'kelas/index' || currentApp() == 'kelas/addKelas' || currentApp() == 'kelas/updateKelas' ? 'active' : '') ?>">
                        <i class='bx bxs-book'></i>
                        <span class="links-name">Kelas</span>
                    </a>
                    <span class="tooltip">Kelas</span>
                </li>
            <?php endif; ?>

            <?php if (Session::get('level') == 2) : ?>
                <li>
                    <a href="../siswa/index.php" class="<?= (currentApp() == 'siswa/index' || currentApp() == 'siswa/addSiswa' || currentApp() == 'siswa/updateSiswa' ? 'active' : '') ?>">
                        <i class='bx bxs-graduation'></i>
                        <span class="links-name">Siswa</span>
                    </a>
                    <span class="tooltip">Siswa</span>
                </li>
            <?php endif; ?>

            <?php if (Session::get('level') == 2) : ?>
                <li>
                    <a href="../spp/index.php" class="<?= (currentApp() == 'spp/index' || currentApp() == 'spp/detailspp' ? 'active' : '') ?>">
                        <i class='bx bxs-donate-heart'></i>
                        <span class="links-name">SPP</span>
                    </a>
                    <span class="tooltip">SPP</span>
                </li>
            <?php endif; ?>

            <?php if (Session::get('level') == 2) : ?>
                <li>
                    <a href="../petugas/index.php" class="<?= (currentApp() == 'petugas/index' || currentApp() == 'petugas/addPetugas' || currentApp() == 'petugas/updatePetugas' ? 'active' : '') ?>">
                        <i class='bx bx-user'></i>
                        <span class="links-name">Petugas</span>
                    </a>
                    <span class="tooltip">Petugas</span>
                </li>
                <div class="divider"></div>
            <?php endif; ?>


            <?php if (Session::has('level')) : ?>
                <li>
                    <a href="../dashboard/pembayaran.php" class="<?= (currentApp() == 'dashboard/pembayaran' ? 'active' : '') ?>">
                        <i class='bx bxs-bank'></i>
                        <span class="links-name">Pembayaran</span>
                    </a>
                    <span class="tooltip">Pembayaran</span>
                </li>
            <?php endif; ?>
            <li>
                <a href="../dashboard/history.php" class="<?= (currentApp() == 'dashboard/history' ? 'active' : '') ?>">
                    <i class='bx bx-history'></i>
                    <span class="links-name">History</span>
                </a>
                <span class="tooltip">History</span>
            </li>

            <!-- footer sidebar -->
            <li class="profile" id="footerSidebar">
                <div class="profile-details">
                    <img src="https://smkti-baliglobal.sch.id/gambar/icon.png" alt="profileImg">
                    <div class="name-job">
                        <?php if (Session::has('level')) : ?>
                            <div class="name"><?= Session::get('profile'); ?></div>
                        <?php else : ?>
                            <div class="name"><?= Session::get('nis'); ?></div>
                            <div class="title"><?= Session::get('kelas'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <form action="" method="post">
                    <button type="submit" name="logout"><i class='bx bx-log-out' id="log-out"></i></button>
                </form>
            </li>
        </ul>
    </div>