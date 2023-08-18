<?= isset($message) ? $message : '' ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <?= $judul ?>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= site_url('admin/bank-soal-tambah') ?>" enctype="multipart/form-data" accept-charset="utf-8">
                <div class="form-group row">
                    <label for="mata_uji" class="col-sm-2 col-form-label">Mata Uji</label>
                    <div class="col-sm-10">
                        <select required name="mata_uji" id="mata_uji" class="form-control select2-single" style="width:100%!important">
                            <option></option>
                            <?php foreach ($dt_mata_uji as $key => $value) {
                                echo '<option value="' . $value['id'] . '">' . $value['mata_uji'] . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="soal" class="col-sm-2 col-form-label">Soal</label>
                    <div class="col-sm-10">
                        <input type="file" name="file_soal" class="form-control mb-2">
                        <textarea name="soal" id="soal" class="form-control summernote"></textarea>
                    </div>
                </div>
                <?php
                $abjad = ['a', 'b', 'c', 'd', 'e'];
                foreach ($abjad as $abj) :
                    $ABJ = strtoupper($abj); // Abjad Kapital
                ?>
                    <div class="form-group row">
                        <label for="jawaban_<?= $abj ?>" class="col-sm-2 col-form-label">Jawaban <?= $ABJ ?></label>
                        <div class="col-sm-10">
                            <input type="file" name="file_<?= $abj ?>" class="form-control mb-2">
                            <textarea name="jawaban_<?= $abj ?>" id="jawaban_<?= $abj ?>" class="form-control summernote"></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="form-group row">
                    <label for="jawaban" class="col-sm-2 col-form-label">Kunci Jawaban</label>
                    <div class="col-sm-10">
                        <select required name="jawaban" id="jawaban" class="form-control select2-single" style="width:100%!important">
                            <option></option>
                            <?php foreach ($abjad as $abj) :
                                $ABJ = strtoupper($abj);
                                echo '<option value="' . $ABJ . '">' . $ABJ . '</option>';
                            endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bobot" class="col-sm-2 col-form-label">Bobot Soal</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" min="1" placeholder="bobot" name="bobot" id="bobot" onkeypress="return isNumberKey(event)" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-primary" name="btn-simpan" value="Simpan"></input>
                        <a href="<?= site_url('admin/bank-soal') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /container -->
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        })

        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2-single").select2({
            placeholder: "Pilih Satu Jawaban",
            allowClear: true,
            containerCssClass: ':all:',
            dropdownAutoWidth: true,
        })
    })

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>