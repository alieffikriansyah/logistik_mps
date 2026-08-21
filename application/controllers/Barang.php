<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
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
        $data['title'] = "Barang";
        
        // Ambil parameter filter dari URL
        $jenis_filter = $this->input->get('jenis_filter');
        
        // Get data barang dengan filter jika ada
        $data['barang'] = $this->admin->getBarang($jenis_filter);
        
        // Get data jenis untuk dropdown filter
        $data['jenis'] = $this->admin->get('jenis');
        $data['selected_jenis'] = $jenis_filter; // Untuk menandai filter yang aktif
        
        $this->template->load('templates/dashboard', 'barang/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('jenis_id', 'Jenis Barang', 'required');
        $this->form_validation->set_rules('satuan_id', 'Satuan Barang', 'required');
        $this->form_validation->set_rules('merk', 'Merk', 'trim|max_length[100]');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'trim|max_length[100]');
    }

    public function add()
    {
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Barang";
            $data['jenis'] = $this->admin->get('jenis');
            $data['satuan'] = $this->admin->get('satuan');

            // Mengenerate ID Barang
            $kode_terakhir = $this->admin->getMax('barang', 'id_barang');
            $kode_tambah = substr($kode_terakhir, -6, 6);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 6, '0', STR_PAD_LEFT);
            $data['id_barang'] = 'B' . $number;

            $this->template->load('templates/dashboard', 'barang/add', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Handle upload foto
            $foto_barang = $this->_upload_foto();
            if ($foto_barang !== false) {
                $input['foto_barang'] = $foto_barang;
            } else {
                $input['foto_barang'] = null; // atau kosongkan jika upload gagal
            }
            
            // Set default value jika kosong
            $input['merk'] = !empty($input['merk']) ? $input['merk'] : '';
            $input['lokasi'] = !empty($input['lokasi']) ? $input['lokasi'] : '';
            
            $insert = $this->admin->insert('barang', $input);

            if ($insert) {
                set_pesan('Data berhasil disimpan');
                redirect('barang');
            } else {
                set_pesan('Gagal menyimpan data', false);
                redirect('barang/add');
            }
        }
    }

    private function _upload_foto()
    {
        if (empty($_FILES['foto_barang']['name'])) {
            return false;
        }

        $config['upload_path'] = './assets/uploads/fotobarang/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; // 2MB
        $config['file_name'] = $this->_rename_file($_FILES['foto_barang']['name']);
        $config['overwrite'] = false;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto_barang')) {
            return false;
        } else {
            return $this->upload->data('file_name');
        }
    }

    private function _rename_file($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        // Bersihkan nama file dari karakter khusus
        $clean_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        
        $new_filename = $clean_name . '.' . $ext;
        $counter = 1;
        
        // Cek jika file sudah ada, tambahkan angka
        while (file_exists('./assets/uploads/fotobarang/' . $new_filename)) {
            $new_filename = $clean_name . '(' . $counter . ').' . $ext;
            $counter++;
        }
        
        return $new_filename;
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit Barang";
            $data['jenis'] = $this->admin->get('jenis');
            $data['satuan'] = $this->admin->get('satuan');
            $data['barang'] = $this->admin->get('barang', ['id_barang' => $id]);
            
            if (!$data['barang']) {
                set_pesan('Data tidak ditemukan', false);
                redirect('barang');
            }
            
            $this->template->load('templates/dashboard', 'barang/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Handle upload foto baru
            if (!empty($_FILES['foto_barang']['name'])) {
                $foto_barang = $this->_upload_foto();
                if ($foto_barang !== false) {
                    $input['foto_barang'] = $foto_barang;
                    
                    // Hapus foto lama jika ada
                    $barang_lama = $this->admin->get('barang', ['id_barang' => $id]);
                    if (!empty($barang_lama['foto_barang'])) {
                        $file_path = './assets/uploads/fotobarang/' . $barang_lama['foto_barang'];
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }
                }
            } else {
                // Jika tidak upload foto baru, tetap pertahankan foto lama
                unset($input['foto_barang']);
            }
            
            // Set default value jika kosong
            $input['merk'] = !empty($input['merk']) ? $input['merk'] : '';
            $input['lokasi'] = !empty($input['lokasi']) ? $input['lokasi'] : '';

            $update = $this->admin->update('barang', 'id_barang', $id, $input);

            if ($update) {
                set_pesan('Data berhasil diupdate');
                redirect('barang');
            } else {
                set_pesan('Gagal mengupdate data', false);
                redirect('barang/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        
        // Hapus foto barang jika ada
        $barang = $this->admin->get('barang', ['id_barang' => $id]);
        if ($barang && !empty($barang['foto_barang'])) {
            $file_path = './assets/uploads/fotobarang/' . $barang['foto_barang'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        if ($this->admin->delete('barang', 'id_barang', $id)) {
            set_pesan('Data berhasil dihapus.');
        } else {
            set_pesan('Data gagal dihapus.', false);
        }
        redirect('barang');
    }

    public function getstok($getId)
    {
        $id = encode_php_tags($getId);
        $query = $this->admin->cekStok($id);
        output_json($query);
    }
}