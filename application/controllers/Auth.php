<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

		if ($this->session->has_userdata('is_Logged')) {
			if ($this->session->userdata('is_admin') == TRUE) {
				redirect('admin/dashboard');
			} else {
				redirect('peserta-uji/dashboard');
			}
		}
		$this->data['title'] = 'Halaman Login';
		$this->data['content'] = 'auth/login_page';
		$this->template->_render_page('auth/main-page', $this->data);
	}

	public function proses_login()
	{
		$username = $this->input->post('uname');
		$password = $this->input->post('pwd');
		$data = ['username' => $username];
		$query = $this->Main_model->where_data($data, 'tbl_pengguna');
		$result = $query->row_array();
		if (!empty($result) && password_verify($password, $result['password'])) {
			$data = [
				'is_Logged' 	=> TRUE,
				'nama'		=> $result['nama'],
				'uuid'		=> $result['uuid']
			];
			if ($result['is_admin'] == 1) {
				$data['is_admin'] = TRUE;
				$dt['is_admin'] = TRUE;
			} else {
				$data['is_admin'] = FALSE;
				$dt['is_admin'] = FALSE;
			}

			$this->session->set_userdata($data);
			$dt['status'] = true;
			$dt['pesan_sukses'] = 'Selamat Datang ' . ucfirst($result['nama']) . '!';
		} else {
			$dt['status'] = false;
			$dt['pesan_gagal'] = 'Akun tidak ditemukan!';
		}
		echo json_encode($dt);
	}

	//fungsi logout
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('auth', 'refresh');
	}
}
