<div class="container-fluid">
    <div class="card">
        <div class="card-header justify-content-between">
            <?= $judul ?>
            <div class="float-right">
                <a class="btn btn-sm btn-primary" href="<?= site_url('admin/ujian-tambah') ?>"><i class="fa fa-plus"></i> Tambah</a>
                <button class="btn btn-sm btn-danger" onclick="bulk_delete()"><i class="fa fa-trash"></i> Hapus Masal</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= form_open('admin/ujian-bulk-delete', array('id' => 'bulk')); ?>
                <table id="table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Ujian</th>
                            <th>Mata uji</th>
                            <th>Waktu</th>
                            <th>Acak Soal</th>
                            <th>Token</th>
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
                        ordering: false,
                        processing: true,
                        serverSide: false,
                        searching: true,
                        order: [],
                        ajax: {
                            url: "<?= site_url('admin/ujian-list'); ?>",
                            type: "POST"
                        },
                        columns: [{
                                "data": "nama_ujian"
                            },
                            {
                                "data": "mata_uji"
                            },
                            {
                                "data": "waktu"
                            },
                            {
                                "data": "jenis"
                            },
                            {
                                "data": "token"
                            },
                            {
                                "data": "action"
                            }
                        ],
                        columnDefs: [{
                                targets: 6,
                                data: "id_ujian",
                                render: function(data, type, row, meta) {
                                    return `<div class="text-center">
									<input name="checked[]" class="check" value="${data}" type="checkbox">
								</div>`;
                                }
                            },
                            {
                                targets: 4,
                                data: "token",
                                render: function(data, type, row, meta) {
                                    return `<div class="text-center">
                                    <span class="badge badge-pill badge-info">${data}</span>
								</div>`;
                                }
                            },
                        ]
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