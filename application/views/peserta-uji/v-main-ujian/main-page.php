<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $_meta_deskripsi ?>">
    <meta name="keywords" content="<?= $_meta_keyword ?>">
    <meta name="author" content="andhika6@gmail.com">
    <link rel="icon" href="<?= base_url('assets/img/logo.png') ?>">

    <title><?= isset($title) ? $title : 'Aplikasi CAT' ?></title>

    <!-- core CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/toastr.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet">
    <!-- core JS -->
    <script src="<?= base_url('assets/js/jquery-3.6.4.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/toastr.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/sweetalert.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.countdown.min.js') ?>"></script>

</head>

<body>
    <?php $this->load->view('peserta-uji/v-main-ujian/navbar-main-ujian'); ?>

    <?php
    if (isset($content) && $content) {
        $this->load->view($content);
    }
    ?>

    <?php $this->load->view('peserta-uji/v-main-ujian/footer-main-ujian'); ?>

</body>

</html>