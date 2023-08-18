<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $_meta_deskripsi?>">
    <meta name="keywords" content="<?= $_meta_keyword?>">
    <meta name="author" content="andhika6@gmail.com">
    <link rel="icon" href="<?= base_url('assets/img/logo.png') ?>">

    <title><?= isset($title) ? $title : 'Aplikasi CAT' ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/toastr.min.css') ?>" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .card {
            width: 100%;
            max-width: 450px;
            padding: 15px;
            margin: 0 auto;
        }
    </style>

    <script src="<?= base_url('assets/js/jquery-3.6.4.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/toastr.min.js') ?>"></script>
</head>

<body>

    <?php
    if (isset($content) && $content) {
        $this->load->view($content);
    }
    ?>

</body>

</html>