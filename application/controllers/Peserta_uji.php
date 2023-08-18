<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Peserta_uji extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		cekLogin();
		cekIsUser();
	}

	public function index()
	{
		if ($this->session->userdata('is_admin') == FALSE) {
			redirect('peserta-uji/ujian');
		} else {
			show_error('Kembalilah kejalan yang benar!');
		}
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	/**
	 * untuk ujian
	 */
	public function ujian()
	{
		$this->data['title'] = 'Jadwal Ujian';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'peserta-uji/v-ujian/ujian';
		$this->template->_render_page('peserta-uji/main-page', $this->data);
	}

	public function ujian_list()
	{
		$uuid = $this->session->userdata('uuid');
		$where = ['uuid' => $uuid];
		$query = $this->Main_model->where_data($where, 'tbl_pengguna');
		if ($query->num_rows()) {
			$dt = $query->row_array();
			$id = $dt['id'];
			echo $this->output_json($this->Main_model->getDataUjian_peserta_uji($id), false);
		} else {
			show_404();
		}
	}

	public function cetak_hasil_ujian()
	{
		$id = $this->uri->segment(3);
		$query = $this->Main_model->getHasilUjian_whereID_ujian($id);
		if ($query->num_rows()) {
			//show_error($this->db->last_query());
			$dt = $query->row_array();
			$data['nama_ujian'] = $dt['nama_ujian'];
			$data['jumlah_soal'] = $dt['jumlah_soal'];
			$data['waktu'] = $dt['waktu'];
			$data['tgl_mulai'] = $dt['tgl_mulai'];
			$data['mata_uji'] = $dt['mata_uji'];
			$data['nilai_min'] = $dt['nilai_min'];
			$data['nilai_max'] = $dt['nilai_max'];
			$data['nilai_avg'] = $dt['nilai_avg'];
			$uuid = $this->session->userdata('uuid');
			$where = ['uuid' => $uuid];
			$query_p = $this->Main_model->where_data($where, 'tbl_pengguna');
			if ($query_p->num_rows()) {
				$dt = $query_p->row_array();
				$id_peserta_ujian = $dt['id'];
				$data['all_nilai_peserta'] = $this->Main_model->getHasilUjian_whereID_ujian_nama($id)->result_array();
				$data['dt_nilai_peserta'] = $this->Main_model->getHasilUjian_peserta_uji($id, $id_peserta_ujian)->result_array();
			}
		} else {
			show_404();
		}

		$data['title'] = 'Laporan Hasil Ujian';
		$data['judul'] = $data['title'];
		$this->load->view('peserta-uji/v-hasil-ujian/hasil-ujian-pdf', $data);
	}

	public function token($id = null)
	{
		if (empty($this->uri->segment(3)) || empty($id)) {
			show_404();
		} else {
			$this->load->library('encryption');
			$this->data['encrypted_id'] = urlencode($this->encryption->encrypt($id));
			$this->data['ujian'] = $this->Main_model->getDtUjian($id)->row_array();

			$this->data['title'] = 'Token Ujian';
			$this->data['judul'] = $this->data['title'];
			$this->data['content'] = 'peserta-uji/v-main-ujian/token';
			$this->template->_render_page('peserta-uji/v-main-ujian/main-page', $this->data);
		}
	}

	public function cek_token()
	{
		$id = $this->input->post('id_ujian', true);
		$token = $this->input->post('token', true);

		$where = ['id_ujian' => $id];

		$row = $this->Main_model->where_data($where, 'tbl_jadwal_ujian')->row_array();
		if (isset($row['id_ujian']) && !empty($id)) {
			$dt_token = $row['token'];
			$data['status'] = $token === $dt_token ? TRUE : FALSE;
		} else {
			$data['status'] = FALSE;
		}
		$this->output_json($data);
	}

	public function kerjakan_soal()
	{
		$this->load->library('encryption');
		$key = $this->input->get('key', true);

		$peserta_uji = $this->Main_model->where_data(['uuid' => $this->session->userdata('uuid')], 'tbl_pengguna')->row_array();

		$id  = $this->encryption->decrypt(rawurldecode($key)); //id ujian
		$h_ujian 	= $this->Main_model->hasil_ujian($id, $peserta_uji['id']);

		if (empty($key) || empty($id)) {
			show_404();
		}

		$cek_sudah_ikut = $h_ujian->num_rows();
		$ujian = $this->Main_model->where_data(['id_ujian' => $id], 'tbl_jadwal_ujian')->row();

		$arr_id_mata_uji = $ujian->id_mata_uji;

		// Mengonversi string menjadi array
		$id_mata_uji_array = explode(',', $arr_id_mata_uji);

		// Menghitung jumlah elemen dalam array
		$jumlahElemen_id_mata_uji = count($id_mata_uji_array);

		$id_mata_uji = explode(',', $arr_id_mata_uji);
		$limits = explode(',', $ujian->jumlah_soal);

		$order = ($ujian->jenis === "acak") ? 'rand()' : 'id_soal';

		$soal = array();

		for ($i = 0; $i < $jumlahElemen_id_mata_uji; $i++) {
			$id_value = $id_mata_uji[$i];
			$limit_value = $limits[$i];
			// Fetch questions from the database using the Main_model's getSoal() method
			$data = $this->Main_model->getSoal($id_value, $order, $limit_value);
			// Add the fetched questions into the $soal array
			$soal = array_merge($soal, $data);
		}
		if ($cek_sudah_ikut < 1) {
			$soal_urut_ok 	= array();
			$i = 0;
			foreach ($soal as $s) {
				$soal_per = new stdClass();
				$soal_per->id_soal 		= $s->id_soal;
				$soal_per->soal 		= $s->soal;
				$soal_per->file 		= $s->file_soal;
				$soal_per->tipe_file 	= $s->tipe_file;
				$soal_per->mata_uji_id 	= $s->mata_uji_id; //-->
				$soal_per->opsi_a 		= $s->opsi_a;
				$soal_per->opsi_b 		= $s->opsi_b;
				$soal_per->opsi_c 		= $s->opsi_c;
				$soal_per->opsi_d 		= $s->opsi_d;
				$soal_per->opsi_e 		= $s->opsi_e;
				$soal_per->jawaban 		= $s->jawaban;
				$soal_urut_ok[$i] 		= $soal_per;
				$i++;
			}
			$soal_urut_ok 	= $soal_urut_ok;
			$list_id_soal	= "";
			$list_jwb_soal 	= "";
			$list_id_mata_uji 	= "";
			if (!empty($soal)) {
				foreach ($soal as $d) {
					$list_id_soal .= $d->id_soal . ",";
					$list_jwb_soal .= $d->id_soal . "::N,";
					$list_id_mata_uji .= $d->mata_uji_id . ","; //-->
				}
			}
			$list_id_soal 	= substr($list_id_soal, 0, -1);
			$list_jwb_soal 	= substr($list_jwb_soal, 0, -1);
			$waktu_selesai 	= date('Y-m-d H:i:s', strtotime("+{$ujian->waktu} minute"));
			$time_mulai		= date('Y-m-d H:i:s');

			$input = [
				'id_ujian' 		=> $id,
				'id_peserta_ujian'	=> $peserta_uji['id'],
				'list_soal'		=> $list_id_soal,
				'list_jawaban' 	=> $list_jwb_soal,
				'list_id_mata_uji' => $list_id_mata_uji, //-->
				'jml_benar'		=> 0,
				'nilai'			=> 0,
				'nilai_bobot'	=> 0,
				'tgl_mulai'		=> $time_mulai,
				'tgl_selesai'	=> $waktu_selesai,
				'status'		=> 'Y'
			];
			$this->Main_model->batch_insert('tbl_hasil_ujian', $input);

			// Setelah insert wajib refresh dulu
			redirect('peserta-uji/kerjakan-soal/?key=' . urlencode($key), 'location', 301);
		} else {

			$q_soal = $h_ujian->row();

			$urut_soal 		= explode(",", $q_soal->list_jawaban);
			$soal_urut_ok	= array();
			for ($i = 0; $i < sizeof($urut_soal); $i++) {
				$pc_urut_soal	= explode(":", $urut_soal[$i]);
				$pc_urut_soal1 	= empty($pc_urut_soal[1]) ? "''" : "'{$pc_urut_soal[1]}'";
				$ambil_soal 	= $this->Main_model->ambilSoal($pc_urut_soal1, $pc_urut_soal[0]);
				$soal_urut_ok[] = $ambil_soal;
			}

			$detail_tes = $q_soal;
			$soal_urut_ok = $soal_urut_ok;

			$pc_list_jawaban = explode(",", $detail_tes->list_jawaban);
			$arr_jawab = array();
			foreach ($pc_list_jawaban as $v) {
				$pc_v 	= explode(":", $v);
				$idx 	= $pc_v[0];
				$val 	= $pc_v[1];
				$rg 	= $pc_v[2];

				$arr_jawab[$idx] = array("j" => $val, "r" => $rg);
			}

			$arr_opsi = array("a", "b", "c", "d", "e");
			$html = '';
			$no = 1;
			if (!empty($soal_urut_ok)) {
				foreach ($soal_urut_ok as $s) {
					$path = 'uploads/bank-soal/';
					$vrg = $arr_jawab[$s->id_soal]["r"] == "" ? "N" : $arr_jawab[$s->id_soal]["r"];
					$html .= '<div class="soal" id="soal_' . $no . '" data-nomor="' . $no . '" style="display: none;">';
					$html .= '<input type="hidden" name="id_soal_' . $no . '" value="' . $s->id_soal . '">'; //id_soal
					$html .= '<input type="hidden" name="rg_' . $no . '" id="rg_' . $no . '" value="' . $vrg . '">'; //ragu-ragu
					if (!empty($s->file_soal)) {
						$html .= '<div class="text-center">';
						$html .= '<div class="w-25">' . tampil_media($path . $s->file_soal) . '</div>';
						$html .= '</div>';
					}
					$html .= $s->soal;
					for ($j = 0; $j < $this->config->item('jml_opsi'); $j++) {
						$opsi 			= "opsi_" . $arr_opsi[$j];
						$file 			= "file_" . $arr_opsi[$j];
						$checked 		= $arr_jawab[$s->id_soal]["j"] == strtoupper($arr_opsi[$j]) ? "checked" : "";
						$pilihan_opsi 	= !empty($s->$opsi) ? $s->$opsi : "";
						$tampil_media_opsi = (is_file(base_url() . $path . $s->$file) || $s->$file != "") ? tampil_media($path . $s->$file) : "";
						$html .= '<div class="card mb-2" onclick="return simpan_sementara();">';
						$html .= '<div class="card-body">';
						$html .= '<label>';
						$html .= '<input type="radio" id="opsi_' . strtolower($arr_opsi[$j]) . '_' . $s->id_soal . '" name="opsi_' . $no . '" value="' . strtoupper($arr_opsi[$j]) . '" ' . $checked . ' />';
						$html .= '<span class="huruf_opsi">' . strtoupper($arr_opsi[$j]) . '</span> ' . $pilihan_opsi;
						if (!empty($tampil_media_opsi)) {
							$html .= '<div class="w-25">' . $tampil_media_opsi . '</div>';
						}
						$html .= '</label>';
						$html .= '</div>';
						$html .= '</div>';
					}
					$html .= '</div>';
					$no++;
				}
			}

			// Enkripsi Id Tes
			$id_tes = $this->encryption->encrypt($detail_tes->id);
			//show_error($detail_tes->id);
			$this->data = [
				'soal'		=> $detail_tes,
				'no' 		=> $no - 1,
				'html' 		=> $html,
				'id_tes'	=> $id_tes
			];
		}

		$this->data['ujian'] = $this->Main_model->where_data(['id_ujian' => $id], 'tbl_jadwal_ujian')->row_array();

		$this->data['title'] = 'Kerjakan Soal';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'peserta-uji/v-main-ujian/sheet';
		$this->template->_render_page('peserta-uji/v-main-ujian/main-page', $this->data);
	}

	public function simpan_satu()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);

		$input 	= $this->input->post(null, true);
		$list_jawaban 	= "";
		for ($i = 1; $i <= $input['jml_soal']; $i++) {
			$_tjawab 	= "opsi_" . $i;
			$_tidsoal 	= "id_soal_" . $i;
			$_ragu 		= "rg_" . $i;
			$jawaban_ 	= empty($input[$_tjawab]) ? "" : $input[$_tjawab];
			$list_jawaban	.= "" . $input[$_tidsoal] . ":" . $jawaban_ . ":" . $input[$_ragu] . ",";
		}
		$list_jawaban	= substr($list_jawaban, 0, -1);
		$d_simpan = [
			'list_jawaban' => $list_jawaban
		];

		// Simpan jawaban
		$this->Main_model->batch_update('tbl_hasil_ujian', $d_simpan, 'id', $id_tes);
		$this->output_json(['status' => true]);
	}

	public function simpan_akhir()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);

		// Get Jawaban dari kolom list jawaban
		$list_jawaban = $this->Main_model->getJawaban($id_tes)->list_jawaban;

		// Pecah Jawaban
		$pc_jawaban = explode(",", $list_jawaban);

		$jumlah_benar = 0;
		$jumlah_salah = 0;
		$jumlah_ragu = 0;
		$nilai_bobot = 0;
		$total_bobot = 0;
		$jumlah_soal = sizeof($pc_jawaban);

		$jumlah_benar_per_id = array();

		foreach ($pc_jawaban as $jwb) {
			$pc_dt = explode(":", $jwb);
			$id_soal = $pc_dt[0];
			$jawaban = $pc_dt[1];
			$jawaban_per_id = $pc_dt[1];
			$ragu = $pc_dt[2];

			$cek_jwb = $this->Main_model->where_data(['id_soal' => $id_soal], 'tbl_soal')->row();
			$total_bobot = $total_bobot + $cek_jwb->bobot;

			$jawaban == $cek_jwb->jawaban ? $jumlah_benar++ : $jumlah_salah++;

			if (!isset($jumlah_benar_per_id[$id_soal])) {
				$jumlah_benar_per_id[$id_soal] = 0;
			}

			$jawaban_per_id == $cek_jwb->jawaban ? $jumlah_benar_per_id[$id_soal]++ : $jumlah_salah++;
		}

		$nilai = ($jumlah_benar / $jumlah_soal) * 100;
		$nilai_bobot = ($total_bobot / $jumlah_soal) * 100;

		// Menggabungkan jumlah benar per ID menjadi string dengan tanda pemisah koma
		$jml_benar_per_id = array();
		foreach ($jumlah_benar_per_id as $id_mata_uji => $total_benar_per_id) {
			$jml_benar_per_id[] = $id_mata_uji . ':' . $total_benar_per_id;
		}
		$jml_benar_per_id_str = implode(",", $jml_benar_per_id);

		$d_update = [
			'jml_benar' => $jumlah_benar,
			'nilai' => number_format(floor($nilai), 0),
			'nilai_bobot' => number_format(floor($nilai_bobot), 0),
			'status' => 'N',
			'jml_benar_per_id' => $jml_benar_per_id_str
		];

		$this->Main_model->batch_update('tbl_hasil_ujian', $d_update, 'id', $id_tes);
	}
}
