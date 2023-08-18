<div class="container-fluid">
    <div class="card">
        <div class="card-header justify-content-between">
            <?= $judul ?>
            <div class="float-right">
                <button class="btn btn-sm btn-primary" onclick="reload_ajax()"><i class="fa fa-refresh"></i> Refresh Tabel</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Ujian</th>
                            <th>Mata uji</th>
                            <th>Jumlah Soal</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <script>
                $(document).ready(function() {
                    let base_url = '<?= base_url() ?>'
                    let table

                    table = $('#table').DataTable({
                        processing: true,
                        serverSide: false,
                        searching: true,
                        order: [],
                        ajax: {
                            url: "<?= site_url('peserta-uji/ujian-list'); ?>",
                            type: "POST"
                        },
                        columns: [{
                                "data": "nama_ujian"
                            },
                            {
                                "data": "mata_uji"
                            },
                            {
                                "data": "jumlah_soal",
                                "render": function(data, type, row) {
                                    var jumlah_soal_array = data.split(","); // Memisahkan string menjadi array angka
                                    var jumlah_soal_array_int = jumlah_soal_array.map(Number); // Mengubah setiap elemen array menjadi integer
                                    var total_jumlah_soal = jumlah_soal_array_int.reduce(function(a, b) {
                                        return a + b;
                                    }, 0); // Menghitung total nilai dari array
                                    return total_jumlah_soal; // Menampilkan total nilai
                                }
                            },
                            {
                                "data": "waktu"
                            },
                        ],
                        columnDefs: [{
                            "targets": 4,
                            "data": {
                                "id_ujian": "id_ujian",
                                "ada": "ada"
                            },
                            "render": function(data, type, row, meta) {
                                var btn;
                                if (data.ada > 0) {
                                    if (data.status_ujian == 'N') {
                                        btn = `
                                                <a class="btn btn-sm btn-success" href="${base_url}peserta-uji/cetak-hasil-ujian/${data.id_ujian}" target="_blank">
                                                    <i class="fa fa-print"></i> Cetak Hasil Ujian
                                                </a>`;
                                                    } else {
                                                        btn = `
                                                <a class="btn btn-sm btn-danger" href="${base_url}peserta-uji/token/${data.id_ujian}">
                                                    <i class="fa fa-pencil"></i> Lanjutkan Ujian
                                                </a>`;
                                                    }

                                                } else {
                                                    btn = `<a class="btn btn-sm btn-primary" href="${base_url}peserta-uji/token/${data.id_ujian}">
                                                <i class="fa fa-pencil"></i> Ikut Ujian
                                            </a>`;
                                    }
                                    return `<div class="text-center">
                                        ${btn}
                                    </div>`;
                            }
                        }, ],
                    })
                })

                function reload_ajax() {
                    $('#table').DataTable().ajax.reload()
                }
            </script>
        </div>
    </div>
</div>