<?php
defined('BASEPATH') or exit('No direct script access allowed');

function cekLogin()
{
	# code untuk cek login
	$_ci = &get_instance();
	if (!$_ci->session->has_userdata('is_Logged')) {
		redirect('auth', 'refresh');
	}
}

function cekIsAdmin()
{
	# code untuk cek is admin
	$_ci = &get_instance();
	if (!$_ci->session->userdata('is_admin') == TRUE) {
		show_error('Kembalilah Kejalan Yang Benar!');
	}
}

function cekIsUser()
{
	# code untuk cek is admin
	$_ci = &get_instance();
	if (!$_ci->session->userdata('is_admin') == FALSE) {
		show_error('Kembalilah Kejalan Yang Benar!');
	}
}


function tampil_media($file, $width = "", $height = "")
{
	$ret = '';

	$pc_file = explode(".", $file);
	$eks = end($pc_file);

	$eks_video = array("mp4", "flv", "mpeg");
	$eks_audio = array("mp3", "acc");
	$eks_image = array("jpeg", "jpg", "gif", "bmp", "png");


	if (!in_array($eks, $eks_video) && !in_array($eks, $eks_audio) && !in_array($eks, $eks_image)) {
		$ret .= '';
	} else {
		if (in_array($eks, $eks_video)) {
			if (is_file("./" . $file)) {
				$ret .= '<p><video width="' . $width . '" height="' . $height . '" controls>
                <source src="' . base_url() . $file . '" type="video/mp4">
                <source src="' . base_url() . $file . '" type="application/octet-stream">Browser tidak support</video></p>';
			} else {
				$ret .= '';
			}
		}

		if (in_array($eks, $eks_audio)) {
			if (is_file("./" . $file)) {
				$ret .= '<p><audio width="' . $width . '" height="' . $height . '" controls>
				<source src="' . base_url() . $file . '" type="audio/mpeg">
				<source src="' . base_url() . $file . '" type="audio/wav">Browser tidak support</audio></p>';
			} else {
				$ret .= '';
			}
		}

		if (in_array($eks, $eks_image)) {
			if (is_file("./" . $file)) {
				$ret .= '<img class="thumbnail w-100" src="' . base_url() . $file . '" style="width: ' . $width . '; height: ' . $height . ';">';
			} else {
				$ret .= '';
			}
		}
	}


	return $ret;
}

//--------------------------konversi waktu----------------------------
function waktu($jumlahMenit)
{
	$jam = floor($jumlahMenit / 60); // Mendapatkan jumlah jam
	$menit = $jumlahMenit % 60; // Mendapatkan jumlah menit

	// Format output
	$hasil = "";
	if ($jam > 0) {
		$hasil .= $jam . " jam ";
	}
	if ($menit > 0) {
		$hasil .= $menit . " menit";
	}

	return $hasil;
}

//--------------------------konversi tanggal indo----------------------------
function tgl_indo($tanggal) {
    $namaBulan = array(
        'January'   => 'Januari',
        'February'  => 'Februari',
        'March'     => 'Maret',
        'April'     => 'April',
        'May'       => 'Mei',
        'June'      => 'Juni',
        'July'      => 'Juli',
        'August'    => 'Agustus',
        'September' => 'September',
        'October'   => 'Oktober',
        'November'  => 'November',
        'December'  => 'Desember'
    );

    $englishMonth = date('F', strtotime($tanggal));
    $indonesianMonth = $namaBulan[$englishMonth];

    $tanggalIndonesia = date('d', strtotime($tanggal)) . ' ' . $indonesianMonth . ' ' . date('Y H:i:s', strtotime($tanggal));
    
    return $tanggalIndonesia;
}

