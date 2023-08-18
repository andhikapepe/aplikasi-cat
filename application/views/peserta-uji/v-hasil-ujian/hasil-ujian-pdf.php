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
$total_soal = 0;
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
    $result_mata_uji = implode("<br>", $combined_array);
}

$tbl = '
<h2>Data Ujian</h2>
<table width="100%">
    <tr>
        <td width="30%">Nama Ujian</td>
        <td width="5%">:</td>
        <td  width="65%">' . $nama_ujian . '</td>
    </tr>
    <tr>
        <td width="30%">Mata Uji</td>
        <td width="5%">:</td>
        <td  width="65%">' . $result_mata_uji . '</td>
    </tr>
    <tr>
        <td width="30%">Jumlah Soal</td>
        <td width="5%">:</td>
        <td  width="65%">' . $total_soal . '</td>
    </tr>
    <tr>
        <td width="30%">Nilai terendah</td>
        <td width="5%">:</td>
        <td  width="65%">' . $nilai_min . '</td>
    </tr>
    <tr>
        <td width="30%">Waktu</td>
        <td width="5%">:</td>
        <td  width="65%">' . $waktu . '</td>
    </tr>
    <tr>
        <td width="30%">Nilai tertinggi</td>
        <td width="5%">:</td>
        <td  width="65%">' . $nilai_max . '</td>
    </tr>
    <tr>
        <td width="30%">Tanggal Mulai</td>
        <td width="5%">:</td>
        <td  width="65%">' . $tgl_mulai . '</td>
    </tr>
    <tr>
        <td width="30%">Nilai Rata-rata</td>
        <td width="5%">:</td>
        <td  width="65%">' . $nilai_avg . '</td>
    </tr>
</table>
';

// Assuming $all_nilai_peserta is available and is a sorted array based on 'nilai' key.
// Sort the array
usort($all_nilai_peserta, function ($a, $b) {
    return $b['nilai'] - $a['nilai'];
});

$data_array = $dt_nilai_peserta;

$no = 1;
$previous_score = null;
$rank = 0;
$real_rank = 0;

foreach ($all_nilai_peserta as $peserta) {
    $real_rank++;
    if ($peserta['nilai'] != $previous_score) {  // Jika skor berbeda dengan sebelumnya, peringkat berubah
        $rank = $real_rank;
    }
    // Find the student in $data_array and set his rank
    foreach ($data_array as $index => $row) {
        if ($row['id_peserta_ujian'] == $peserta['id_peserta_ujian']) {
            $data_array[$index]['peringkat'] = $rank;
            break;
        }
    }
    $previous_score = $peserta['nilai'];
}

foreach ($data_array as $index => $row) {
    $tbl .= '
    <h2>Data Peserta</h2>
    <table id="data-peserta">
        <tr>
            <td width="30%">Nama</td>
            <td width="5%">:</td>
            <td width="65%">' . strtoupper($row['nama']) . '</td>
        </tr>
    </table>

    <h2>Hasil Ujian</h2>
    <table>
        <tr>
            <td width="30%">Jawab Benar</td>
            <td width="5%">:</td>
            <td  width="65%">' . $row['jml_benar'] . '</td>
        </tr>
        <tr>
            <td width="30%">Jawab Benar Per Mata Uji</td>
            <td width="5%">:</td>
            <td  width="65%">';

    $list_id_mata_uji = $row['list_id_mata_uji'];
    $list_id_mata_uji_array = explode(',', rtrim($list_id_mata_uji, ','));
    $list_id_mata_uji = array_filter($list_id_mata_uji_array, 'strlen');

    $jml_benar_per_id = $row['jml_benar_per_id'];
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
    $tbl .=  $formattedString;

    $tbl .= ';</td>
        </tr>
        <tr>
            <td width="30%">Nilai</td>
            <td width="5%">:</td>
            <td  width="65%">' . $row['nilai'] . '</td>
        </tr>
        <tr>
            <td width="30%">Peringkat</td>
            <td width="5%">:</td>
            <td  width="65%">' . $data_array[$index]['peringkat'] . '</td>
        </tr>
        <tr>
            <td width="30%">Waktu Mulai</td>
            <td width="5%">:</td>
            <td  width="65%">' . $row['tgl_mulai'] . '</td>
        </tr>
        <tr>
            <td width="30%">Waktu Selesai</td>
            <td width="5%">:</td>
            <td  width="65%">' . $row['tgl_selesai'] . '</td>
        </tr>
    </table>';
}
// echo $tbl;

$pdf->writeHTML($tbl, true, false, true, false, '');

// print a block of text using Write()
//$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// ---------------------------------------------------------
ob_clean();
//Close and output PDF document

$pdf->Output('Laporan hasil ujian ' . $judul . '.pdf', 'I');
