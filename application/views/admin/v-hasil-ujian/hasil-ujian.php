<div class="container-fluid">
    <div class="card">
        <div class="card-header justify-content-between">
            <?= $judul ?>
            <div class="float-right">
                <button class="btn btn-sm btn-secondary" onclick="reload_ajax()"><i class="fa fa-refresh"></i> Reload Halaman</button>
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
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <script>
                $(document).ready(function() {
                    let table

                    table = $('#table').DataTable({
                        processing: true,
                        serverSide: false,
                        searching: true,
                        order: [],
                        ajax: {
                            url: "<?= site_url('admin/hasil-ujian-list'); ?>",
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
                            {
                                "data": "tgl_mulai"
                            }
                        ],
                        columnDefs: [{
                            targets: 5,
                            data: "id_ujian",
                            render: function(data, type, row, meta) {
                                return `<a href="<?= site_url('admin/hasil-ujian-lihat/') ?>${data}" class="btn btn-danger btn-sm"><i class="fa fa-search"></i> Lihat Hasil</a>`;
                            }
                        }]
                    })
                })

                function reload_ajax() {
                    $('#table').DataTable().ajax.reload()
                }
            </script>
        </div>
    </div>
</div>