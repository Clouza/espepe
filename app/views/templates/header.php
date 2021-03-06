<?php
require_once '../../app.php';

use App\Controllers\Session;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (Session::has('level')) :  ?>
        <title> ESPEPE | <?= Session::get('profile'); ?> </title>
    <?php else :  ?>
        <title> ESPEPE </title>
    <?php endif; ?>

    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <!-- google fonts poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">

    <!-- css -->
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/global.css">
</head>