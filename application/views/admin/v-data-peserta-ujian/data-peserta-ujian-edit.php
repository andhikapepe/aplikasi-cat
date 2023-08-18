<?= isset($message) ? $message : '' ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <?= $judul ?>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= site_url('admin/data-peserta-ujian-edit/' . $this->uri->segment(3)) ?>" enctype="multipart/form-data" accept-charset="utf-8">
                <div class="form-group row">
                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Username" name="username" id="username" value="<?= isset($username) ? $username : '' ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Nama" required name="nama" id="nama" value="<?= isset($nama) ? $nama : '' ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" minlength="5" placeholder="Masukkan Kata Sandi" id="password">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="tempat lahir" required name="tempat_lahir" id="tempat_lahir" value="<?= isset($tempat_lahir) ? $tempat_lahir : '' ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" placeholder="tanggal lahir" required name="tanggal_lahir" id="tanggal_lahir" value="<?= isset($tanggal_lahir) ? $tanggal_lahir : '' ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki_laki" value="L" <?= (isset($jenis_kelamin) && $jenis_kelamin == "L") ? "checked" : ""; ?>>
                            <label class="form-check-label" for="laki_laki">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P" <?= (isset($jenis_kelamin) && $jenis_kelamin == "P") ? "checked" : ""; ?>>
                            <label class="form-check-label" for="perempuan">Perempuan</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="5" required><?= isset($alamat) ? $alamat : '' ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="no_telp" class="col-sm-2 col-form-label">Nomor Telp.</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Nomor Telp / Handphone" required name="no_telp" id="no_telp" value="<?= isset($no_telp) ? $no_telp : '' ?>" onkeypress="return isNumberKey(event)" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pendidikan_terakhir" class="col-sm-2 col-form-label">Pendidikan Terakhir</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="SD / SMP / SMA / Diploma / Sarjana" required name="pendidikan_terakhir" id="pendidikan_terakhir" value="<?= isset($pendidikan_terakhir) ? $pendidikan_terakhir : '' ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="foto" class="col-sm-2 col-form-label">foto</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" placeholder="Pilih File" name="upload_file" id="foto">
                        <?php if (!empty($foto)) : ?>
                            <div class="w-50 mb-2">
                                <?= tampil_media('uploads/foto-pengguna/' . $foto); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-primary" name="btn-simpan" value="Simpan"></input>
                        <a href="<?= site_url('admin/data-peserta-ujian') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /container -->