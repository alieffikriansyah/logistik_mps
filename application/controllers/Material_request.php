<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Material_request extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Material Request";

        // Ambil data user dari session
        $user_data = $this->session->userdata('login_session');
        $id_user = $user_data['user'];

        // BUILD QUERY MANUAL
        $query = "SELECT mr.*, b.nama_barang, j.nama_jenis, s.nama_satuan, u.nama, u.role
                  FROM material_request mr
                  LEFT JOIN barang b ON b.id_barang = mr.id_barang
                  LEFT JOIN jenis j ON j.id_jenis = mr.id_jenis
                  LEFT JOIN satuan s ON s.id_satuan = mr.id_satuan
                  LEFT JOIN user u ON u.id_user = mr.id_user";
        
        // Tambahkan WHERE condition
        if ($id_user != '1') {
            $query .= " WHERE mr.id_user = " . $this->db->escape($id_user);
        }
        
        $query .= " ORDER BY mr.id_mr DESC";
        
        $data['material_request'] = $this->db->query($query)->result_array();
        $data['user_id'] = $id_user;

        // Status mapping
        foreach ($data['material_request'] as &$row) {
            switch ($row['realisasi']) {
                case 0:
                    $row['status_label'] = '<span class="badge badge-secondary">Baru</span>';
                    break;
                case 1:
                    $row['status_label'] = '<span class="badge badge-info">Proses</span>';
                    break;
                case 2:
                    $row['status_label'] = '<span class="badge badge-danger">Ditolak</span>';
                    break;
                case 3:
                    $row['status_label'] = '<span class="badge badge-success">Selesai</span>';
                    break;
                default:
                    $row['status_label'] = '<span class="badge badge-light">Tidak Diketahui</span>';
                    break;
            }
        }

        $this->template->load('templates/dashboard', 'material_request/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('id_jenis', 'Jenis', 'required');
        $this->form_validation->set_rules('id_satuan', 'Satuan', 'required');
        $this->form_validation->set_rules('stok', 'Stok', 'required|trim|numeric|greater_than[0]');
        $this->form_validation->set_rules('link_rekomendasi', 'Link Rekomendasi', 'trim|valid_url');
        
        // Custom validation untuk memastikan salah satu diisi
        $this->form_validation->set_rules('id_barang', 'Barang', 'callback_check_barang_or_custom');
    }

    // Custom validation function
    public function check_barang_or_custom($str)
    {
        $barang_minta = $this->input->post('barang_minta');
        
        // Jika kedua-duanya kosong
        if (empty($str) && empty($barang_minta)) {
            $this->form_validation->set_message('check_barang_or_custom', 'Pilih barang dari daftar atau isi nama barang manual.');
            return false;
        }
        
        // Jika kedua-duanya terisi
        if (!empty($str) && !empty($barang_minta)) {
            $this->form_validation->set_message('check_barang_or_custom', 'Hanya boleh memilih barang dari daftar ATAU mengisi nama barang manual, tidak boleh keduanya.');
            return false;
        }
        
        return true;
    }

    public function add()
    {
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title']  = "Tambah Material Request";
            $data['barang'] = $this->admin->get('barang');
            $data['jenis']  = $this->admin->get('jenis');
            $data['satuan'] = $this->admin->get('satuan');

            $this->template->load('templates/dashboard', 'material_request/add', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Ambil id_user dari login_session
            $user_data = $this->session->userdata('login_session');
            $input['id_user']   = $user_data['user'];
            $input['realisasi'] = 0;
            $input['tanggal']   = date('Y-m-d H:i:s');
            
            // Jika memilih barang dari daftar, set barang_minta menjadi null
            if (!empty($input['id_barang'])) {
                $input['barang_minta'] = null;
            }
            // Jika mengisi barang manual, set id_barang menjadi null
            elseif (!empty($input['barang_minta'])) {
                $input['id_barang'] = null;
                // Untuk barang custom, kita tetap butuh jenis dan satuan
                // tapi id_barang akan null karena tidak ada di master
            }

            $insert = $this->admin->insert('material_request', $input);
            if ($insert) {
                set_pesan('Data berhasil disimpan');
                redirect('material_request');
            } else {
                set_pesan('Data gagal disimpan', false);
                redirect('material_request/add');
            }
        }
    }

    // Method edit juga perlu disesuaikan
    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title']  = "Edit Material Request";
            $data['mr']     = $this->admin->get('material_request', ['id_mr' => $id]);
            $data['barang'] = $this->admin->get('barang');
            $data['jenis']  = $this->admin->get('jenis');
            $data['satuan'] = $this->admin->get('satuan');
            $data['status_list'] = [
                0 => 'Baru',
                1 => 'Proses',
                2 => 'Ditolak',
                3 => 'Selesai'
            ];

            $this->template->load('templates/dashboard', 'material_request/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Logic yang sama seperti di add
            if (!empty($input['id_barang'])) {
                $input['barang_minta'] = null;
            }
            elseif (!empty($input['barang_minta'])) {
                $input['id_barang'] = null;
            }

            $update = $this->admin->update('material_request', 'id_mr', $id, $input);

            if ($update) {
                set_pesan('Data berhasil diperbarui');
                redirect('material_request');
            } else {
                set_pesan('Data gagal diperbarui', false);
                redirect('material_request/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('material_request', 'id_mr', $id)) {
            set_pesan('Data berhasil dihapus.');
        } else {
            set_pesan('Data gagal dihapus.', false);
        }
        redirect('material_request');
    }

    public function update_realisasi($id_mr)
    {
        $realisasi = $this->input->post('realisasi');
        
        $data = ['realisasi' => $realisasi];
        $update = $this->admin->update('material_request', 'id_mr', $id_mr, $data);

        if ($update) {
            set_pesan('Status realisasi berhasil diupdate');
        } else {
            set_pesan('Gagal mengupdate status realisasi', false);
        }
        redirect('material_request');
    }
}