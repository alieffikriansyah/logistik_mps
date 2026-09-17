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
        $query = "SELECT mr.*, b.nama_barang, j.nama_jenis, s.nama_satuan, u.nama, u.role,
                  (SELECT COUNT(*) FROM file_material_request f WHERE f.id_mr = mr.id_mr) as total_files
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
        $tipe = $this->input->post('tipe_permintaan');
        if ($tipe == 'daftar' || (empty($tipe) && !empty($this->input->post('id_barang')))) {
            $this->form_validation->set_rules('id_barang', 'Barang dari Daftar', 'required|trim');
        } else {
            $this->form_validation->set_rules('barang_minta', 'Nama Barang yang Diminta', 'required|trim');
            $this->form_validation->set_rules('id_jenis', 'Jenis Barang', 'required');
            $this->form_validation->set_rules('id_satuan', 'Satuan Barang', 'required');
            $this->form_validation->set_rules('link_rekomendasi', 'Link Rekomendasi', 'trim|valid_url');
        }

        $this->form_validation->set_rules('stok', 'Jumlah Diminta', 'required|trim|numeric|greater_than[0]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('jenis_mr', 'Jenis MR', 'required|trim|in_list[kontrak,si,biasa,lainnya]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'trim');
    }

    public function add()
    {
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title']  = "Tambah Material Request";
            $data['barang'] = $this->admin->getBarang();
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

            $tipe = $input['tipe_permintaan'] ?? (!empty($input['id_barang']) ? 'daftar' : 'baru');
            unset($input['tipe_permintaan']);
            
            // Jika memilih barang dari daftar
            if ($tipe == 'daftar' && !empty($input['id_barang'])) {
                $barang = $this->admin->get('barang', ['id_barang' => $input['id_barang']]);
                if ($barang) {
                    $input['id_jenis']  = $barang['jenis_id'];
                    $input['id_satuan'] = $barang['satuan_id'];
                }
                $input['barang_minta'] = null;
                $input['link_rekomendasi'] = null;
            } else {
                $input['id_barang'] = null;
            }

            // Bersihkan perihal jika jenis_mr bukan lainnya
            if (($input['jenis_mr'] ?? '') !== 'lainnya') {
                $input['perihal'] = null;
            }

            $insert = $this->admin->insert('material_request', $input);
            if ($insert) {
                $id_mr = $this->db->insert_id();
                // Upload file lampiran jika ada
                $this->_upload_files($id_mr, 'lampiran_files');

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
            $data['barang'] = $this->admin->getBarang();
            $data['jenis']  = $this->admin->get('jenis');
            $data['satuan'] = $this->admin->get('satuan');
            $data['files']  = $this->db->order_by('id_file_mr', 'DESC')->get_where('file_material_request', ['id_mr' => $id])->result_array();
            $data['status_list'] = [
                0 => 'Baru',
                1 => 'Proses',
                2 => 'Ditolak',
                3 => 'Selesai'
            ];

            $this->template->load('templates/dashboard', 'material_request/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            
            $tipe = $input['tipe_permintaan'] ?? (!empty($input['id_barang']) ? 'daftar' : 'baru');
            unset($input['tipe_permintaan']);

            if ($tipe == 'daftar' && !empty($input['id_barang'])) {
                $barang = $this->admin->get('barang', ['id_barang' => $input['id_barang']]);
                if ($barang) {
                    $input['id_jenis']  = $barang['jenis_id'];
                    $input['id_satuan'] = $barang['satuan_id'];
                }
                $input['barang_minta'] = null;
                $input['link_rekomendasi'] = null;
            } else {
                $input['id_barang'] = null;
            }

            // Bersihkan perihal jika jenis_mr bukan lainnya
            if (($input['jenis_mr'] ?? '') !== 'lainnya') {
                $input['perihal'] = null;
            }

            $update = $this->admin->update('material_request', 'id_mr', $id, $input);

            if ($update) {
                // Upload file lampiran tambahan jika ada
                $this->_upload_files($id, 'lampiran_files');

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

        // Hapus file-file fisik dan data lampiran terkait
        $files = $this->db->get_where('file_material_request', ['id_mr' => $id])->result_array();
        foreach ($files as $f) {
            $filepath = './assets/uploads/material_request/' . $f['nama_file'];
            if (file_exists($filepath)) {
                @unlink($filepath);
            }
        }
        $this->db->delete('file_material_request', ['id_mr' => $id]);

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

    // ==========================================
    // MULTI FILE UPLOAD & MANAGEMENT METHODS
    // ==========================================

    private function _upload_files($id_mr, $field_name = 'lampiran_files')
    {
        if (empty($_FILES[$field_name]['name'])) {
            return [];
        }

        $files = $_FILES[$field_name];
        if (!is_array($files['name'])) {
            $files['name']     = [$files['name']];
            $files['type']     = [$files['type']];
            $files['tmp_name'] = [$files['tmp_name']];
            $files['error']    = [$files['error']];
            $files['size']     = [$files['size']];
        }

        $file_count = count($files['name']);
        $has_valid = false;
        for ($i = 0; $i < $file_count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK && !empty($files['name'][$i])) {
                $has_valid = true;
                break;
            }
        }
        if (!$has_valid) {
            return [];
        }

        $upload_path = './assets/uploads/material_request/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $this->load->library('upload');
        $uploaded = [];

        for ($i = 0; $i < $file_count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK && !empty($files['name'][$i])) {
                $_FILES['single_lampiran']['name']     = $files['name'][$i];
                $_FILES['single_lampiran']['type']     = $files['type'][$i];
                $_FILES['single_lampiran']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['single_lampiran']['error']    = $files['error'][$i];
                $_FILES['single_lampiran']['size']     = $files['size'][$i];

                $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                $new_filename = $this->_rename_file($files['name'][$i], $id_mr);

                $config = [
                    'upload_path'   => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png|gif|webp|pdf|xls|xlsx|csv|doc|docx',
                    'max_size'      => 10240, // 10MB
                    'file_name'     => $new_filename,
                    'overwrite'     => false
                ];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('single_lampiran')) {
                    $upload_data = $this->upload->data();
                    $file_record = [
                        'id_mr'       => $id_mr,
                        'nama_file'   => $upload_data['file_name'],
                        'nama_asli'   => $files['name'][$i],
                        'tipe_file'   => $this->_get_file_type($ext),
                        'ukuran_file' => round($upload_data['file_size'], 2),
                        'created_at'  => date('Y-m-d H:i:s')
                    ];
                    $this->db->insert('file_material_request', $file_record);
                    $uploaded[] = $file_record;
                }
            }
        }

        return $uploaded;
    }

    private function _rename_file($filename, $id_mr = null)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $clean_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        $clean_name = substr($clean_name, 0, 40);
        $prefix = $id_mr ? 'mr_' . $id_mr . '_' : 'mr_';
        return $prefix . date('Ymd_His') . '_' . uniqid() . '_' . $clean_name . '.' . $ext;
    }

    private function _get_file_type($ext)
    {
        $ext = strtolower($ext);
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return 'image';
        } elseif ($ext == 'pdf') {
            return 'pdf';
        } elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) {
            return 'excel';
        } elseif (in_array($ext, ['doc', 'docx'])) {
            return 'word';
        }
        return 'other';
    }

    // Endpoint AJAX untuk upload file dari modal di tabel
    public function upload_files_ajax()
    {
        $id_mr = $this->input->post('id_mr');
        if (!$id_mr) {
            output_json(['status' => false, 'message' => 'ID MR tidak valid']);
            return;
        }

        $uploaded = $this->_upload_files($id_mr, 'files');
        if (!empty($uploaded)) {
            $files = $this->db->order_by('id_file_mr', 'DESC')->get_where('file_material_request', ['id_mr' => $id_mr])->result_array();
            foreach ($files as &$f) {
                $f['url'] = base_url('assets/uploads/material_request/' . $f['nama_file']);
            }
            output_json([
                'status'  => true,
                'message' => count($uploaded) . ' file berhasil diupload!',
                'files'   => $files
            ]);
        } else {
            output_json([
                'status'  => false,
                'message' => 'Gagal mengupload file atau format file tidak diizinkan. (Hanya gambar, PDF, Excel, dan Word yang diizinkan, maks 10MB).'
            ]);
        }
    }

    // Endpoint AJAX untuk mengambil daftar file berdasarkan id_mr
    public function get_files_ajax($id_mr)
    {
        $id = encode_php_tags($id_mr);
        $files = $this->db->order_by('id_file_mr', 'DESC')->get_where('file_material_request', ['id_mr' => $id])->result_array();
        foreach ($files as &$f) {
            $f['url'] = base_url('assets/uploads/material_request/' . $f['nama_file']);
        }
        output_json(['status' => true, 'files' => $files]);
    }

    // Endpoint AJAX untuk menghapus satu file
    public function delete_file_ajax($id_file)
    {
        $id = encode_php_tags($id_file);
        $file = $this->db->get_where('file_material_request', ['id_file_mr' => $id])->row_array();
        if ($file) {
            $filepath = './assets/uploads/material_request/' . $file['nama_file'];
            if (file_exists($filepath)) {
                @unlink($filepath);
            }
            $this->db->delete('file_material_request', ['id_file_mr' => $id]);
            output_json(['status' => true, 'message' => 'File berhasil dihapus']);
        } else {
            output_json(['status' => false, 'message' => 'File tidak ditemukan']);
        }
    }
}