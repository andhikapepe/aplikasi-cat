<?php
if (time() >= $soal->waktu_habis) {
    redirect('peserta-uji/ujian', 'location', 301);
}
?>
<style>
    label {
        display: block;
        position: relative;
        padding-left: 50px;
        padding-top: 10px;
    }

    label input {
        display: none;
    }

    label span {
        /* background-color only for content */
        background-clip: content-box;
        border: 2px solid #bbb;
        background-color: #e7e6e7;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        padding: 5px;
        position: absolute;
        overflow: hidden;
        line-height: 1;
        text-align: center;
        border-radius: 100%;
        font-size: 15pt;
        left: 0;
        top: 20%;

    }

    input:checked+span {
        /* background-color only for content */
        background-clip: content-box;
        border: 2px solid #007bff;
        background-color: #007bff;
        border-radius: 50%;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="card mb-2">
                <div class="card-header">
                    Detail Ujian
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table width="100%" class="table">
                            <tr>
                                <td>Nama Ujian</td>
                                <td width="5%">:</td>
                                <td><?= $ujian['nama_ujian'] ?></td>
                            </tr>
                            <tr>
                                <td>Jumlah Soal</td>
                                <td width="5%">:</td>
                                <td><?= $ujian['jumlah_soal'] ?></td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td width="5%">:</td>
                                <td><?= waktu($ujian['waktu']) ?> (<?= $ujian['waktu'] ?> Menit)</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-2">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>Soal #<span id="soalke"></span></h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mt-2">
                        <?= form_open('', array('id' => 'ujian'), array('id' => $id_tes)) ?>
                        <?= $html ?>
                        <input type="hidden" name="jml_soal" id="jml_soal" value="<?= $no; ?>">
                        <?= form_close() ?>
                    </div>
                    <div class="d-flex justify-content-center" id="btn-navigasi"></div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card mb-2">
                <div class="card-header">Sisa Waktu</div>
                <div class="card-body">
                    <h1 class="text-center"><span class="sisawaktu" data-time="<?= $soal->tgl_selesai ?>"></span></h1>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header">Navigasi Soal</div>
                <div class="card-body text-center">
                    <div id="tampil_jawaban" class="justify-content-around"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-danger btn-block btn-lg" onclick="simpan_akhir()">Selesai Ujian</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let base_url = "<?= base_url() ?>"
    let id_tes = "<?= $id_tes ?>"
    let total_soal = $('#jml_soal').val()

    $(document).ready(function() {
        buka(1)
        simpan_sementara()
    })

    function buka(no_soal) {
        let no_soal_sebelumnya = no_soal - 1
        let no_soal_selanjutnya = no_soal + 1

        let current_soal = $('#soal_' + no_soal).data('nomor')
        cek_status_ragu(no_soal)
        $('#soalke').html(no_soal)
        $('.soal').css('display', 'none')
        $('#soal_' + no_soal).css('display', 'block')

        let btnNav = '<button class="back btn btn-info mr-1" onclick="return buka(' + no_soal_sebelumnya + ');"><i class="fa fa-angle-left "></i> Back</button>' +
            '<button class="ragu btn btn-warning mr-1" onclick="ragu_ragu(' + no_soal + ');">Ragu-ragu</button>' +
            '<button class="next btn btn-info mr-1" onclick="return buka(' + no_soal_selanjutnya + ');"><i class="fa fa-angle-right"></i> Next</button>' +
            '<button class="selesai btn btn-danger mr-1" onclick="simpan_akhir();"><i class="fa fa-stop"></i> Selesai</button>'
        $('#btn-navigasi').html(btnNav)

        if (current_soal == 1) {
            $('.back, .selesai').hide()
        } else if (current_soal == total_soal) {
            $('.next').hide()
        } else {
            $('.back .next .selesai').show()
        }

        simpan()
    }

    function cek_status_ragu(id_soal) {
        let status_ragu = $("#rg_" + id_soal).val();

        if (status_ragu == "N") {
            $(".ragu").html('Ragu')
        } else {
            $(".ragu").html('Tidak Ragu')
        }
    }

    function ragu_ragu(id_soal) {
        let status_ragu = $("#rg_" + id_soal).val()

        if (status_ragu == "N") {
            $("#rg_" + id_soal).val('Y')
            $("#btn_soal_" + id_soal).removeClass('btn-success')
            $("#btn_soal_" + id_soal).addClass('btn-warning')

        } else {
            $("#rg_" + id_soal).val('N');
            $("#btn_soal_" + id_soal).removeClass('btn-warning')
            $("#btn_soal_" + id_soal).addClass('btn-success')
        }
        cek_status_ragu(id_soal)
        simpan()
    }

    function getFormData($form) {
        let unindexed_array = $form.serializeArray()
        let indexed_array = {}
        $.map(unindexed_array, function(n, i) {
            indexed_array[n['name']] = n['value']
        });
        return indexed_array
    }

    function simpan_sementara() {
        let f_asal = $("#ujian")
        let form = getFormData(f_asal)
        let jml_soal = total_soal
        jml_soal = parseInt(jml_soal)

        let hasil_jawaban = ""

        for (let i = 1; i <= jml_soal; i++) {
            let idx = 'opsi_' + i
            let idx2 = 'rg_' + i
            let jawab = form[idx]
            let ragu = form[idx2]

            if (jawab != undefined) {
                if (ragu == "Y") {
                    if (jawab == "-") {
                        hasil_jawaban += '<button id="btn_soal_' + (i) + '" class="btn btn-default btn_soal btn-sm mr-2 mb-2" onclick="return buka(' + (i) + ');">' + (i) + ". " + jawab + "</button>"
                    } else {
                        hasil_jawaban += '<button id="btn_soal_' + (i) + '" class="btn btn-warning btn_soal btn-sm mr-2 mb-2" onclick="return buka(' + (i) + ');">' + (i) + ". " + jawab + "</button>"
                    }
                } else {
                    if (jawab == "-") {
                        hasil_jawaban += '<button id="btn_soal_' + (i) + '" class="btn btn-default btn_soal mr-2 btn-sm mb-2" onclick="return buka(' + (i) + ');">' + (i) + ". " + jawab + "</button>"
                    } else {
                        hasil_jawaban += '<button id="btn_soal_' + (i) + '" class="btn btn-success btn_soal mr-2 btn-sm mb-2" onclick="return buka(' + (i) + ');">' + (i) + ". " + jawab + "</button>"
                    }
                }
            } else {
                hasil_jawaban += '<button id="btn_soal_' + (i) + '" class="btn btn-default btn_soal btn-sm mr-2 mb-2" onclick="return buka(' + (i) + ');">' + (i) + ". -</button>"
            }

            // Menambahkan line break setiap 5 tombol
            if (i % 5 === 0) {
                hasil_jawaban += '<br>'
            }
        }
        $("#tampil_jawaban").html('<div id="yes"></div>' + hasil_jawaban)
    }

    function simpan() {
        simpan_sementara()
        let form = $("#ujian")

        $.ajax({
            type: "POST",
            url: base_url + "peserta-uji/simpan-satu",
            data: form.serialize(),
            dataType: 'json',
        })
    }

    function simpan_akhir() {
        // Display the confirmation dialog
        swal({
            title: "Sudah Yakin?",
            text: "Pastikan mengisi semua jawaban!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((proses) => {
            // If the user confirms
            if (proses) {
                // Call the selesai function
                selesai(function(isComplete, errorMessage) {
                    if (isComplete) {
                        // If selesai is successful, redirect
                        window.location.assign(base_url + 'peserta-uji/ujian');
                    } else {
                        // If selesai fails, display an error message or handle it accordingly
                        console.log('The process is true, but selesai() is false. Error:', errorMessage);
                        swal("Error", errorMessage, "error");
                        // Add any additional logic or error handling as needed
                    }
                });
            } else {
                // If the user cancels
                swal("Yey, lanjutkan ujian...");
            }
        });
    }

    let waktu = $('.sisawaktu').data('time');
    $('.sisawaktu').countdown(waktu, {
            elapse: true
        })
        .on('update.countdown', function(event) {
            let $this = $(this)
            if (event.elapsed) {
                swal("Oops!", "Waktu Ujian Telah Habis!", "info").then((value) => {
                    selesai()
                    window.location.assign(base_url + 'peserta-uji/ujian')
                })
            } else {
                $this.html(event.strftime('%H:%M:%S '))
            }
        })

    function selesai(callback) {
        // Call the simpan function before making the AJAX request
        simpan();

        $.ajax({
            type: "POST",
            url: base_url + "peserta-uji/simpan-akhir",
            data: {
                id: id_tes
            },
            beforeSend: function() {
                // You can perform any actions before the AJAX request if needed
            },
            success: function(response) {
                // Handle the successful response (if needed)
                console.log("POST request successful:", response);
                callback(true, null); // Call the callback function with success flag and no error message
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Log more information about the error
                console.error("POST request failed with status:", jqXHR.status);
                console.error("Error message:", errorThrown);

                // You can also check the responseText for more details if available
                if (jqXHR.responseText) {
                    console.error("Response text:", jqXHR.responseText);
                }

                callback(false, "Error saving data. Please try again."); // Call the callback function with failure flag and error message
            }
        });
    }
</script>