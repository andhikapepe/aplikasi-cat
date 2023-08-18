<?= isset($message) ? $message : '' ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <?= $judul ?>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= site_url('admin/ujian-tambah') ?>">
                <div class="form-group row">
                    <label for="nama_ujian" class="col-sm-2 col-form-label">Nama Ujian</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Nama Ujian" name="nama_ujian" id="nama_ujian" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mata_uji" class="col-sm-2 col-form-label">Mata Uji</label>
                    <div class="col-sm-10">
                        <select multiple required name="mata_uji[]" id="mata_uji" class="form-control select2-multiple" style="width:100%!important">
                            <option></option>
                            <?php foreach ($dt_mata_uji as $key => $value) {
                                echo '<option value="' . $value['id'] . '">' . $value['mata_uji'] . ' (max '.$value['jml_soal'].' soal)</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jumlah_soal" class="col-sm-2 col-form-label">Jumlah Soal Per Mata Uji</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Jumlah Soal" min="1" name="jumlah_soal" id="jumlah_soal" onkeypress="return isNumberOrCommaKey(event)" required>
                        <label class="text-small text-danger">pisahkan dengan koma (',') jika lebih dari 1 mata uji</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tgl_mulai" class="col-sm-2 col-form-label">Tanggal Mulai Ujian</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" class="form-control" name="tgl_mulai" id="tgl_mulai" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="toleransi_keterlambatan" class="col-sm-2 col-form-label">Toleransi Keterlambatan</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" class="form-control" name="toleransi_keterlambatan" id="toleransi_keterlambatan" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="waktu_ujian" class="col-sm-2 col-form-label">Waktu Ujian</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" placeholder="Waktu ujian dalam menit" min="1" name="waktu_ujian" id="waktu_ujian" onkeypress="return isNumberKey(event)" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jenis_soal" class="col-sm-2 col-form-label">Acak Soal</label>
                    <div class="col-sm-10">
                        <select name="jenis_soal" id="jenis_soal" class="form-control">
                            <option value="" disabled selected>--- Pilih ---</option>
                            <option value="acak">Acak Soal</option>
                            <option value="urut">Urut Soal</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-primary" name="btn-simpan" value="Simpan"></input>
                        <a href="<?= site_url('admin/ujian') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /container -->
<script>
    // Ketika pilihan pada select 'mata_uji' berubah
    $('#mata_uji').on('change', function() {
        const selectedOptions = $(this).val();
        const jumlahSoalValue = (selectedOptions.length > 0) ? '0,'.repeat(selectedOptions.length - 1) + '0' : '';
        $('#jumlah_soal').val(jumlahSoalValue);
    });

    function isNumberOrCommaKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 44) {
            return false;
        }
        return true;
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    $(document).ready(function() {
        $.fn.select2.defaults.set("theme", "bootstrap");
        $(".select2-multiple").select2({
            placeholder: "Pilih Satu Jawaban atau lebih yang sesuai",
            allowClear: true,
            containerCssClass: ':all:',
            dropdownAutoWidth: true,
        })
    })
</script>