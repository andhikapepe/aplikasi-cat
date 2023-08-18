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

    <link href="<?= base_url('assets/css/jquery.dataTables.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/summernote.min.css') ?>" rel="stylesheet">

    <link href="<?= base_url('assets/css/select2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/select2-bootstrap.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

    <link href="<?= base_url('assets/css/sidebar.css') ?>" rel="stylesheet">
    <!-- core JS -->
    <script src="<?= base_url('assets/js/jquery-3.6.4.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/toastr.min.js') ?>"></script>

    <script src="<?= base_url('assets/js/select2.full.js') ?>"></script>

    <script src="<?= base_url('assets/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/dataTables.bootstrap4.min.js') ?>"></script>

    <script src="<?= base_url('assets/js/sweetalert.min.js') ?>"></script>

    <script src="<?= base_url('assets/js/summernote.min.js') ?>"></script>
</head>

<body>
    <div class="page-wrapper chiller-theme toggled">
        <?php $this->load->view('admin/sidebar-admin'); ?>
        <!-- sidebar-wrapper  -->
        <main class="page-content">
            <?php
            if (isset($content) && $content) {
                $this->load->view($content);
            }
            ?>
            <div class="container-fluid">
                <hr>

                <footer class="text-center">
                    <div class="mb-2">
                        &copy; <?= date('Y') . ' - ' . (isset($_app_name) ? $_app_name : 'Aplikasi CAT') ?>
                    </div>
                </footer>
            </div>
        </main>
        <!-- page-content" -->
    </div>

    <script>
        jQuery(function($) {
            $(".sidebar-dropdown > a").click(function() {
                $(".sidebar-submenu").slideUp(200)
                if ($(this).parent().hasClass("active")) {
                    $(".sidebar-dropdown").removeClass("active")
                    $(this).parent().removeClass("active");
                } else {
                    $(".sidebar-dropdown").removeClass("active")
                    $(this).next(".sidebar-submenu").slideDown(200)
                    $(this).parent().addClass("active")
                }
            })

            $("#close-sidebar").click(function() {
                $(".page-wrapper").removeClass("toggled")
            })

            $("#show-sidebar").click(function() {
                $(".page-wrapper").addClass("toggled")
            })
        })
    </script>
</body>

</html>