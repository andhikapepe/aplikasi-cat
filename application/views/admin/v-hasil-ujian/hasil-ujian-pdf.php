<?php
$this->load->library('Pdf');

// create new PDF document
$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('andhika6@gmail.com');
$pdf->SetTitle($judul . ': ' . strtoupper($nama_ujian));
//$pdf->SetSubject('Subject of the Document');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont('times', '', 10);

//header
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);

// add a page
$pdf->AddPage();

// Explode the mata_uji and jumlah_soal strings into arrays
$mata_uji_array = explode(',', $mata_uji);
$jumlah_soal_array = explode(',', $jumlah_soal);

// Menggunakan loop untuk menjumlahkan angka-angka dalam array
$total = 0;
foreach ($jumlah_soal_array as $value) {
    $total_soal += (int) trim($value); // Konversi ke integer dan hapus spasi jika ada
}

// Initialize an empty array to store the combined mata_uji and jumlah_soal strings
$combined_array = array();

// Check if the number of elements in both arrays is the same before combining
if (count($mata_uji_array) === count($jumlah_soal_array)) {
    // Combine the mata_uji and jumlah_soal arrays with the format mata_uji (jumlah soal)
    for ($i = 0; $i < count($mata_uji_array); $i++) {
        $combined_array[] = $mata_uji_array[$i] . ' (' . $jumlah_soal_array[$i] . ' Soal)';
    }

    // Menggabungkan elemen dalam array dengan tag <li>
    $list_items = implode("</li><li>", $combined_array);

    // Tambahkan tag <ul> untuk membuat daftar tak berurut (unordered list)
    $result_mata_uji = "<ol><li>" . $list_items . "</li></ol>";
}

$tbl = '
<table width="100%">
<tr>
<td width="15%">Nama Ujian</td>
<td width="5%">:</td>
<td width="30%">' . $nama_ujian . '</td>
<td width="15%">Nilai terendah</td>
<td width="5%">:</td>
<td width="30%">' . $nilai_min . '</td>
</tr>
<tr>
<td width="15%">Jumlah Soal</td>
<td width="5%">:</td>
<td width="30%">' . $total_soal . '</td>
<td width="15%">Nilai tertinggi</td>
<td width="5%">:</td>
<td width="30%">' . $nilai_max . '</td>
</tr>
<tr>
<td width="15%">Waktu</td>
<td width="5%">:</td>
<td width="30%">' . $waktu . '</td>
<td width="15%">Nilai Rata-rata</td>
<td width="5%">:</td>
<td width="30%">' . $nilai_avg . '</td>
</tr>
<tr>
<td width="15%">Tanggal Mulai</td>
<td width="5%">:</td>
<td width="30%">' . $tgl_mulai . '</td>
<td width="50%">Mata Uji :';
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
        $result[] = "<li>" . $array_mata_uji[$i] . " (" . $array_jumlah_soal[$i] . ' soal' . ")</li>";
    }
    // Gabungkan kembali array hasil dengan koma sebagai pemisah
    $dt_mata_uji_jumlah_soal = implode(" ", $result);
    // Tampilkan hasilnya dalam bentuk unordered list (ul)
    $tbl .= "<ol>" . $dt_mata_uji_jumlah_soal . "</ol>";
}

$tbl .= '</td>
</tr>
</table><p></p>

';

$tbl .= '<table style="width:100%; line-height: 1.5;" border="1" cellpadding="2">
<thead>
    <tr style="text-align:center; font-weight:bold">
        <td width="10%">No.</td>
        <td>Nama</td>
        <td>Jumlah Benar</td>
        <td>Nilai</td>
        <td>Peringkat</td>
        <td>Jawab Benar Per Mata Uji</td>
    </tr>
</thead>
<tbody>
';

$data_array = $dt_nilai_peserta;

$no = 1;
$rank = 1;
$last_score = null;

foreach ($data_array as $key => &$entry) { // Note the '&' to make the entry referenceable.

    if ($entry['nilai'] != $last_score) {  // jika nilai berbeda dengan nilai sebelumnya, naikkan peringkat
        $rank = $key + 1;
    }
    $entry['peringkat'] = $rank;

    $list_id_mata_uji = $entry['list_id_mata_uji'];
    $list_id_mata_uji_array = explode(',', rtrim($list_id_mata_uji, ','));
    $list_id_mata_uji = array_filter($list_id_mata_uji_array, 'strlen');

    $jml_benar_per_id = $entry['jml_benar_per_id'];
    $jml_benar_per_id_array = explode(',', rtrim($jml_benar_per_id, ','));
    $jml_benar_per_id = array_filter($jml_benar_per_id_array, 'strlen');

    $angka_setelah_titik_dua = array();

    foreach ($jml_benar_per_id as $elemen) {
        $pecah = explode(':', $elemen);
        $angka_setelah_titik_dua[] = $pecah[1];
    }

    $jml_benar_per_id = $angka_setelah_titik_dua;

    $hasil_gabungan = [];

    for ($i = 0; $i < count($list_id_mata_uji); $i++) {
        $dtMata_uji = $this->Main_model->where_data(['id' => $list_id_mata_uji[$i]], 'tbl_mata_uji')->row_array();
        $hasil_gabungan[$i] = $dtMata_uji['mata_uji'] . ":" . $jml_benar_per_id[$i];
    }

    $jumlah_angka_1_per_kunci = [];

    foreach ($hasil_gabungan as $data) {
        list($kunci, $nilai) = explode(":", $data);
        if (!isset($jumlah_angka_1_per_kunci[$kunci])) {
            $jumlah_angka_1_per_kunci[$kunci] = 0;
        }
        $jumlah_angka_1_per_kunci[$kunci] += intval($nilai);
    }

    $entry['dt_benar_per_mata_uji'] = $jumlah_angka_1_per_kunci;

    $tbl .= '<tr>
    <td width="10%" style="text-align:center">' . $no++ . '</td>
    <td>' . $entry['nama'] . '</td>
    <td style="text-align:center">' . $entry['jml_benar'] . '</td>
    <td style="text-align:center">' . $entry['nilai'] . '</td>
    <td style="text-align:center">' . $entry['peringkat'] . '</td>
    <td style="text-align:left">';
    // Ambil data array 'dt_benar_per_mata_uji'
    $dt_benar_per_mata_uji = $entry["dt_benar_per_mata_uji"];
    // Buat variabel untuk menyimpan string hasil
    $formattedString = '';
    // Loop melalui array 'dt_benar_per_mata_uji'
    foreach ($dt_benar_per_mata_uji as $mata_uji => $jumlah_soal) {
        $formattedString .= $mata_uji . " (" . $jumlah_soal . "), ";
    }
    // Hapus koma dan spasi terakhir
    $formattedString = rtrim($formattedString, ', ');
    // Tampilkan hasilnya
    $tbl .=  $formattedString . '</td>
</tr>';
}

$tbl .= '
</tbody>
</table>';

$pdf->writeHTML($tbl, true, false, true, false, '');

// print a block of text using Write()
//$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// ---------------------------------------------------------
ob_clean();
//Close and output PDF document

$pdf->Output('Laporan hasil ujian ' . $judul . '.pdf', 'I');
