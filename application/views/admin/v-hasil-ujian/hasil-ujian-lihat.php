<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <?= $judul ?>
            <div class="float-right">
                <a href="<?= site_url('admin/hasil-ujian') ?>" class="btn btn-sm btn-secondary"><i class="fa fa-undo"></i> Kembali</a>
                <a href="<?= site_url('admin/hasil-ujian-cetak/' . $this->uri->segment(3)) ?>" class="btn btn-sm btn-danger"><i class="fa fa-print"></i> Cetak Hasil Ujian</a>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex">
                <div class="col col-md-6">
                    <table class="table table-stripped">
                        <tr>
                            <td>Nama Ujian</td>
                            <td>:</td>
                            <td><?= $nama_ujian ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Soal</td>
                            <td>:</td>
                            <td>
                                <?php // Mengonversi string menjadi array
                                $jumlah_soal_array = explode(',', $jumlah_soal);

                                // Menggunakan loop untuk menjumlahkan angka-angka dalam array
                                $total = 0;
                                foreach ($jumlah_soal_array as $value) {
                                    $total += (int) trim($value); // Konversi ke integer dan hapus spasi jika ada
                                }
                                echo $total;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Waktu</td>
                            <td>:</td>
                            <td><?= $waktu ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Mulai</td>
                            <td>:</td>
                            <td><?= $tgl_mulai ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col col-md-3">
                    <table class="table table-stripped">
                        <tr>
                            <td>Nilai terendah</td>
                            <td>:</td>
                            <td><?= $nilai_min ?></td>
                        </tr>
                        <tr>
                            <td>Nilai tertinggi</td>
                            <td>:</td>
                            <td><?= $nilai_max ?></td>
                        </tr>
                        <tr>
                            <td>Rata-rata Nilai</td>
                            <td>:</td>
                            <td><?= $nilai_avg ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col col-md-3">
                    <table class="table table-stripped">
                        <tr>
                            <td>Mata Uji</td>
                            <td>:</td>
                            <td><?php
                                // Ubah string menjadi array
                                $arr_mata_uji = explode(",", $mata_uji);
                                // Hapus elemen duplikat
                                $unique_mata_uji = array_unique($arr_mata_uji);
                                // Gabungkan kembali array menjadi string dengan koma sebagai pemisah
                                $dt_mata_uji = implode(",", $unique_mata_uji);

                                $array_mata_uji = explode(",", $dt_mata_uji);
                                $array_jumlah_soal = explode(",", $jumlah_soal);


                                // Pastikan jumlah elemen sama pada kedua array
                                if (count($array_mata_uji) === count($array_jumlah_soal)) {
                                    // Gabungkan array mata uji dengan array jumlah soal menggunakan loop
                                    $result = [];

                                    for ($i = 0; $i < count($array_mata_uji); $i++) {
                                        $result[] = "<li>" . $array_mata_uji[$i] . " <span class='badge badge-danger'>" . $array_jumlah_soal[$i] . ' soal' . "</span></li>";
                                    }

                                    // Gabungkan kembali array hasil dengan koma sebagai pemisah
                                    $dt_mata_uji_jumlah_soal = implode(" ", $result);

                                    // Tampilkan hasilnya dalam bentuk unordered list (ul)
                                    echo "<ol>" . $dt_mata_uji_jumlah_soal . "</ol>";
                                } else {
                                    echo "Error: Jumlah mata uji tidak sesuai dengan jumlah soal.";
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table id="table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah Benar</th>
                            <th>Nilai</th>
                            <th>Peringkat</th>
                            <th>Jawab Benar Per Mata Uji</th>
                        </tr>
                    </thead>
                </table>
                <script>
                    $(document).ready(function() {
                        let table
                        table = $('#table').DataTable({
                            processing: true,
                            serverSide: false,
                            searching: true,
                            order: [],
                            ajax: {
                                url: "<?= site_url('admin/hasil-ujian-lihat-peserta/' . $this->uri->segment(3)) ?>",
                                type: "POST",
                            },
                            columns: [{
                                    "data": "nama"
                                },
                                {
                                    "data": "jml_benar",
                                },
                                {
                                    "data": "nilai",
                                },
                                {
                                    "data": "peringkat",
                                },
                                {
                                    "data": "dt_benar_per_mata_uji",
                                    "render": function(data) {
                                        // Konversi objek JSON menjadi string
                                        const jsonString = JSON.stringify(data);

                                        // Menghapus kurung kurawal '{' dan '}'
                                        const stringWithoutBraces = jsonString.slice(1, -1);

                                        // Pisahkan setiap pasangan key-value
                                        const keyValuePairs = stringWithoutBraces.split(',');

                                        let formattedString = '';

                                        keyValuePairs.forEach((pair, index) => {
                                            const [key, value] = pair.split(':');
                                            const mataUji = key.trim().replace(/"/g, '');
                                            const jumlahSoal = value.trim();
                                            formattedString += `${mataUji} <span class="badge badge-info"> ${jumlahSoal}</span><br>`;
                                        });

                                        return formattedString;
                                    }
                                }

                            ]
                        })
                    })
                </script>
            </div>
        </div>
    </div>
</div>