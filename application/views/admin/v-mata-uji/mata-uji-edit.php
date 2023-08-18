<?= isset($message) ? $message : '' ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <?= $judul ?>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= site_url('admin/mata-uji-edit/'.$this->uri->segment(3)) ?>">
                <div class="form-group row">
                    <label for="mata_uji" class="col-sm-2 col-form-label">Mata Uji</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Mata Uji" name="mata_uji" id="mata_uji" value="<?= isset($mata_uji) ? $mata_uji : '' ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-primary" name="btn-simpan" value="Simpan"></input>
                        <a href="<?= site_url('admin/mata-uji') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /container -->