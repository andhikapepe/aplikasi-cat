<nav class="navbar navbar-expand-md navbar-dark bg-primary mb-4 p-4 justify-content-between">
    <a class="navbar-brand" href="<?= site_url('peserta-uji') ?>"><?= $_app_name; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <a class="nav-link" href="<?= site_url('auth/logout') ?>"><i class="fa fa-sign-out"></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>