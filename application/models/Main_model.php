<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Main_model extends CI_Model
{
    public function __construct()
    {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
    }

    public function get_data($table)
    {
        # code...
        return $this->db->get($table);
    }

    function where_data($where, $table)
    {
        return $this->db->get_where($table, $where);
    }

    function insert_data($data, $table)
    {
        return $this->db->insert($table, $data);
    }

    public function batch_insert($table, $data, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->insert($table, $data);
        } else {
            $insert = $this->db->insert_batch($table, $data);
        }
        return $insert;
    }

    function update_data($where, $data, $table)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }

    public function batch_update($table, $data, $pk, $id = null, $batch = false)
    {
        if ($batch === false) {
            $insert = $this->db->update($table, $data, array($pk => $id));
        } else {
            $insert = $this->db->update_batch($table, $data, $pk);
        }
        return $insert;
    }

    function delete_data($where, $table)
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }

    function bulk_delete($table, $data, $pk)
    {
        $this->db->where_in($pk, $data);
        return $this->db->delete($table);
    }

    /** untuk Datatables */
    function getDataAdmin($where)
    {
        # code...
        $this->datatables->select('uuid,username,nama,tempat_lahir,tanggal_lahir,jenis_kelamin,foto');
        $this->datatables->from('tbl_pengguna');
        $this->datatables->where($where);
        $this->datatables->add_column('action', anchor('admin/data-admin-edit/$1', '<i class="fa fa-edit"></i> Edit', array('class' => 'btn btn-warning btn-sm')) . ' ' . anchor('admin/data-admin-delete/$1', '<i class="fa fa-trash"></i> Hapus', array('class' => 'btn btn-danger btn-sm')), 'uuid');
        return $this->datatables->generate();
    }

    function getDataPesertaUji($where)
    {
        # code...
        $this->datatables->select('uuid,username,nama,tempat_lahir,tanggal_lahir,jenis_kelamin,foto');
        $this->datatables->from('tbl_pengguna');
        $this->datatables->where($where);
        $this->datatables->add_column('action', anchor('admin/data-peserta-ujian-edit/$1', '<i class="fa fa-edit"></i> Edit', array('class' => 'btn btn-warning btn-sm')) . ' ' . anchor('admin/data-peserta-ujian-delete/$1', '<i class="fa fa-trash"></i> Hapus', array('class' => 'btn btn-danger btn-sm')), 'uuid');
        return $this->datatables->generate();
    }

    function getDataMataUji()
    {
        $this->datatables->select('id,mata_uji');
        $this->datatables->from('tbl_mata_uji');
        $this->datatables->add_column('action', anchor('admin/mata-uji-edit/$1', '<i class="fa fa-edit"></i> Edit', array('class' => 'btn btn-warning btn-sm')) . ' ' . anchor('admin/mata-uji-delete/$1', '<i class="fa fa-trash"></i> Hapus', array('class' => 'btn btn-danger btn-sm')), 'id');
        return $this->datatables->generate();
    }

    function getDataBankSoal()
    {
        $this->datatables->select('id_soal,mata_uji,soal,FROM_UNIXTIME(created_on) as created_on, FROM_UNIXTIME(updated_on) as updated_on');
        $this->datatables->from('tbl_soal');
        $this->datatables->join('tbl_mata_uji', 'COALESCE(tbl_mata_uji.id,"") = COALESCE(mata_uji_id,"")', 'LEFT');
        $this->datatables->add_column('action', anchor('admin/bank-soal-detail/$1', '<i class="fa fa-eye"></i> Detail', array('class' => 'btn btn-secondary btn-sm')) . ' ' . anchor('admin/bank-soal-edit/$1', '<i class="fa fa-edit"></i> Edit', array('class' => 'btn btn-warning btn-sm')) . ' ' . anchor('admin/bank-soal-delete/$1', '<i class="fa fa-trash"></i> Hapus', array('class' => 'btn btn-danger btn-sm')), 'id_soal');
        return $this->datatables->generate();
    }

    function getDataUjian()
    {
        //SELECT id_ujian, token, nama_ujian, GROUP_CONCAT(m.mata_uji) AS mata_uji, jumlah_soal, CONCAT(tgl_mulai, " <br/> (", waktu, " Menit)") AS waktu, jenis FROM `tbl_jadwal_ujian` LEFT JOIN tbl_mata_uji m ON FIND_IN_SET(m.id, tbl_jadwal_ujian.id_mata_uji) GROUP BY id_ujian, token, nama_ujian, jumlah_soal, waktu, jenis;
        $this->datatables->select('id_ujian, token, nama_ujian, GROUP_CONCAT(m.mata_uji) AS mata_uji, jumlah_soal, CONCAT(tgl_mulai, " <br/> (", waktu, " Menit)") AS waktu, jenis');
        $this->datatables->from('tbl_jadwal_ujian');
        $this->datatables->join('tbl_mata_uji m', 'FIND_IN_SET(m.id, tbl_jadwal_ujian.id_mata_uji)');
        $this->datatables->group_by('id_ujian, token, nama_ujian, jumlah_soal, waktu, jenis');
        $this->datatables->add_column('action', anchor('admin/ujian-refresh-token/$1', '<i class="fa fa-refresh"></i> Refresh Token', array('class' => 'btn btn-secondary btn-sm')) . ' ' . anchor('admin/ujian-edit/$1', '<i class="fa fa-edit"></i> Edit', array('class' => 'btn btn-warning btn-sm')) . ' ' . anchor('admin/ujian-delete/$1', '<i class="fa fa-trash"></i> Hapus', array('class' => 'btn btn-danger btn-sm')), 'id_ujian');
        return $this->datatables->generate();
    }

    function getDtUjian($id)
    {
        //SELECT id_ujian, token, nama_ujian, GROUP_CONCAT(m.mata_uji) AS mata_uji, jumlah_soal, waktu, tgl_mulai,terlambat, jenis FROM `tbl_jadwal_ujian` LEFT JOIN tbl_mata_uji m ON FIND_IN_SET(m.id, tbl_jadwal_ujian.id_mata_uji);
        $this->db->select('id_ujian, token, tbl_jadwal_ujian.id_mata_uji, nama_ujian, GROUP_CONCAT(m.mata_uji) AS mata_uji, jumlah_soal, waktu, tgl_mulai,terlambat, jenis');
        $this->db->from('tbl_jadwal_ujian');
        $this->db->join('tbl_mata_uji m', 'FIND_IN_SET(m.id, tbl_jadwal_ujian.id_mata_uji)', 'LEFT');
        $this->db->where('id_ujian', $id);
        return $this->db->get();
    }

    function getJumlahSoalByMataUji()
    {
        $this->db->select('id, mata_uji, COUNT(id_soal) as jml_soal');
        $this->db->from('tbl_mata_uji');
        $this->db->join('tbl_soal', 'id = mata_uji_id', 'LEFT');
        $this->db->group_by('id');
        return $this->db->get();
    }

    function getHasilUjian()
    {
        $this->datatables->select('tbl_hasil_ujian.id_ujian,nama_ujian, GROUP_CONCAT(DISTINCT tbl_mata_uji.mata_uji) AS mata_uji, jumlah_soal, CONCAT(waktu, " Menit") waktu, tbl_jadwal_ujian.tgl_mulai');
        $this->datatables->from('tbl_hasil_ujian');
        $this->datatables->join('tbl_jadwal_ujian', 'tbl_hasil_ujian.id_ujian = tbl_jadwal_ujian.id_ujian');
        $this->datatables->join('tbl_mata_uji', 'FIND_IN_SET(tbl_mata_uji.id, tbl_jadwal_ujian.id_mata_uji)', 'LEFT');
        $this->datatables->group_by('tbl_hasil_ujian.id_ujian');
        return $this->datatables->generate();
    }

    function getHasilUjian_whereID_ujian($id)
    {
        $this->db->select('tbl_hasil_ujian.id_ujian,nama_ujian, GROUP_CONCAT(DISTINCT tbl_mata_uji.mata_uji) AS mata_uji, jumlah_soal, CONCAT(waktu, " Menit") waktu, tbl_jadwal_ujian.tgl_mulai, MIN(nilai) nilai_min, MAX(nilai) nilai_max, AVG(FORMAT(FLOOR(nilai),0)) nilai_avg');
        $this->db->from('tbl_hasil_ujian');
        $this->db->where('tbl_hasil_ujian.id_ujian', $id);
        $this->db->join('tbl_jadwal_ujian', 'tbl_hasil_ujian.id_ujian = tbl_jadwal_ujian.id_ujian');
        $this->db->join('tbl_mata_uji', 'FIND_IN_SET(tbl_mata_uji.id, tbl_jadwal_ujian.id_mata_uji)', 'LEFT');
        $this->db->group_by('tbl_hasil_ujian.id_ujian');
        return $this->db->get();
    }

    function getHasilUjian_whereID_ujian_nama($id)
    {
        $this->db->select('tbl_hasil_ujian.id_ujian,id_peserta_ujian,nama,jml_benar,nilai,(SELECT COUNT(DISTINCT nilai) FROM tbl_hasil_ujian u2 WHERE u2.nilai >= tbl_hasil_ujian.nilai) AS peringkat, list_id_mata_uji, jml_benar_per_id');
        $this->db->from('tbl_hasil_ujian');
        $this->db->where('tbl_hasil_ujian.id_ujian', $id);
        $this->db->join('tbl_pengguna', 'tbl_hasil_ujian.id_peserta_ujian = tbl_pengguna.id');
        return $this->db->get();
    }

    function getHasilUjian_whereID_ujian_dan_nama($id)
    {
        $this->datatables->select('tbl_hasil_ujian.id_ujian, nama, jml_benar, nilai, list_id_mata_uji, jml_benar_per_id');
        $this->datatables->from('tbl_hasil_ujian');
        $this->datatables->where('tbl_hasil_ujian.id_ujian', $id);
        $this->datatables->join('tbl_pengguna', 'tbl_hasil_ujian.id_peserta_ujian = tbl_pengguna.id');

        return $this->datatables->generate();
    }

    function getDataUjian_peserta_uji($id)
    {
        //SELECT a.id_ujian AS id_ujian, a.nama_ujian AS nama_ujian, GROUP_CONCAT(DISTINCT tbl_mata_uji.mata_uji) AS mata_uji, a.jumlah_soal AS jumlah_soal, CONCAT(a.tgl_mulai, " <br/> (", a.waktu, " Menit)") AS waktu, COUNT(h.id) AS ada, (SELECT h.status  FROM tbl_hasil_ujian h  WHERE h.id_peserta_ujian = '8a6e8295-904c-4231-8011-5f122ebc4c3e'  AND h.id_ujian = a.id_ujian) AS status_ujian FROM  tbl_jadwal_ujian a JOIN  tbl_mata_uji ON FIND_IN_SET(tbl_mata_uji.id, a.id_mata_uji) LEFT JOIN  tbl_hasil_ujian h ON h.id_peserta_ujian = '8a6e8295-904c-4231-8011-5f122ebc4c3e'  AND h.id_ujian = a.id_ujian GROUP BY  a.id_ujian, a.nama_ujian, a.jumlah_soal, a.tgl_mulai, a.waktu;
        $sql = 'SELECT a.id_ujian AS id_ujian, a.nama_ujian AS nama_ujian, GROUP_CONCAT(DISTINCT tbl_mata_uji.mata_uji) AS mata_uji, a.jumlah_soal AS jumlah_soal, CONCAT(a.tgl_mulai, " <br/> (", a.waktu, " Menit)") AS waktu, COUNT(h.id) AS ada, (SELECT h.status  FROM tbl_hasil_ujian h  WHERE h.id_peserta_ujian = ' . $id . '  AND h.id_ujian = a.id_ujian) AS status_ujian FROM  tbl_jadwal_ujian a JOIN  tbl_mata_uji ON FIND_IN_SET(tbl_mata_uji.id, a.id_mata_uji) LEFT JOIN  tbl_hasil_ujian h ON h.id_peserta_ujian = ' . $id . '  AND h.id_ujian = a.id_ujian GROUP BY  a.id_ujian, a.nama_ujian, a.jumlah_soal, a.tgl_mulai, a.waktu';
        $this->datatables
            ->select('id_ujian, nama_ujian, mata_uji, jumlah_soal, waktu, ada, status_ujian')
            ->from("(" . $sql . ") temp")
            ->unset_column('id_ujian')
            //->add_column('actions', 'Edit | Delete', 'id_ujian')
            ->unset_column('status_ujian')
            ->unset_column('ada');

        return $this->datatables->generate();
    }

    function getHasilUjian_peserta_uji($id, $id_peserta_ujian)
    {
        $this->db->select('id_peserta_ujian, h.id_ujian,nama,jml_benar,nilai,h.tgl_mulai,h.tgl_selesai, nilai AS peringkat, list_id_mata_uji, jml_benar_per_id');
        $this->db->from('tbl_hasil_ujian h');
        $this->db->where('h.id_ujian', $id);
        $this->db->where('h.id_peserta_ujian', $id_peserta_ujian);
        $this->db->join('tbl_pengguna p', 'h.id_peserta_ujian = p.id');
        $this->db->order_by('nilai', 'desc');
        return $this->db->get();
    }


    public function hasil_ujian($id_ujian, $id_peserta_ujian)
    {
        $this->db->select('*, UNIX_TIMESTAMP(tgl_selesai) as waktu_habis');
        $this->db->from('tbl_hasil_ujian');
        $this->db->where('id_ujian', $id_ujian);
        $this->db->where('id_peserta_ujian', $id_peserta_ujian);
        return $this->db->get();
    }

    public function getSoal($id_mata_uji, $order, $limit)
    {
        $this->db->select('id_soal, mata_uji_id, mata_uji, soal, file_soal, tipe_file, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban');
        $this->db->from('tbl_soal');
        $this->db->join('tbl_mata_uji', 'tbl_mata_uji.id = mata_uji_id');
        $this->db->where('mata_uji_id', $id_mata_uji);
        $this->db->order_by($order);
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function getJawaban($id_tes)
    {
        $this->db->select('list_jawaban');
        $this->db->from('tbl_hasil_ujian');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row();
    }

    public function ambilSoal($pc_urut_soal1, $pc_urut_soal_arr)
    {
        $this->db->select("*, {$pc_urut_soal1} AS jawaban");
        $this->db->from('tbl_soal');
        $this->db->where('id_soal', $pc_urut_soal_arr);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            // Return the first row as an object
            return $query->row();
        } else {
            return null; // Or handle the case when the question is not found
        }
    }
}
