<?= isset($message) ? $message : '' ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <?= $judul ?>
        </div>
        <div class="card-body">
            <div class="alert bg-warning">
                <strong>Catatan!</strong> untuk import data dari file excel, silahkan download templatenya terlebih dahulu.
            </div>
            <form method="POST" action="<?= site_url('admin/data-admin-import') ?>" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="dtfile" class="col-sm-2 col-form-label">Download Template</label>
                    <div class="col-sm-10">
                        <a href="<?= site_url('template/pengguna.xlsx') ?>" class="btn btn-primary">Template Data Admin (.xlsx)</a>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dtfile" class="col-sm-2 col-form-label">Pilih File</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" placeholder="Pilih File" name="upload_file" id="dtfile" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-success" name="btn-preview" value="Preview"></input>
                        <a href="<?= site_url('admin/data-admin') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
            <?php if (isset($_POST['btn-preview'])) : ?>
                <br>
                <h4>Preview Data</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Username</td>
                            <td>Nama</td>
                            <td>Password</td>
                            <td>Tempat Lahir</td>
                            <td>Tanggal Lahir</td>
                            <td>Jenis Kelamin</td>
                            <td>Alamat</td>
                            <td>No telp</td>
                            <td>Pendidikan terakhir</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $status = true;
                        if (empty($import)) {
                            echo '<tr><td colspan="3" class="text-center">Data kosong! pastikan anda menggunakan format yang telah disediakan.</td></tr>';
                        } else {
                            $no = 1;
                            foreach ($import as $data) :
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="<?= $data['username'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['username'] == null ? 'BELUM DIISI' : $data['username']; ?>
                                    </td>
                                    <td class="<?= $data['nama'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['nama'] == null ? 'BELUM DIISI' : $data['nama']; ?>
                                    </td>
                                    <td class="<?= $data['password'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['password'] == null ? 'BELUM DIISI' : $data['password']; ?>
                                    </td>
                                    <td class="<?= $data['tempat_lahir'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['tempat_lahir'] == null ? 'BELUM DIISI' : $data['tempat_lahir']; ?>
                                    </td>
                                    <td class="<?= $data['tanggal_lahir'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['tanggal_lahir'] == null ? 'BELUM DIISI' : $data['tanggal_lahir']; ?>
                                    </td>
                                    <td class="<?= $data['jenis_kelamin'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['jenis_kelamin'] == null ? 'BELUM DIISI' : $data['jenis_kelamin']; ?>
                                    </td>
                                    <td class="<?= $data['alamat'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['alamat'] == null ? 'BELUM DIISI' : $data['alamat']; ?>
                                    </td>
                                    <td class="<?= $data['no_telp'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['no_telp'] == null ? 'BELUM DIISI' : $data['no_telp']; ?>
                                    </td>
                                    <td class="<?= $data['pendidikan_terakhir'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['pendidikan_terakhir'] == null ? 'BELUM DIISI' : $data['pendidikan_terakhir']; ?>
                                    </td>
                                </tr>
                        <?php
                                if ($data['username'] == null || $data['nama'] == null || $data['password'] == null|| $data['tempat_lahir'] == null|| $data['tanggal_lahir'] == null|| $data['jenis_kelamin'] == null|| $data['alamat'] == null|| $data['no_telp'] == null|| $data['pendidikan_terakhir'] == null) {
                                    $status = false;
                                }
                            endforeach;
                        }
                        ?>
                    </tbody>
                </table>
                <?php if ($status) : ?>

                    <?= form_open('admin/data-admin-do-import', null, ['data' => json_encode($import)]); ?>
                    <button type='submit' class='btn btn-block btn-danger'><i class="fa fa-upload"></i> Import</button>
                    <?= form_close(); ?>

                <?php endif; ?>
                <br>
            <?php endif; ?>

        </div>
    </div>
</div> <!-- /container -->