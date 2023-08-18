<div class="container-fluid">
    <div class="alert alert-info mb-3" role="alert">
        <h4 class="alert-heading">Tata Tertib Ujian</h4>
        <ol>
            <li>
                Peserta Ujian harus menjunjung tinggi integritas dan kejujuran dalam mengikuti Ujian
                yang ditunjukkan dengan Pakta integritas yang telah ditetapkan oleh Panitia Ujian.
            </li>
            <li>
                Peserta Ujian yang terlambat bergabung dan terlambat membuka soal, maka waktu pengerjaan tetap habis sesuai jadwal yang sudah ditetapkan dan tidak ada penambahan waktu.
            </li>
            <li>
                Selama ujian berlangsung, peserta ujian dilarang untuk:
                <ul>
                    <li>Berkomunikasi pribadi dalam bentuk apapun kecuali dengan Panitia Ujian.</li>
                    <li>Memfoto atau merekam tampilan soal.</li>
                    <li>Aktivitas lainnya yang mengganggu sistem Ujian yang mengacu ke Peraturan Perundangan yang berlaku.</li>
                </ul>
            </li>
            <li>
                Apabila terjadi masalah yang mengakibatkan peserta ujian tidak bisa melanjutkan ujian
                karena alasan teknis yang tidak dapat dikendalikan, maka Peserta Ujian segera
                menghubungi Panitia Ujian
            </li>
            <li>
                Pengawas ujian mempunyai wewenang dan tanggungjawab penuh pada waktu pelaksanaan ujian dalam hal:
                <ul>
                    <li>Mencatat kehadiran Peserta Ujian.</li>
                    <li>Memberi teguran dan peringatan kepada Peserta Ujian.</li>
                    <li>Mencatat Nama Peserta Ujian yang melanggar Tata Tertib Ujian.</li>
                </ul>
            </li>
            <li>Peserta Ujian bersedia menerima sanksi dari Pengawas Ujian apabila melanggar peraturan Tata Tertib ini</li>
        </ol>
        <hr>
        <blockquote class="blockquote text-center">
            <p class="mb-0">Semoga berhasil untuk ujiannya! Tetaplah percaya kepada diri sendiri dan kamu pasti akan mencapai setiap tujuanmu.</p>
            <footer class="blockquote-footer"><cite title="Source Title">Panitia Ujian</cite></footer>
        </blockquote>
    </div>
    <div class="card-deck">
        <div class="card" style="max-width: 40rem;">
            <div class="card-header">
                <h6>Informasi Data Ujian</h6>
                <span id="id_ujian" data-key="<?= $encrypted_id ?>"></span>
            </div>
            <div class="card-body">
                <table width="100%" class="table table-striped table-bordered">
                    <tr>
                        <td width="35%">Nama Ujian</td>
                        <td width="5%">:</td>
                        <td><?= $ujian['nama_ujian'] ?></td>
                    </tr>
                    <tr>
                        <td width="35%">Jumlah Soal</td>
                        <td width="5%">:</td>
                        <td>
                            <?php $jumlah_soal_array = explode(",", $ujian['jumlah_soal']);
                            // Langkah 2: Mengubah setiap elemen array menjadi integer
                            $jumlah_soal_array_int = array_map('intval', $jumlah_soal_array);
                            // Langkah 3: Menghitung total nilai
                            $total_jumlah_soal = array_sum($jumlah_soal_array_int);
                            echo $total_jumlah_soal;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%">Mata Uji</td>
                        <td width="5%">:</td>
                        <td>
                            <?php
                            // Assuming you have the mata_uji and jumlah_soal values as separate variables
                            $mata_uji = $ujian['mata_uji'];
                            $jumlah_soal = $ujian['jumlah_soal'];

                            // Explode the mata_uji and jumlah_soal strings into arrays
                            $mata_uji_array = explode(',', $mata_uji);
                            $jumlah_soal_array = explode(',', $jumlah_soal);

                            // Initialize an empty array to store the combined mata_uji and jumlah_soal strings
                            $combined_array = array();

                            // Check if the number of elements in both arrays is the same before combining
                            if (count($mata_uji_array) === count($jumlah_soal_array)) {
                                // Combine the mata_uji and jumlah_soal arrays with the format mata_uji (jumlah soal)
                                for ($i = 0; $i < count($mata_uji_array); $i++) {
                                    $combined_array[] = $mata_uji_array[$i] . ' <span class="badge badge-danger">' . $jumlah_soal_array[$i] . ' Soal</span>';
                                }

                                // Use implode() to concatenate the combined array elements into a single string
                                $result = implode("<br>", $combined_array);

                                // Output the final result
                                echo $result;
                            } else {
                                //echo "Error: The number of elements in mata_uji and jumlah_soal arrays is not the same.";
                                return false;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%">Waktu</td>
                        <td width="5%">:</td>
                        <td><?= waktu($ujian['waktu']) ?> (<?= $ujian['waktu'] ?> Menit)</td>
                    </tr>
                    <tr>
                        <td width="35%">Pelaksanaan Ujian</td>
                        <td width="5%">:</td>
                        <td><?= tgl_indo(date('d F Y H:i:s', strtotime($ujian['tgl_mulai']))) ?> WIB</td>
                    </tr>
                    <tr>
                        <td width="35%">Toleransi Waktu</td>
                        <td width="5%">:</td>
                        <td><?= tgl_indo(date('d F Y H:i:s', strtotime($ujian['terlambat']))) ?> WIB</td>
                    </tr>
                </table>
                <input name="token" class="form-control input-lg" id="token" placeholder="Masukkan Token Disini"></input>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <?php
                $mulai = date('Y-m-d H:i:s', strtotime($ujian['tgl_mulai']));
                $terlambat = date('Y-m-d H:i:s', strtotime($ujian['terlambat']));
                $now = date('Y-m-d H:i:s');
                ?>
                <div id="countdown" class="lead text-center"></div>

                <?php
                if ($mulai > $now) {
                    $message = '<div class="alert alert-info"><h1 class="display-4">Hai, ' . $this->session->userdata('nama') . '!</h1>Sabar ya... Ujian baru bisa dimulai dalam: <strong><span>%D Hari %H Jam %M Menit %S Detik</span></strong> lagi.</div>';
                    $countdown = $mulai;
                ?>
                    <script type="text/javascript">
                        $('#countdown').countdown("<?= $countdown ?>", {
                                elapse: true
                            })
                            .on('update.countdown', function(event) {
                                var $this = $(this);
                                if (event.elapsed) {
                                    $("#countdown").hide();
                                    location.reload()
                                } else {
                                    $this.html(event.strftime(
                                        '<?= $message ?>'
                                    ))
                                }
                            })
                    </script>
                <?php
                } else if ($terlambat > $now) {
                    $message = '<div class="alert alert-warning"><h1 class="display-4">Perhatian!</h1>Batas waktu menekan tombol mulai adalah: <strong><span>%D Hari %H Jam %M Menit %S Detik</span></strong> </br> <button id="btncek" onclick="cek(this)" data-id="' . $ujian['id_ujian'] . '" class="btn btn-success btn-lg btn-block mt-4 mb-4"> <i class="fa fa-pencil"></i> Mulai </button> </div>';
                    $countdown = $terlambat;
                ?>
                    <script type="text/javascript">
                        $('#countdown').countdown("<?= $countdown ?>", {
                                elapse: true
                            })
                            .on('update.countdown', function(event) {
                                var $this = $(this);
                                if (event.elapsed) {
                                    $("#countdown").hide();
                                    location.reload()
                                } else {
                                    $this.html(event.strftime(
                                        '<?= $message ?>'
                                    ))
                                }
                            })

                        function cek(data) {
                            let base_url = '<?= base_url() ?>'
                            let idUjian = data.dataset.id
                            let token = $('#token').val()
                            if (token.length == 0) {
                                swal({
                                    title: "Gagal",
                                    text: "Token belum diisi!",
                                    icon: "error",
                                })
                            } else {
                                let key = $('#id_ujian').data('key');
                                $.ajax({
                                    url: base_url + 'peserta-uji/cek-token/',
                                    type: 'POST',
                                    data: {
                                        id_ujian: idUjian,
                                        token: token
                                    },
                                    cache: false,
                                    success: function(result) {
                                        swal({
                                            icon: result.status ? "success" : "error",
                                            title: result.status ? "Berhasil" : "Gagal",
                                            text: result.status ? "Token Benar, mengarahkan ke halaman ujian" : "Token Salah"
                                        }).then((data) => {
                                            if (result.status) {
                                                location.href = base_url + 'peserta-uji/kerjakan-soal/?key=' + key;
                                            }
                                        })
                                    }
                                });
                            }
                        }
                    </script>
                <?php
                } else {
                    echo '<div class="alert alert-info"><h1 class="display-4">Hemm!</h1>Waktu untuk menekan tombol <strong>"MULAI"</strong> sudah habis.<br/> Silahkan hubungi admin anda untuk bisa mengikuti ujian pengganti/susulan.</div>';
                }

                ?>
            </div>
        </div>
    </div>
</div>