<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangmasuk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
        $this->load->database(); // Load database untuk query builder
    }

    public function index()
    {
        $data['title'] = "Barang Masuk";
        
        // Query builder langsung di controller - hanya tampilkan status != 8
        $this->db->select('barang_masuk.*, barang.nama_barang, supplier.nama_supplier, satuan.nama_satuan, user.nama');
        $this->db->from('barang_masuk');
        $this->db->join('barang', 'barang_masuk.barang_id = barang.id_barang');
        $this->db->join('supplier', 'barang_masuk.supplier_id = supplier.id_supplier');
        $this->db->join('satuan', 'satuan_id = id_satuan');
        $this->db->join('user', 'user_id = id_user');
        $this->db->where('barang_masuk.status !=', 8); // Hanya data aktif
        $this->db->order_by('barang_masuk.id_barang_masuk', 'DESC');
        $data['barangmasuk'] = $this->db->get()->result_array();

        $this->template->load('templates/dashboard', 'barang_masuk/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
        $this->form_validation->set_rules('barang_id', 'Barang', 'required');
        $this->form_validation->set_rules('jumlah_masuk', 'Jumlah Masuk', 'required|trim|numeric|greater_than[0]');
          // Validasi untuk keterangan
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('lokasi', 'Lokasi', 'trim');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Barang Masuk";
            $data['supplier'] = $this->admin->get('supplier');
            $data['barang'] = $this->admin->get('barang');
            

            // Mendapatkan dan men-generate kode transaksi barang masuk
            $kode = 'T-BM-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_masuk', 'id_barang_masuk', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_masuk'] = $kode . $number;

            $this->template->load('templates/dashboard', 'barang_masuk/add', $data);
        } else {
            $input = $this->input->post(null, true);
            // Tambahkan status = 1 untuk data baru
            $input['status'] = 1;

             // Pastikan field keterangan ada dalam input
            if (!isset($input['keterangan'])) {
                $input['keterangan'] = ''; // Default value jika kosong
            }

            // Pastikan field keterangan ada dalam input
            if (!isset($input['lokasi'])) {
                $input['lokasi'] = ''; // Default value jika kosong
            }
           
            $insert = $this->admin->insert('barang_masuk', $input);

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('barangmasuk');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barangmasuk/add');
            }
        }
    }

    // Tambahkan function edit setelah function add
public function edit($getId)
{
    $id = encode_php_tags($getId);
    
    $this->_validasi();
    
    if ($this->form_validation->run() == false) {
        $data['title'] = "Edit Barang Masuk";
        $data['supplier'] = $this->admin->get('supplier');
        $data['barang'] = $this->admin->get('barang');
        
        // Ambil data barang masuk yang akan diedit
        $data['barang_masuk'] = $this->admin->get('barang_masuk', ['id_barang_masuk' => $id]);
        
        // Ambil data barang untuk mendapatkan satuan
        $barang_id = $data['barang_masuk']['barang_id'];
        $data['barang_detail'] = $this->admin->get('barang', ['id_barang' => $barang_id]);
        
        // Ambil data satuan dari barang
        $data['satuan'] = $this->admin->get('satuan', ['id_satuan' => $data['barang_detail']['satuan_id']]);
        
        $this->template->load('templates/dashboard', 'barang_masuk/edit', $data);
    } else {
        $input = $this->input->post(null, true);
        
        // Pastikan field keterangan dan lokasi ada dalam input
        if (!isset($input['keterangan'])) {
            $input['keterangan'] = '';
        }
        if (!isset($input['lokasi'])) {
            $input['lokasi'] = '';
        }
        
        $update = $this->admin->update('barang_masuk', 'id_barang_masuk', $id, $input);
        
        if ($update) {
            set_pesan('Data berhasil diupdate.');
            redirect('barangmasuk');
        } else {
            set_pesan('Gagal mengupdate data.', false);
            redirect('barangmasuk/edit/' . $id);
        }
    }
}

    // public function delete($getId)
    // {
    //     $id = encode_php_tags($getId);

    //     // Ambil data barang masuk yang akan dihapus
    //     $barang_masuk = $this->admin->get('barang_masuk', ['id_barang_masuk' => $id]);

    //     if ($barang_masuk) {
    //         // Update status menjadi 8 (dihapus) daripada menghapus fisik data
    //         if ($this->admin->update('barang_masuk', 'id_barang_masuk', $id, ['status' => 8])) {
    //             set_pesan('Data berhasil dihapus.');
    //         } else {
    //             set_pesan('Gagal menghapus data.', false);
    //         }
    //     } else {
    //         set_pesan('Data tidak ditemukan.', false);
    //     }

    //     redirect('barangmasuk');
    // }
 public function delete($getId)  // ← DI Barangmasuk.php
{
    $id = encode_php_tags($getId);

    // 1. Ambil data barang masuk dengan JOIN untuk dapatkan barang_id
    $this->db->select('bm.*, b.id_barang');
    $this->db->from('barang_masuk bm');
    $this->db->join('barang b', 'bm.barang_id = b.id_barang');
    $this->db->where('bm.id_barang_masuk', $id);
    $barang_masuk = $this->db->get()->row_array();

    if ($barang_masuk) {
        // Debug - cek data
        log_message('debug', 'Data BM: ' . print_r($barang_masuk, true));
        
        $stok_masuk = (int)$barang_masuk['jumlah_masuk']; 
        $id_barang = $barang_masuk['barang_id'];
        
        // 2. VALIDASI data lengkap
        if ($stok_masuk > 0 && $id_barang) {
            // 3. KURANGI STOK
            $this->db->set('stok', 'stok - ' . $stok_masuk, FALSE);
            $this->db->where('id_barang', $id_barang);
            $update_stok = $this->db->update('barang');
        } else {
            $update_stok = true; // Skip update jika data kosong
        }
        
        // 4. HAPUS barang masuk
        if ($update_stok && $this->admin->delete('barang_masuk', 'id_barang_masuk', $id)) {
            set_pesan('Data berhasil dihapus dan stok diperbarui.');
        } else {
            set_pesan('Gagal menghapus data atau update stok.', false);
        }
    } else {
        set_pesan('Data tidak ditemukan.', false);
    }

    redirect('barangmasuk');
}



}