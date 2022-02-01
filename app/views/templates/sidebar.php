<?php

use App\Controllers\AuthenticationController as Auth;
use App\Controllers\Session;

if (isset($_POST['logout'])) {
    Auth::logout($_POST['logout']);
}
?>

<body>
    <div class="sidebar open">
        <div class="logo-details">
            <div class="logo-name">PBW</div>
            <i class='bx bx-menu' id="btn"></i>
        </div>

        <!-- content sidebar -->
        <ul class="nav-list">
            <!-- <li>
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search...">
                <span class="tooltip">Search</span>
            </li> -->
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
                    <a href="../petugas/index.php" class="<?= (currentApp() == 'petugas/index' || currentApp() == 'petugas/addPetugas' || currentApp() == 'petugas/updatePetugas' ? 'active' : '') ?>">
                        <i class='bx bx-user'></i>
                        <span class="links-name">Petugas</span>
                    </a>
                    <span class="tooltip">Petugas</span>
                </li>
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
            <li class="profile">
                <div class="profile-details">
                    <img src="../../../assets/img/profile.png" alt="profileImg">
                    <div class="name-job">
                        <div class="name"><?= Session::get('profile'); ?></div>
                        <div class="title"><?= Session::get('nisn'); ?></div>
                    </div>
                </div>
                <form action="" method="post">
                    <button type="submit" name="logout"><i class='bx bx-log-out' id="log-out"></i></button>
                </form>
            </li>
        </ul>
    </div>