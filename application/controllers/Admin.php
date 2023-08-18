<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		cekLogin();
		cekIsAdmin();
		//$this->uuid->v4();
	}

	public function index()
	{
		if ($this->session->userdata('is_admin') == TRUE) {
			redirect('admin/dashboard');
		} else {
			show_error('Kembalilah kejalan yang benar!');
		}
	}

	/**
	 * Untuk Dashboard
	 */
	public function dashboard()
	{
		$this->data['title'] = 'Dashboard Admin';
		$this->data['content'] = 'admin/dashboard-admin';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	/**
	 * untuk akun
	 */
	public function akun()
	{
		$uuid = $this->session->userdata('uuid');
		$where = ['uuid' => $uuid];

		$row = $this->Main_model->where_data($where, 'tbl_pengguna')->row_array();
		if (isset($row['id'])) {
			$this->form_validation->set_rules('username', 'username', 'trim|required');
			$this->form_validation->set_rules('nama', 'nama', 'trim|required');
			$this->form_validation->set_rules('password', 'Kata Sandi', 'trim');
			$this->form_validation->set_rules('passconf', 'Konfirmasi Kata Sandi', 'trim|matches[password]');

			if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
				$username = $this->input->post('username', true);
				$nama = $this->input->post('nama', true);
				$password = $this->input->post('password', true);

				$data = [
					'username'      => $username,
					'nama'         => $nama,
				];
				$options = [
					'cost' => 12,
				];
				if ($password) {
					$data['password'] = password_hash($password, PASSWORD_DEFAULT, $options);
				}

				if ($this->Main_model->update_data($where, $data, 'tbl_pengguna')) {
					$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
						window.setTimeout(function() {
							window.location = "' . base_url('admin/akun') . '";
						}, 1500)
						</script>';
					$this->session->set_userdata($data);
				}
			} else {
				$this->data['username'] =  $this->form_validation->set_value('username', $row['username']);
				$this->data['nama'] = $this->form_validation->set_value('nama', $row['nama']);
				$this->data['password'] = $this->form_validation->set_value('password');
				$this->data['passconf'] = $this->form_validation->set_value('passconf');
			}

			$this->data['title'] = 'Pengaturan Akun';
			$this->data['judul'] = $this->data['title'];
			$this->data['content'] = 'admin/akun';
			$this->template->_render_page('admin/main-page', $this->data);
		} else {
			show_404();
		}
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	/**
	 * Untuk data Admin
	 */

	public function data_admin()
	{
		$this->data['title'] = 'Data Admin';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-data-admin/data-admin';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function data_admin_list()
	{
		$where = ['is_admin' => 1];
		echo $this->output_json($this->Main_model->getDataAdmin($where), false);
	}

	public function data_admin_bulk_delete()
	{
		$chk = $this->input->post('checked', true);
		// Delete File
		foreach ($chk as $id) {
			$path = FCPATH . 'uploads/foto-pengguna/';
			$where = ['uuid' => $id];
			$dtFoto = $this->Main_model->where_data($where, 'tbl_pengguna')->row();
			// Hapus File foto
			if (!empty($dtFoto->foto)) {
				if (file_exists($path . $dtFoto->foto)) {
					unlink($path . $dtFoto->foto);
				}
			}
		}

		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->Main_model->bulk_delete('tbl_pengguna', $chk, 'uuid')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			} else {
				show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
			}
		}
	}

	public function data_admin_tambah()
	{
		$this->form_validation->set_rules('username', 'username', 'trim|required');
		$this->form_validation->set_rules('nama', 'nama', 'trim|required');
		$this->form_validation->set_rules('password', 'Kata Sandi', 'trim');
		$this->form_validation->set_rules('tempat_lahir', 'tempat lahir', 'trim|required');
		$this->form_validation->set_rules('tanggal_lahir', 'tanggal lahir', 'trim|required');
		$this->form_validation->set_rules('jenis_kelamin', 'jenis kelamin', 'trim|required');
		$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
		$this->form_validation->set_rules('no_telp', 'nomor telp/handphone', 'trim|required');
		$this->form_validation->set_rules('pendidikan_terakhir', 'pendidikan terakhir', 'trim|required');

		if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
			$username = $this->input->post('username', true);
			$nama = $this->input->post('nama', true);
			$password = $this->input->post('password', true);
			$tempat_lahir = $this->input->post('tempat_lahir', true);
			$tanggal_lahir = $this->input->post('tanggal_lahir', true);
			$jenis_kelamin = $this->input->post('jenis_kelamin', true);
			$alamat = $this->input->post('alamat', true);
			$no_telp = $this->input->post('no_telp', true);
			$pendidikan_terakhir = $this->input->post('pendidikan_terakhir', true);
			$options = [
				'cost' => 12,
			];
			$data = [
				'uuid'		=> $this->uuid->v4(),
				'username' => $username,
				'nama' => $nama,
				'password' => password_hash($password, PASSWORD_DEFAULT, $options),
				'tempat_lahir' => $tempat_lahir,
				'tanggal_lahir' => $tanggal_lahir,
				'jenis_kelamin' => $jenis_kelamin,
				'alamat' => $alamat,
				'no_telp' => $no_telp,
				'pendidikan_terakhir' => $pendidikan_terakhir,
				'is_admin' => 1
			];

			$config['upload_path']		= './uploads/foto-pengguna/';
			$config['allowed_types']	= 'jpeg|jpg|png|gif';
			$config['max_size']			= 2048;
			$config['encrypt_name']		= true;

			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('upload_file')) {
				if (isset($_FILES['upload_file']) && isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != '') {
					$error = $this->upload->display_errors();
					show_error($error);
					die;
				}
			} else {
				$upload_data = $this->upload->data();
				$data['foto'] = $upload_data['raw_name'] . $upload_data['file_ext'];
			}

			if ($this->Main_model->insert_data($data, 'tbl_pengguna')) {
				$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
						window.setTimeout(function() {
							window.location = "' . base_url('admin/data-admin') . '";
						}, 1500)
						</script>';
			}
		} else {
			$this->data['username'] =  $this->form_validation->set_value('username');
			$this->data['nama'] = $this->form_validation->set_value('nama');
			$this->data['password'] = $this->form_validation->set_value('password');
			$this->data['tempat_lahir'] = $this->form_validation->set_value('tempat_lahir');
			$this->data['tanggal_lahir'] = $this->form_validation->set_value('tanggal_lahir');
			$this->data['jenis_kelamin'] = $this->form_validation->set_value('jenis_kelamin');
			$this->data['alamat'] = $this->form_validation->set_value('alamat');
			$this->data['no_telp'] = $this->form_validation->set_value('no_telp');
			$this->data['pendidikan_terakhir'] = $this->form_validation->set_value('pendidikan_terakhir');
		}

		$this->data['title'] = 'Tambah Data Admin';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-data-admin/data-admin-tambah';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function data_admin_edit()
	{
		$uuid = $this->uri->segment(3);
		$where = ['uuid' => $uuid];

		$row = $this->Main_model->where_data($where, 'tbl_pengguna')->row_array();
		if (isset($row['id']) && !empty($uuid)) {
			$this->form_validation->set_rules('username', 'username', 'trim|required');
			$this->form_validation->set_rules('nama', 'nama', 'trim|required');
			$this->form_validation->set_rules('password', 'Kata Sandi', 'trim');
			$this->form_validation->set_rules('tempat_lahir', 'tempat lahir', 'trim|required');
			$this->form_validation->set_rules('tanggal_lahir', 'tanggal lahir', 'trim|required');
			$this->form_validation->set_rules('jenis_kelamin', 'jenis kelamin', 'trim|required');
			$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
			$this->form_validation->set_rules('no_telp', 'nomor telp/handphone', 'trim|required');
			$this->form_validation->set_rules('pendidikan_terakhir', 'pendidikan terakhir', 'trim|required');

			if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
				$username = $this->input->post('username', true);
				$nama = $this->input->post('nama', true);
				$password = $this->input->post('password', true);
				$tempat_lahir = $this->input->post('tempat_lahir', true);
				$tanggal_lahir = $this->input->post('tanggal_lahir', true);
				$jenis_kelamin = $this->input->post('jenis_kelamin', true);
				$alamat = $this->input->post('alamat', true);
				$no_telp = $this->input->post('no_telp', true);
				$pendidikan_terakhir = $this->input->post('pendidikan_terakhir', true);

				$data = [
					'username'      => $username,
					'nama'         => $nama,
					'tempat_lahir' => $tempat_lahir,
					'tanggal_lahir' => $tanggal_lahir,
					'jenis_kelamin' => $jenis_kelamin,
					'alamat' => $alamat,
					'no_telp' => $no_telp,
					'pendidikan_terakhir' => $pendidikan_terakhir,
					'is_admin'	=> 1,
				];
				$config['upload_path']		= './uploads/foto-pengguna/';
				$config['allowed_types']	= 'jpeg|jpg|png|gif';
				$config['max_size']			= 2048;
				$config['encrypt_name']		= true;

				$this->load->library('upload');
				$this->upload->initialize($config);


				if (!$this->upload->do_upload('upload_file')) {
					if (isset($_FILES['upload_file']) && isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != '') {
						$error = $this->upload->display_errors();
						show_error($error);
						die;
					}
				} else {
					$path = FCPATH . 'uploads/foto-pengguna/';

					$dtFoto = $this->Main_model->where_data($where, 'tbl_pengguna')->row();
					// Hapus File foto
					if (!empty($dtFoto->foto)) {
						if (file_exists($path . $dtFoto->foto)) {
							unlink($path . $dtFoto->foto);
						}
					}

					$upload_data = $this->upload->data();
					$data['foto'] = $upload_data['raw_name'] . $upload_data['file_ext'];
				}


				$options = [
					'cost' => 12,
				];
				if (!empty($password)) {
					$data['password'] = password_hash($password, PASSWORD_DEFAULT, $options);
				}
				if ($uuid == $row['uuid']) {
					if ($this->Main_model->update_data($where, $data, 'tbl_pengguna')) {
						$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
							window.setTimeout(function() {
								window.location = "' . base_url('admin/data-admin') . '";
							}, 1500)
							</script>';
					}
				} else {
					show_404();
				}
			} else {
				$this->data['username'] =  $this->form_validation->set_value('username', $row['username']);
				$this->data['nama'] = $this->form_validation->set_value('nama', $row['nama']);
				$this->data['password'] = $this->form_validation->set_value('password');
				$this->data['tempat_lahir'] = $this->form_validation->set_value('tempat_lahir', $row['tempat_lahir']);
				$this->data['tanggal_lahir'] = $this->form_validation->set_value('tanggal_lahir', $row['tanggal_lahir']);
				$this->data['jenis_kelamin'] = $this->form_validation->set_value('jenis_kelamin', $row['jenis_kelamin']);
				$this->data['alamat'] = $this->form_validation->set_value('alamat', $row['alamat']);
				$this->data['no_telp'] = $this->form_validation->set_value('no_telp', $row['no_telp']);
				$this->data['pendidikan_terakhir'] = $this->form_validation->set_value('pendidikan_terakhir', $row['pendidikan_terakhir']);
				$this->data['foto'] = $this->form_validation->set_value('foto', $row['foto']);
			}

			$this->data['title'] = 'Edit Data Admin';
			$this->data['judul'] = $this->data['title'];
			$this->data['content'] = 'admin/v-data-admin/data-admin-edit';
			$this->template->_render_page('admin/main-page', $this->data);
		} else {
			show_404();
		}
	}

	public function data_admin_delete()
	{
		$uuid = $this->uri->segment(3);
		$where = ['uuid' => $uuid];
		$path = FCPATH . 'uploads/foto-pengguna/';
		$dtFoto = $this->Main_model->where_data($where, 'tbl_pengguna')->row();
		// Hapus foto
		if (!empty($dtFoto->foto)) {
			if (file_exists($path . $dtFoto->foto)) {
				unlink($path . $dtFoto->foto);
			}
		}
		if ($this->Main_model->delete_data($where, 'tbl_pengguna')) {
			redirect('admin/data-admin');
		} else {
			show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
		}
	}

	public function data_admin_import()
	{
		if ($this->input->post('btn-preview')) {
			$config['upload_path']		= './uploads/import/';
			$config['allowed_types']	= 'xls|xlsx|csv';
			$config['max_size']			= 2048;
			$config['encrypt_name']		= true;

			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('upload_file')) {
				$error = $this->upload->display_errors();
				show_error($error);
				die;
			} else {
				$file = $this->upload->data('full_path');
				$ext = $this->upload->data('file_ext');

				switch ($ext) {
					case '.xlsx':
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
						break;
					case '.xls':
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
						break;
					case '.csv':
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
						break;
					default:
						echo "unknown file ext";
						die;
				}

				$spreadsheet = $reader->load($file);
				$sheetData = $spreadsheet->getActiveSheet()->toArray();
				$data = [];
				for ($i = 1; $i < count($sheetData); $i++) {
					$data[] = [
						'username' => $sheetData[$i][0],
						'nama' => $sheetData[$i][1],
						'password' => $sheetData[$i][2],
						'tempat_lahir' => $sheetData[$i][3],
						'tanggal_lahir' => $sheetData[$i][4],
						'jenis_kelamin' => $sheetData[$i][5],
						'alamat' => $sheetData[$i][6],
						'no_telp' => $sheetData[$i][7],
						'pendidikan_terakhir' => $sheetData[$i][8]
					];
				}

				unlink($file);

				$this->data['import'] = $data;
			}
		}
		$this->data['title'] = 'Import Data Admin';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-data-admin/data-admin-import';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function data_admin_do_import()
	{

		$input = json_decode($this->input->post('data', true));
		$data = [];
		$options = [
			'cost' => 12,
		];
		foreach ($input as $d) {
			$data[] = [
				'uuid' => $this->uuid->v4(),
				'username' => $d->username,
				'password' => password_hash($d->password, PASSWORD_DEFAULT, $options),
				'nama' => $d->nama,
				'tempat_lahir' => $d->tempat_lahir,
				'tanggal_lahir' => $d->tanggal_lahir,
				'jenis_kelamin' => $d->jenis_kelamin,
				'alamat' => $d->alamat,
				'no_telp' => $d->no_telp,
				'pendidikan_terakhir' => $d->pendidikan_terakhir,
				'is_admin' => 1
			];
		}

		$save = $this->Main_model->batch_insert('tbl_pengguna', $data, true);
		if ($save) {
			redirect('admin/data-admin');
		} else {
			redirect('admin/data-admin-import');
		}
	}

	/**
	 * untuk data peserta ujian
	 */

	public function data_peserta_ujian()
	{
		$this->data['title'] = 'Data Peserta Ujian';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-data-peserta-ujian/data-peserta-ujian';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function data_peserta_ujian_list()
	{
		$where = ['is_admin' => 0];
		echo $this->output_json($this->Main_model->getDataPesertaUji($where), false);
	}

	public function data_peserta_ujian_bulk_delete()
	{
		$chk = $this->input->post('checked', true);
		// Delete File
		foreach ($chk as $id) {
			$path = FCPATH . 'uploads/foto-pengguna/';
			$where = ['uuid' => $id];
			$dtFoto = $this->Main_model->where_data($where, 'tbl_pengguna')->row();
			// Hapus File foto
			if (!empty($dtFoto->foto)) {
				if (file_exists($path . $dtFoto->foto)) {
					unlink($path . $dtFoto->foto);
				}
			}
		}

		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {

			if ($this->Main_model->bulk_delete('tbl_pengguna', $chk, 'uuid')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			} else {
				show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
			}
		}
	}

	public function data_peserta_ujian_tambah()
	{
		$this->form_validation->set_rules('username', 'username', 'trim|required');
		$this->form_validation->set_rules('nama', 'nama', 'trim|required');
		$this->form_validation->set_rules('password', 'Kata Sandi', 'trim');
		$this->form_validation->set_rules('tempat_lahir', 'tempat lahir', 'trim|required');
		$this->form_validation->set_rules('tanggal_lahir', 'tanggal lahir', 'trim|required');
		$this->form_validation->set_rules('jenis_kelamin', 'jenis kelamin', 'trim|required');
		$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
		$this->form_validation->set_rules('no_telp', 'nomor telp/handphone', 'trim|required');
		$this->form_validation->set_rules('pendidikan_terakhir', 'pendidikan terakhir', 'trim|required');

		if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
			$username = $this->input->post('username', true);
			$nama = $this->input->post('nama', true);
			$password = $this->input->post('password', true);
			$tempat_lahir = $this->input->post('tempat_lahir', true);
			$tanggal_lahir = $this->input->post('tanggal_lahir', true);
			$jenis_kelamin = $this->input->post('jenis_kelamin', true);
			$alamat = $this->input->post('alamat', true);
			$no_telp = $this->input->post('no_telp', true);
			$pendidikan_terakhir = $this->input->post('pendidikan_terakhir', true);

			$options = [
				'cost' => 12,
			];

			$data = [
				'uuid' => $this->uuid->v4(),
				'username' => $username,
				'nama' => $nama,
				'password' => password_hash($password, PASSWORD_DEFAULT, $options),
				'tempat_lahir' => $tempat_lahir,
				'tanggal_lahir' => $tanggal_lahir,
				'jenis_kelamin' => $jenis_kelamin,
				'alamat' => $alamat,
				'no_telp' => $no_telp,
				'pendidikan_terakhir' => $pendidikan_terakhir,
			];

			$config['upload_path']		= './uploads/foto-pengguna/';
			$config['allowed_types']	= 'jpeg|jpg|png|gif';
			$config['max_size']			= 2048;
			$config['encrypt_name']		= true;

			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('upload_file')) {
				if (isset($_FILES['upload_file']) && isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != '') {
					$error = $this->upload->display_errors();
					show_error($error);
					die;
				}
			} else {
				$upload_data = $this->upload->data();
				$data['foto'] = $upload_data['raw_name'] . $upload_data['file_ext'];
			}

			if ($this->Main_model->insert_data($data, 'tbl_pengguna')) {
				$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
						window.setTimeout(function() {
							window.location = "' . base_url('admin/data-peserta-ujian') . '";
						}, 1500)
						</script>';
			}
		} else {
			$this->data['username'] =  $this->form_validation->set_value('username');
			$this->data['nama'] = $this->form_validation->set_value('nama');
			$this->data['password'] = $this->form_validation->set_value('password');
			$this->data['tempat_lahir'] = $this->form_validation->set_value('tempat_lahir');
			$this->data['tanggal_lahir'] = $this->form_validation->set_value('tanggal_lahir');
			$this->data['jenis_kelamin'] = $this->form_validation->set_value('jenis_kelamin');
			$this->data['alamat'] = $this->form_validation->set_value('alamat');
			$this->data['no_telp'] = $this->form_validation->set_value('no_telp');
			$this->data['pendidikan_terakhir'] = $this->form_validation->set_value('pendidikan_terakhir');
		}

		$this->data['title'] = 'Tambah Data Peserta Ujian';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-data-peserta-ujian/data-peserta-ujian-tambah';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function data_peserta_ujian_edit()
	{
		$uuid = $this->uri->segment(3);
		$where = ['uuid' => $uuid];

		$row = $this->Main_model->where_data($where, 'tbl_pengguna')->row_array();
		if (isset($row['id']) && !empty($uuid)) {
			$this->form_validation->set_rules('username', 'username', 'trim|required');
			$this->form_validation->set_rules('nama', 'nama', 'trim|required');
			$this->form_validation->set_rules('password', 'Kata Sandi', 'trim');
			$this->form_validation->set_rules('tempat_lahir', 'tempat lahir', 'trim|required');
			$this->form_validation->set_rules('tanggal_lahir', 'tanggal lahir', 'trim|required');
			$this->form_validation->set_rules('jenis_kelamin', 'jenis kelamin', 'trim|required');
			$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
			$this->form_validation->set_rules('no_telp', 'nomor telp/handphone', 'trim|required');
			$this->form_validation->set_rules('pendidikan_terakhir', 'pendidikan terakhir', 'trim|required');

			if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
				$username = $this->input->post('username', true);
				$nama = $this->input->post('nama', true);
				$password = $this->input->post('password', true);
				$tempat_lahir = $this->input->post('tempat_lahir', true);
				$tanggal_lahir = $this->input->post('tanggal_lahir', true);
				$jenis_kelamin = $this->input->post('jenis_kelamin', true);
				$alamat = $this->input->post('alamat', true);
				$no_telp = $this->input->post('no_telp', true);
				$pendidikan_terakhir = $this->input->post('pendidikan_terakhir', true);

				$data = [
					'username'      => $username,
					'nama'         => $nama,
					'tempat_lahir' => $tempat_lahir,
					'tanggal_lahir' => $tanggal_lahir,
					'jenis_kelamin' => $jenis_kelamin,
					'alamat' => $alamat,
					'no_telp' => $no_telp,
					'pendidikan_terakhir' => $pendidikan_terakhir,
				];

				$config['upload_path']		= './uploads/foto-pengguna/';
				$config['allowed_types']	= 'jpeg|jpg|png|gif';
				$config['max_size']			= 2048;
				$config['encrypt_name']		= true;

				$this->load->library('upload');
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('upload_file')) {
					if (isset($_FILES['upload_file']) && isset($_FILES['upload_file']['name']) && $_FILES['upload_file']['name'] != '') {
						$error = $this->upload->display_errors();
						show_error($error);
						die;
					}
				} else {
					$path = FCPATH . 'uploads/foto-pengguna/';

					$dtFoto = $this->Main_model->where_data($where, 'tbl_pengguna')->row();
					// Hapus File foto
					if (!empty($dtFoto->foto)) {
						if (file_exists($path . $dtFoto->foto)) {
							unlink($path . $dtFoto->foto);
						}
					}

					$upload_data = $this->upload->data();
					$data['foto'] = $upload_data['raw_name'] . $upload_data['file_ext'];
				}

				$options = [
					'cost' => 12,
				];
				if ($password) {
					$data['password'] = password_hash($password, PASSWORD_DEFAULT, $options);
				}
				if ($uuid == $row['uuid']) {
					if ($this->Main_model->update_data($where, $data, 'tbl_pengguna')) {
						$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
							window.setTimeout(function() {
								window.location = "' . base_url('admin/data-peserta-ujian') . '";
							}, 1500)
							</script>';
					}
				} else {
					show_404();
				}
			} else {
				$this->data['username'] =  $this->form_validation->set_value('username', $row['username']);
				$this->data['nama'] = $this->form_validation->set_value('nama', $row['nama']);
				$this->data['password'] = $this->form_validation->set_value('password');
				$this->data['tempat_lahir'] = $this->form_validation->set_value('tempat_lahir', $row['tempat_lahir']);
				$this->data['tanggal_lahir'] = $this->form_validation->set_value('tanggal_lahir', $row['tanggal_lahir']);
				$this->data['jenis_kelamin'] = $this->form_validation->set_value('jenis_kelamin', $row['jenis_kelamin']);
				$this->data['alamat'] = $this->form_validation->set_value('alamat', $row['alamat']);
				$this->data['no_telp'] = $this->form_validation->set_value('no_telp', $row['no_telp']);
				$this->data['pendidikan_terakhir'] = $this->form_validation->set_value('pendidikan_terakhir', $row['pendidikan_terakhir']);
				$this->data['foto'] = $this->form_validation->set_value('foto', $row['foto']);
			}

			$this->data['title'] = 'Edit Data Peserta Ujian';
			$this->data['judul'] = $this->data['title'];
			$this->data['content'] = 'admin/v-data-peserta-ujian/data-peserta-ujian-edit';
			$this->template->_render_page('admin/main-page', $this->data);
		} else {
			show_404();
		}
	}

	public function data_peserta_ujian_delete()
	{
		$uuid = $this->uri->segment(3);
		$where = ['uuid' => $uuid];
		$path = FCPATH . 'uploads/foto-pengguna/';
		$dtFoto = $this->Main_model->where_data($where, 'tbl_pengguna')->row();
		// Hapus foto
		if (!empty($dtFoto->foto)) {
			if (file_exists($path . $dtFoto->foto)) {
				unlink($path . $dtFoto->foto);
			}
		}
		if ($this->Main_model->delete_data($where, 'tbl_pengguna')) {
			redirect('admin/data-peserta-ujian');
		} else {
			show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
		}
	}

	public function data_peserta_ujian_import()
	{
		if ($this->input->post('btn-preview')) {
			$config['upload_path']		= './uploads/import/';
			$config['allowed_types']	= 'xls|xlsx|csv';
			$config['max_size']			= 2048;
			$config['encrypt_name']		= true;

			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('upload_file')) {
				$error = $this->upload->display_errors();
				show_error($error);
				die;
			} else {
				$file = $this->upload->data('full_path');
				$ext = $this->upload->data('file_ext');

				switch ($ext) {
					case '.xlsx':
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
						break;
					case '.xls':
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
						break;
					case '.csv':
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
						break;
					default:
						echo "unknown file ext";
						die;
				}

				$spreadsheet = $reader->load($file);
				$sheetData = $spreadsheet->getActiveSheet()->toArray();
				$data = [];
				for ($i = 1; $i < count($sheetData); $i++) {
					$data[] = [
						'username' => $sheetData[$i][0],
						'nama' => $sheetData[$i][1],
						'password' => $sheetData[$i][2],
						'tempat_lahir' => $sheetData[$i][3],
						'tanggal_lahir' => $sheetData[$i][4],
						'jenis_kelamin' => $sheetData[$i][5],
						'alamat' => $sheetData[$i][6],
						'no_telp' => $sheetData[$i][7],
						'pendidikan_terakhir' => $sheetData[$i][8]
					];
				}

				unlink($file);

				$this->data['import'] = $data;
			}
		}
		$this->data['title'] = 'Import Data Peserta Ujian';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-data-peserta-ujian/data-peserta-ujian-import';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function data_peserta_ujian_do_import()
	{
		$input = json_decode($this->input->post('data', true));
		$data = [];
		$options = [
			'cost' => 12,
		];
		foreach ($input as $d) {
			$data[] = [
				'uuid' => $this->uuid->v4(),
				'username' => $d->username,
				'password' => password_hash($d->password, PASSWORD_DEFAULT, $options),
				'nama' => $d->nama,
				'tempat_lahir' => $d->tempat_lahir,
				'tanggal_lahir' => $d->tanggal_lahir,
				'jenis_kelamin' => $d->jenis_kelamin,
				'alamat' => $d->alamat,
				'no_telp' => $d->no_telp,
				'pendidikan_terakhir' => $d->pendidikan_terakhir,
				'is_admin' => 0
			];
		}

		$save = $this->Main_model->batch_insert('tbl_pengguna', $data, true);
		if ($save) {
			redirect('admin/data-peserta-ujian');
		} else {
			redirect('admin/data-peserta-ujian-import');
		}
	}

	/**
	 * Untuk mata uji
	 */

	public function mata_uji()
	{
		$this->data['title'] = 'Data Peserta Ujian';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-mata-uji/mata-uji';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function mata_uji_list()
	{
		echo $this->output_json($this->Main_model->getDataMataUji(), false);
	}

	public function mata_uji_bulk_delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->Main_model->bulk_delete('tbl_mata_uji', $chk, 'id')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			} else {
				show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
			}
		}
	}

	public function mata_uji_tambah()
	{
		$this->form_validation->set_rules('mata_uji', 'Mata Uji', 'trim|required');

		if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
			$mata_uji = $this->input->post('mata_uji', true);

			$data = [
				'mata_uji'      => $mata_uji
			];

			if ($this->Main_model->insert_data($data, 'tbl_mata_uji')) {
				$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
						 window.setTimeout(function() {
							 window.location = "' . base_url('admin/mata-uji') . '";
						 }, 1500)
						 </script>';
			}
		}

		$this->data['title'] = 'Tambah Mata Uji';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-mata-uji/mata-uji-tambah';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function mata_uji_edit()
	{
		$id = $this->uri->segment(3);
		$where = ['id' => $id];

		$row = $this->Main_model->where_data($where, 'tbl_mata_uji')->row_array();
		if (isset($row['id']) && !empty($id)) {
			$this->form_validation->set_rules('mata_uji', 'Mata Uji', 'trim|required');

			if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
				$mata_uji = $this->input->post('mata_uji', true);
				$data = [
					'mata_uji'      => $mata_uji
				];
				if ($this->Main_model->update_data($where, $data, 'tbl_mata_uji')) {
					$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
							 window.setTimeout(function() {
								 window.location = "' . base_url('admin/mata-uji') . '";
							 }, 1500)
							 </script>';
				}
			} else {
				$this->data['mata_uji'] =  $this->form_validation->set_value('mata_uji', $row['mata_uji']);
			}

			$this->data['title'] = 'Edit Mata Uji';
			$this->data['judul'] = $this->data['title'];
			$this->data['content'] = 'admin/v-mata-uji/mata-uji-edit';
			$this->template->_render_page('admin/main-page', $this->data);
		} else {
			show_404();
		}
	}

	public function mata_uji_delete()
	{
		$id = $this->uri->segment(3);
		$where = ['id' => $id];
		if ($this->Main_model->delete_data($where, 'tbl_mata_uji')) {
			redirect('admin/mata-uji');
		} else {
			show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
		}
	}

	/**
	 * untuk bank soal
	 */
	public function bank_soal()
	{
		$this->data['title'] = 'Bank Soal';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-bank-soal/bank-soal';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function bank_soal_list()
	{
		echo $this->output_json($this->Main_model->getDataBankSoal(), false);
	}

	public function bank_soal_bulk_delete()
	{
		$chk = $this->input->post('checked', true);
		// Delete File
		foreach ($chk as $id) {
			$abjad = ['a', 'b', 'c', 'd', 'e'];
			$path = FCPATH . 'uploads/bank-soal/';
			$where = ['id_soal' => $id];
			$soal = $this->Main_model->where_data($where, 'tbl_soal')->row();
			// Hapus File Soal
			if (!empty($soal->file)) {
				if (file_exists($path . $soal->file)) {
					unlink($path . $soal->file);
				}
			}
			//Hapus File Opsi
			$i = 0; //index
			foreach ($abjad as $abj) {
				$file_opsi = 'file_' . $abj;
				if (!empty($soal->$file_opsi)) {
					if (file_exists($path . $soal->$file_opsi)) {
						unlink($path . $soal->$file_opsi);
					}
				}
			}
		}

		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->Main_model->bulk_delete('tbl_soal', $chk, 'id_soal')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			} else {
				show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
			}
		}
	}

	public function bank_soal_delete()
	{
		$id = $this->uri->segment(3);
		$where = ['id_soal' => $id];

		$abjad = ['a', 'b', 'c', 'd', 'e'];
		$path = FCPATH . 'uploads/bank-soal/';
		$soal = $this->Main_model->where_data($where, 'tbl_soal')->row();
		// Hapus File Soal
		if (!empty($soal->file)) {
			if (file_exists($path . $soal->file)) {
				unlink($path . $soal->file);
			}
		}
		//Hapus File Opsi
		$i = 0; //index
		foreach ($abjad as $abj) {
			$file_opsi = 'file_' . $abj;
			if (!empty($soal->$file_opsi)) {
				if (file_exists($path . $soal->$file_opsi)) {
					unlink($path . $soal->$file_opsi);
				}
			}
		}

		if ($this->Main_model->delete_data($where, 'tbl_soal')) {
			redirect('admin/bank-soal');
		} else {
			show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
		}
	}

	public function bank_soal_tambah()
	{
		$this->form_validation->set_rules('mata_uji', 'Mata Uji', 'trim|required');
		$this->form_validation->set_rules('jawaban', 'Kunci Jawaban', 'required');
		$this->form_validation->set_rules('bobot', 'Bobot Soal', 'required|max_length[2]');

		$getDtMataUji = $this->Main_model->get_data('tbl_mata_uji');

		if ($getDtMataUji->num_rows()) {
			$this->data['dt_mata_uji'] = $getDtMataUji->result_array();
		}

		if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
			$mata_uji = $this->input->post('mata_uji', true);
			$soal = $this->input->post('soal', true);
			$jawaban  = $this->input->post('jawaban', true);
			$bobot  = $this->input->post('bobot', true);

			$data = [
				'mata_uji_id' => $mata_uji,
				'soal'	=> $soal,
				'jawaban' => $jawaban,
				'bobot'	=> $bobot,
				'created_on' => time(),
				'updated_on' => time(),
			];

			$abjad = ['a', 'b', 'c', 'd', 'e'];

			// Inputan Opsi
			foreach ($abjad as $abj) {
				$data['opsi_' . $abj]    = $this->input->post('jawaban_' . $abj, true);
			}

			$allowed_type 	= [
				"image/jpeg", "image/jpg", "image/png", "image/gif",
				"audio/mpeg", "audio/mpg", "audio/mpeg3", "audio/mp3", "audio/x-wav", "audio/wave", "audio/wav",
				"video/mp4", "application/octet-stream"
			];

			$config['upload_path']      = './uploads/bank-soal/';
			$config['allowed_types']    = 'jpeg|jpg|png|gif|mpeg|mpg|mpeg3|mp3|wav|wave|mp4';
			//$config['max_size']			= 2048;
			$config['encrypt_name']     = TRUE;

			// Array input names untuk loop
			$file_ = [
				'file_soal',
				'file_a',
				'file_b',
				'file_c',
				'file_d',
				'file_e',
			];

			// Melakukan looping untuk setiap input name
			foreach ($file_ as $inputName) {

				// Mengecek apakah input dikirimkan oleh user dan memiliki file yang di-upload
				if (!empty($_FILES[$inputName]['name']) && $_FILES[$inputName]['size'] > 0) {

					// Melakukan upload dan menyimpan path file baru ke dalam array result
					$this->upload->initialize($config);
					if ($this->upload->do_upload($inputName)) {
						$dt = $this->upload->data();
						$data[$inputName] = $dt['raw_name'] . $dt['file_ext'];
						if ($_FILES[$inputName]['name'] == 'file_soal') {
							$data['tipe_file'] = $this->upload->data('file_type');
						}
					} else {
						$error = $this->upload->display_errors();
						show_error($error, 500, strtoupper(str_replace('_', ' ', $_FILES[$inputName]['name'])) . ' Error');
						exit();
					}
				}
			}

			if ($this->Main_model->insert_data($data, 'tbl_soal')) {
				$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
						 window.setTimeout(function() {
							 window.location = "' . base_url('admin/bank-soal') . '";
						 }, 1500)
						 </script>';
			}
		}

		$this->data['title'] = 'Tambah Soal';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-bank-soal/bank-soal-tambah';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function bank_soal_edit()
	{
		$id = $this->uri->segment(3);
		$where = ['id_soal' => $id];

		$row = $this->Main_model->where_data($where, 'tbl_soal')->row_array();
		if (isset($row['id_soal']) && !empty($id)) {
			$this->form_validation->set_rules('mata_uji', 'Mata Uji', 'trim|required');
			$this->form_validation->set_rules('jawaban', 'Kunci Jawaban', 'required');
			$this->form_validation->set_rules('bobot', 'Bobot Soal', 'required|max_length[2]');

			$getDtMataUji = $this->Main_model->get_data('tbl_mata_uji');

			if ($getDtMataUji->num_rows()) {
				$this->data['dt_mata_uji'] = $getDtMataUji->result_array();
			}

			if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
				$mata_uji = $this->input->post('mata_uji', true);
				$soal = $this->input->post('soal', true);
				$jawaban  = $this->input->post('jawaban', true);
				$bobot  = $this->input->post('bobot', true);

				$data = [
					'mata_uji_id' => $mata_uji,
					'soal'	=> $soal,
					'jawaban' => $jawaban,
					'bobot'	=> $bobot,
					'updated_on' => time(),
				];

				$abjad = ['a', 'b', 'c', 'd', 'e'];

				// Inputan Opsi
				foreach ($abjad as $abj) {
					$data['opsi_' . $abj]    = $this->input->post('jawaban_' . $abj, true);
				}

				$allowed_type 	= [
					"image/jpeg", "image/jpg", "image/png", "image/gif",
					"audio/mpeg", "audio/mpg", "audio/mpeg3", "audio/mp3", "audio/x-wav", "audio/wave", "audio/wav",
					"video/mp4", "application/octet-stream"
				];
				$config['upload_path']      = './uploads/bank-soal/';
				$config['allowed_types']    = 'jpeg|jpg|png|gif|mpeg|mpg|mpeg3|mp3|wav|wave|mp4';
				//$config['max_size']			= 2048;
				$config['encrypt_name']     = TRUE;

				// Array input names untuk loop
				$file_ = [
					'file_soal',
					'file_a',
					'file_b',
					'file_c',
					'file_d',
					'file_e',
				];

				// Melakukan looping untuk setiap input name
				foreach ($file_ as $inputName) {

					// Mengecek apakah input dikirimkan oleh user dan memiliki file yang di-upload
					if (!empty($_FILES[$inputName]['name']) && $_FILES[$inputName]['size'] > 0) {

						// Melakukan upload dan menyimpan path file baru ke dalam array result
						$this->upload->initialize($config);
						if ($this->upload->do_upload($inputName)) {
							$img_src = FCPATH . 'uploads/bank-soal/';
							if (!unlink($img_src . $row[$inputName])) {
								show_error('Error saat delete gambar <br/>' . var_dump($row), 500, 'Error Edit Gambar');
								exit();
							}
							$dt = $this->upload->data();
							$data[$inputName] = $dt['raw_name'] . $dt['file_ext'];
							if ($_FILES[$inputName]['name'] == 'file_soal') {
								$data['tipe_file'] = $this->upload->data('file_type');
							}
						} else {
							$error = $this->upload->display_errors();
							show_error($error, 500, strtoupper(str_replace('_', ' ', $_FILES[$inputName]['name'])) . ' Error');
							exit();
						}
					}
				}

				if ($this->Main_model->update_data($where, $data, 'tbl_soal')) {
					$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
						window.setTimeout(function() {
							window.location = "' . base_url('admin/bank-soal') . '";
						}, 1500)
						</script>';
				}
			} else {
				$this->data['mata_uji'] =  $this->form_validation->set_value('mata_uji', $row['mata_uji_id']);
				$this->data['dt_detail'] = $row;
			}

			$this->data['title'] = 'Edit Soal';
			$this->data['judul'] = $this->data['title'];
			$this->data['content'] = 'admin/v-bank-soal/bank-soal-edit';
			$this->template->_render_page('admin/main-page', $this->data);
		} else {
			show_404();
		}
	}

	public function bank_soal_detail()
	{
		$id = $this->uri->segment(3);
		$where = ['id_soal' => $id];
		$getDtDetailSoal = $this->Main_model->where_data($where, 'tbl_soal');
		if (!empty($id) && $getDtDetailSoal->num_rows()) {
			$this->data['dt_detail'] = $getDtDetailSoal->row_array();

			$this->data['title'] = 'Detail Soal';
			$this->data['judul'] = $this->data['title'];
			$this->data['content'] = 'admin/v-bank-soal/bank-soal-detail';
			$this->template->_render_page('admin/main-page', $this->data);
		} else {
			show_404();
		}
	}

	public function bank_soal_import()
	{
		if ($this->input->post('btn-preview')) {
			$config['upload_path']		= './uploads/import/';
			$config['allowed_types']	= 'xls|xlsx|csv';
			$config['max_size']			= 2048;
			$config['encrypt_name']		= true;

			$this->load->library('upload');
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('upload_file')) {
				$error = $this->upload->display_errors();
				show_error($error);
				die;
			} else {
				$file = $this->upload->data('full_path');
				$ext = $this->upload->data('file_ext');

				switch ($ext) {
					case '.xlsx':
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
						break;
					case '.xls':
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
						break;
					case '.csv':
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
						break;
					default:
						echo "unknown file ext";
						die;
				}

				$spreadsheet = $reader->load($file);
				$sheetData = $spreadsheet->getActiveSheet()->toArray();
				$data = [];
				for ($i = 1; $i < count($sheetData); $i++) {
					$data[] = [
						'soal' => $sheetData[$i][0],
						'opsi_a' => $sheetData[$i][1],
						'opsi_b' => $sheetData[$i][2],
						'opsi_c' => $sheetData[$i][3],
						'opsi_d' => $sheetData[$i][4],
						'opsi_e' => $sheetData[$i][5],
						'jawaban' => strtoupper($sheetData[$i][6]),
						'bobot_soal' => $sheetData[$i][7],
					];
				}

				unlink($file);

				$this->data['import'] = $data;
			}

			$this->data['id_mata_uji'] = $this->input->post('mata_uji');
		}
		$getDtMataUji = $this->Main_model->get_data('tbl_mata_uji');

		if ($getDtMataUji->num_rows()) {
			$this->data['dt_mata_uji'] = $getDtMataUji->result_array();
		}

		$this->data['title'] = 'Import Bank Soal';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-bank-soal/bank-soal-import';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function bank_soal_do_import()
	{
		$mata_uji = $this->input->post('mata_uji');
		$input = json_decode($this->input->post('data', true));
		$data = [];

		foreach ($input as $d) {
			$data[] = [
				'mata_uji_id' => $mata_uji,
				'soal'	=> '<p>' . $d->soal . '</p>',
				'opsi_a' => '<p>' . $d->opsi_a . '</p>',
				'opsi_b' => '<p>' . $d->opsi_b . '</p>',
				'opsi_c' => '<p>' . $d->opsi_c . '</p>',
				'opsi_d' => '<p>' . $d->opsi_d . '</p>',
				'opsi_e' => '<p>' . $d->opsi_e . '</p>',
				'jawaban' => $d->jawaban,
				'bobot'	=> $d->bobot_soal,
				'created_on' => time(),
				'updated_on' => time(),
			];
		}

		$save = $this->Main_model->batch_insert('tbl_soal', $data, true);
		if ($save) {
			redirect('admin/bank-soal');
		} else {
			redirect('admin/bank-soal-import');
		}
	}


	/**
	 * untuk ujian
	 */
	public function ujian()
	{
		$this->data['title'] = 'Jadwal Ujian';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-ujian/ujian';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function ujian_list()
	{
		echo $this->output_json($this->Main_model->getDataUjian(), false);
	}

	public function ujian_bulk_delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->Main_model->bulk_delete('tbl_jadwal_ujian', $chk, 'id_ujian')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			} else {
				show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
			}
		}
	}

	public function ujian_delete()
	{
		$id = $this->uri->segment(3);
		$where = ['id_ujian' => $id];
		if ($this->Main_model->delete_data($where, 'tbl_jadwal_ujian')) {
			redirect('admin/ujian');
		} else {
			show_error('Sepertinya data yang ingin kamu hapus mungkin memiliki relasi dengan tabel yang lain!', '', 'Oops!');
		}
	}

	public function ujian_refresh_token()
	{
		$this->load->helper('string');
		$data['token'] = strtoupper(random_string('alpha', 5));
		$where = ['id_ujian' => $this->uri->segment(3)];
		if ($this->Main_model->update_data($where, $data, 'tbl_jadwal_ujian')) {
			redirect('admin/ujian');
		}
	}

	public function convert_tgl($tgl)
	{
		return date('Y-m-d H:i:s', strtotime($tgl));
	}

	public function ujian_tambah()
	{

		$getDtMataUji = $this->Main_model->getJumlahSoalByMataUji();

		if ($getDtMataUji->num_rows()) {
			$this->data['dt_mata_uji'] = $getDtMataUji->result_array();
		}

		$this->form_validation->set_rules('mata_uji[]', 'Mata Uji', 'trim|required');
		$this->form_validation->set_rules('nama_ujian', 'Nama Ujian', 'trim|required');
		$this->form_validation->set_rules('jumlah_soal', 'Jumlah Soal', 'trim|required');
		$this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'trim|required');
		$this->form_validation->set_rules('toleransi_keterlambatan', 'Toleransi Keterlambatan', 'trim|required');
		$this->form_validation->set_rules('waktu_ujian', 'Waktu Ujian', 'trim|required');
		$this->form_validation->set_rules('jenis_soal', 'Jenis Soal', 'trim|required');

		if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
			$mata_uji = implode(',', $this->input->post('mata_uji', true));
			$nama_ujian = $this->input->post('nama_ujian', true);
			$jumlah_soal = $this->input->post('jumlah_soal', true);
			$tgl_mulai = $this->convert_tgl($this->input->post('tgl_mulai', true));
			$toleransi_keterlambatan =  $this->convert_tgl($this->input->post('toleransi_keterlambatan', true));
			$waktu_ujian = $this->input->post('waktu_ujian', true);
			$jenis_soal = $this->input->post('jenis_soal', true);

			$this->load->helper('string');
			$token = strtoupper(random_string('alpha', 5));

			$data = [
				'id_mata_uji'   => $mata_uji,
				'nama_ujian' 	=> $nama_ujian,
				'jumlah_soal' 	=> $jumlah_soal,
				'tgl_mulai' 	=> $tgl_mulai,
				'terlambat' 	=> $toleransi_keterlambatan,
				'waktu' 		=> $waktu_ujian,
				'jenis' 		=> $jenis_soal,
				'token'			=> $token
			];

			if ($this->Main_model->insert_data($data, 'tbl_jadwal_ujian')) {
				$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
						 window.setTimeout(function() {
							 window.location = "' . base_url('admin/ujian') . '";
						 }, 1500)
						 </script>';
			}
		}

		$this->data['title'] = 'Tambah Jadwal Ujian';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-ujian/ujian-tambah';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function ujian_edit()
	{
		$id = $this->uri->segment(3);
		$where = ['id_ujian' => $id];

		$row = $this->Main_model->where_data($where, 'tbl_jadwal_ujian')->row_array();
		if (isset($row['id_ujian']) && !empty($id)) {
			$getDtMataUji = $this->Main_model->getJumlahSoalByMataUji();

			if ($getDtMataUji->num_rows()) {
				$this->data['dt_mata_uji'] = $getDtMataUji->result_array();
			}

			$this->form_validation->set_rules('mata_uji[]', 'Mata Uji', 'trim|required');
			$this->form_validation->set_rules('nama_ujian', 'Nama Ujian', 'trim|required');
			$this->form_validation->set_rules('jumlah_soal', 'Jumlah Soal', 'trim|required');
			$this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'trim|required');
			$this->form_validation->set_rules('toleransi_keterlambatan', 'Toleransi Keterlambatan', 'trim|required');
			$this->form_validation->set_rules('waktu_ujian', 'Waktu Ujian', 'trim|required');
			$this->form_validation->set_rules('jenis_soal', 'Jenis Soal', 'trim|required');

			if ($this->input->post('btn-simpan') && $this->form_validation->run() == TRUE) {
				$mata_uji = implode(',', $this->input->post('mata_uji', true));
				$nama_ujian = $this->input->post('nama_ujian', true);
				$jumlah_soal = $this->input->post('jumlah_soal', true);
				$tgl_mulai = $this->convert_tgl($this->input->post('tgl_mulai', true));
				$toleransi_keterlambatan =  $this->convert_tgl($this->input->post('toleransi_keterlambatan', true));
				$waktu_ujian = $this->input->post('waktu_ujian', true);
				$jenis_soal = $this->input->post('jenis_soal', true);

				$data = [
					'id_mata_uji'   => $mata_uji,
					'nama_ujian'    => $nama_ujian,
					'jumlah_soal'   => $jumlah_soal,
					'tgl_mulai'     => $tgl_mulai,
					'terlambat'     => $toleransi_keterlambatan,
					'waktu'         => $waktu_ujian,
					'jenis'         => $jenis_soal
				];

				if ($this->Main_model->update_data($where, $data, 'tbl_jadwal_ujian')) {
					$this->data['message'] = '<script type="text/javascript">toastr.success("Data Berhasil Disimpan!")
                         window.setTimeout(function() {
                             window.location = "' . base_url('admin/ujian') . '";
                         }, 1500)
                         </script>';
				}
			} else {
				$this->data['mata_uji'] =  explode(',', $row['id_mata_uji']);
				$this->data['nama_ujian'] =  $this->form_validation->set_value('nama_ujian', $row['nama_ujian']);
				$this->data['jumlah_soal'] =  $this->form_validation->set_value('jumlah_soal', $row['jumlah_soal']);
				$this->data['tgl_mulai'] =  $this->form_validation->set_value('tgl_mulai', $row['tgl_mulai']);
				$this->data['toleransi_keterlambatan'] =  $this->form_validation->set_value('toleransi_keterlambatan', $row['terlambat']);
				$this->data['waktu_ujian'] =  $this->form_validation->set_value('waktu_ujian', $row['waktu']);
				$this->data['jenis_soal'] =  $this->form_validation->set_value('jenis_soal', $row['jenis']);
			}

			$this->data['title'] = 'Edit Jadwal Ujian';
			$this->data['judul'] = $this->data['title'];
			$this->data['content'] = 'admin/v-ujian/ujian-edit';
			$this->template->_render_page('admin/main-page', $this->data);
		} else {
			show_404();
		}
	}

	/**
	 * untuk hasil ujian
	 */
	public function hasil_ujian()
	{
		$this->data['title'] = 'Hasil Ujian';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-hasil-ujian/hasil-ujian';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function hasil_ujian_list()
	{
		echo $this->output_json($this->Main_model->getHasilUjian(), false);
	}

	public function hasil_ujian_lists()
	{
		$query = $this->Main_model->getHasilUjian();
		if ($query->num_rows()) {
			$data = $query->result_array();

			if (!empty($data)) {
				$list_id_mata_uji = explode(",", $data[0]['list_id_mata_uji']);
				$list_id_mata_uji = array_filter($list_id_mata_uji, 'strlen');
				$jml_benar_per_id = explode(",", $data[0]['jml_benar_per_id']);
				$jml_benar_per_id = array_filter($jml_benar_per_id, 'strlen');

				// Buat array kosong untuk menyimpan hasil angka setelah tanda ':'
				$angka_setelah_titik_dua = array();

				// Loop melalui setiap elemen dalam $data
				foreach ($jml_benar_per_id as $elemen) {
					// Pecah elemen berdasarkan tanda ':'
					$pecah = explode(':', $elemen);
					// Ambil angka setelah tanda ':' dan tambahkan ke array baru
					$angka_setelah_titik_dua[] = $pecah[1];
				}

				$jml_benar_per_id = $angka_setelah_titik_dua;

				// Array untuk menyimpan hasil penggabungan
				$hasil_gabungan = [];

				// Perulangan untuk menggabungkan berdasarkan indeks
				for ($i = 0; $i < count($list_id_mata_uji); $i++) {
					$hasil_gabungan[$i] = $list_id_mata_uji[$i] . ":" . $jml_benar_per_id[$i];
				}

				// Inisialisasi array untuk menyimpan jumlah angka 1 di setiap kunci
				$jumlah_angka_1_per_kunci = [];

				// Iterasi untuk menghitung jumlah angka 1 di setiap kunci
				foreach ($hasil_gabungan as $data) {
					list($kunci, $nilai) = explode(":", $data);
					if (!isset($jumlah_angka_1_per_kunci[$kunci])) {
						$jumlah_angka_1_per_kunci[$kunci] = 0;
					}
					$jumlah_angka_1_per_kunci[$kunci] += intval($nilai);
				}

				// Ubah ke dalam format JSON
				$json_result = json_encode($jumlah_angka_1_per_kunci);

				// Tampilkan hasilnya
				header('Content-Type: application/json');
				echo $json_result;
			}
		}
	}

	public function hasil_ujian_lihat()
	{
		$id = $this->uri->segment(3);
		$query = $this->Main_model->getHasilUjian_whereID_ujian($id);
		if ($query->num_rows()) {
			$dt = $query->row_array();
			$this->data['nama_ujian'] = $dt['nama_ujian'];
			$this->data['jumlah_soal'] = $dt['jumlah_soal'];
			$this->data['waktu'] = $dt['waktu'];
			$this->data['tgl_mulai'] = $dt['tgl_mulai'];
			$this->data['mata_uji'] = $dt['mata_uji'];
			$this->data['nilai_min'] = $dt['nilai_min'];
			$this->data['nilai_max'] = $dt['nilai_max'];
			$this->data['nilai_avg'] = $dt['nilai_avg'];
		} else {
			show_404();
		}

		$this->data['title'] = 'Detail Hasil Ujian';
		$this->data['judul'] = $this->data['title'];
		$this->data['content'] = 'admin/v-hasil-ujian/hasil-ujian-lihat';
		$this->template->_render_page('admin/main-page', $this->data);
	}

	public function hasil_ujian_lihat_peserta($id)
	{
		$query = $this->Main_model->getHasilUjian_whereID_ujian_dan_nama($id);
		if (!empty($query)) {
			$data_array = json_decode($query, true);

			$rank = 1;
			$last_score = null;

			foreach ($data_array['data'] as $key => &$entry) { // Note the '&' to make the entry referenceable.
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
			}

			// Now $data_array contains the desired structure.
			$json_data_updated = json_encode($data_array);

			// Hasil JSON yang sudah diperbarui
			echo $this->output_json($json_data_updated, false);
		}
	}

	public function hasil_ujian_cetak()
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
			$data['dt_nilai_peserta'] = $this->Main_model->getHasilUjian_whereID_ujian_nama($id)->result_array();
		}

		$data['title'] = 'Laporan Hasil Ujian';
		$data['judul'] = $data['title'];
		$this->load->view('admin/v-hasil-ujian/hasil-ujian-pdf', $data);
	}
}
