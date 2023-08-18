<div class="container-fluid">
    <div class="card">
        <div class="card-header justify-content-between">
            <?= $judul ?>
            <div class="float-right">
                <a class="btn btn-sm btn-primary" href="<?= site_url('admin/bank-soal-tambah') ?>"><i class="fa fa-plus"></i> Tambah</a>
                <a class="btn btn-sm btn-success" href="<?= site_url('admin/bank-soal-import') ?>"><i class="fa fa-upload"></i> Import</a>
                <button class="btn btn-sm btn-danger" onclick="bulk_delete()"><i class="fa fa-trash"></i> Hapus Masal</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= form_open('admin/bank-soal-bulk-delete', array('id' => 'bulk')); ?>
                <table id="table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Mata Uji</th>
                            <th>Soal</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                            <th width="100" class="text-center">
                                <input class="select_all" type="checkbox">
                            </th>
                        </tr>
                    </thead>
                </table>
                <?= form_close() ?>
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
                            url: "<?= site_url('admin/bank-soal-list'); ?>",
                            type: "POST"
                        },
                        columns: [{
                                "data": "mata_uji"
                            },
                            {
                                "data": "soal"
                            },
                            {
                                "data": "created_on",
                                "render" : function(data, type, row) {
                                    // Convert the date to Indonesian format
                                    const datetime = new Date(data);
                                    const options = {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        weekday: 'long',
                                        //timeZone: 'UTC',
                                        //timeZoneName: 'short'
                                    };
                                    const tanggalIndo = datetime.toLocaleDateString('id-ID', options);

                                    // Return the formatted date
                                    return tanggalIndo;
                                }
                            },
                            {
                                "data": "action"
                            }
                        ],
                        columnDefs: [{
                            targets: 4,
                            data: "id_soal",
                            render: function(data, type, row, meta) {
                                return `<div class="text-center">
									<input name="checked[]" class="check" value="${data}" type="checkbox">
								</div>`;
                            }
                        }]
                    })
                })
                $(".select_all").on("click", function() {
                    if (this.checked) {
                        $(".check").each(function() {
                            this.checked = true;
                            $(".select_all").prop("checked", true);
                        });
                    } else {
                        $(".check").each(function() {
                            this.checked = false;
                            $(".select_all").prop("checked", false);
                        });
                    }
                });

                function bulk_delete() {
                    if ($("#table tbody tr .check:checked").length == 0) {
                        swal({
                            title: "Gagal",
                            text: "Tidak ada data yang dipilih",
                            icon: "error",
                        });
                    } else {
                        swal({
                                title: "Anda Yakin?",
                                text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                            .then((willDelete) => {
                                if (willDelete) {
                                    $("#bulk").submit();
                                } else {
                                    swal("Data Aman!");
                                }
                            })
                    }
                }

                $("#bulk").on("submit", function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    $.ajax({
                        url: $(this).attr("action"),
                        data: $(this).serialize(),
                        type: "POST",
                        success: function(respon) {
                            if (respon.status) {
                                swal({
                                    title: "Berhasil",
                                    text: respon.total + " data berhasil dihapus",
                                    icon: "success"
                                })
                                $('.select_all').prop('checked', false);
                            } else {
                                swal({
                                    title: "Gagal",
                                    text: "Tidak ada data yang dipilih",
                                    icon: "error"
                                })
                            }
                            reload_ajax();
                        },
                        error: function() {
                            swal({
                                title: "Gagal",
                                text: "Ada data yang sedang digunakan",
                                icon: "error"
                            })
                        }
                    })
                })

                function reload_ajax() {
                    $('#table').DataTable().ajax.reload()
                }
            </script>
        </div>
    </div>
</div>