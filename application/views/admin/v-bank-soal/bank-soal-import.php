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
            <form method="POST" action="<?= site_url('admin/bank-soal-import') ?>" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="dtfile" class="col-sm-2 col-form-label">Download Template</label>
                    <div class="col-sm-10">
                        <a href="<?= site_url('template/bank-soal.xlsx') ?>" class="btn btn-primary">Template Bank Soal (.xlsx)</a>
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
                        <a href="<?= site_url('admin/bank-soal') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
            <?php if (isset($_POST['btn-preview'])) : ?>
                <br>
                <h4>Preview Data</h4>
                <div class="alert alert-warning" role="alert">
                    Untuk menambahkan gambar pada soal atau opsi jawaban silahkan gunakan fitur <button class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button> di halaman <a href="<?= site_url('admin/bank-soal') ?>">Bank Soal</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Soal</td>
                            <td>Opsi A</td>
                            <td>Opsi B</td>
                            <td>Opsi C</td>
                            <td>Opsi D</td>
                            <td>Opsi E</td>
                            <td>Jawaban</td>
                            <td>Bobot Soal</td>
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
                                    <td class="<?= $data['soal'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['soal'] == null ? 'BELUM DIISI' : $data['soal']; ?>
                                    </td>
                                    <td class="<?= $data['opsi_a'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['opsi_a'] == null ? 'BELUM DIISI' : $data['opsi_a'];; ?>
                                    </td>
                                    <td class="<?= $data['opsi_b'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['opsi_b'] == null ? 'BELUM DIISI' : $data['opsi_b'];; ?>
                                    </td>
                                    <td class="<?= $data['opsi_c'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['opsi_c'] == null ? 'BELUM DIISI' : $data['opsi_c'];; ?>
                                    </td>
                                    <td class="<?= $data['opsi_d'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['opsi_d'] == null ? 'BELUM DIISI' : $data['opsi_d'];; ?>
                                    </td>
                                    <td class="<?= $data['opsi_e'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['opsi_e'] == null ? 'BELUM DIISI' : $data['opsi_e'];; ?>
                                    </td>
                                    <td class="<?= $data['jawaban'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['jawaban'] == null ? 'BELUM DIISI' : $data['jawaban'];; ?>
                                    </td>
                                    <td class="<?= $data['bobot_soal'] == null ? 'bg-danger' : ''; ?>">
                                        <?= $data['bobot_soal'] == null ? 'BELUM DIISI' : $data['bobot_soal'];; ?>
                                    </td>
                                </tr>
                        <?php
                                if ($data['soal'] == null || $data['opsi_a'] == null || $data['opsi_b'] == null || $data['opsi_c'] == null || $data['opsi_d'] == null || $data['opsi_e'] == null || $data['jawaban'] == null || $data['bobot_soal'] == null) {
                                    $status = false;
                                }
                            endforeach;
                        }
                        ?>
                    </tbody>
                </table>
                <?php if ($status) : ?>
                    <?= form_open('admin/bank-soal-do-import', null, ['data' => json_encode($import)]); ?>
                    <hr>
                    <div class="form-group row">
                        <label for="mata_uji" class="col-sm-2 col-form-label">Pilih Mata Uji</label>
                        <div class="col-sm-10">
                            <select required name="mata_uji" id="mata_uji" class="form-control select2-single" style="width:100%!important">
                                <option></option>
                                <?php foreach ($dt_mata_uji as $key => $value) {
                                    echo '<option value="' . $value['id'] . '">' . $value['mata_uji'] . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <button type='submit' class='btn btn-block btn-danger'><i class="fa fa-upload"></i> Import</button>
                    <?= form_close(); ?>
                <?php endif; ?>
                <br>
            <?php endif; ?>

        </div>
    </div>
</div> <!-- /container -->

<script>
    $(document).ready(function() {
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2-single").select2({
            placeholder: "Pilih Satu Jawaban",
            allowClear: true,
            containerCssClass: ':all:',
            dropdownAutoWidth: true,
        });
    });
</script>