<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangkeluar extends CI_Controller
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
        $data['title'] = "Barang Keluar";
        
        // Ambil parameter filter dari URL
        $jenis_filter = $this->input->get('jenis_filter');
        
        // Get data barang keluar dengan filter jika ada
        $data['barangkeluar'] = $this->admin->barang_keluar_filter($jenis_filter);
        
        // Get data jenis untuk dropdown filter
        $data['jenis'] = $this->admin->get('jenis');
        $data['selected_jenis'] = $jenis_filter; // Untuk menandai filter yang aktif
        
        $this->template->load('templates/dashboard', 'barang_keluar/data', $data);
    }

   private function _validasi()
    {
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required|trim');
        $this->form_validation->set_rules('barang_id', 'Barang', 'required');

        $input = $this->input->post('barang_id', true);
        // Mendapatkan data barang berdasarkan id_barang
        $result = $this->admin->get('barang', ['id_barang' => $input]);

        // Periksa apakah hasilnya tidak null sebelum mengakses 'stok'
        if ($result !== null) {
            $stok = $result['stok'];
        } else {
            // Handle jika data tidak ditemukan atau mengembalikan null
            $stok = 0;
        }

        $stok_valid = $stok + 1;

        $this->form_validation->set_rules(
            'jumlah_keluar',
            'Jumlah Keluar',
            "required|trim|numeric|greater_than[0]|less_than[{$stok_valid}]",
            [
                'less_than' => "Jumlah Keluar tidak boleh lebih dari {$stok}"
            ]
        );
        
        // Tambahkan validasi untuk keterangan
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|max_length[500]');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Barang Keluar";
            $data['barang'] = $this->admin->get('barang', null, ['stok >' => 0]);

            // Mendapatkan dan men-generate kode transaksi barang keluar
            $kode = 'T-BK-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_keluar', 'id_barang_keluar', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_keluar'] = $kode . $number;

            $this->template->load('templates/dashboard', 'barang_keluar/add', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Debug: lihat data yang dikirim
            echo "<pre>";
            print_r($input);
            echo "</pre>";
            // die(); // Uncomment untuk debug
            
            // SET KODE = 1
            $input['kode'] = 1;
            
            // Pastikan field keterangan ada dalam input
            if (!isset($input['keterangan'])) {
                $input['keterangan'] = ''; // Default value jika kosong
            }
            
            $insert = $this->admin->insert('barang_keluar', $input);

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('barangkeluar');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barangkeluar/add');
            }
        }
    }


public function delete($getId)
{
    $id = encode_php_tags($getId);

    // Ambil data barang keluar yang akan dihapus
    $barang_keluar = $this->admin->get('barang_keluar', ['id_barang_keluar' => $id]);

    if ($barang_keluar) {
        // Ambil jumlah barang yang akan dikembalikan
        $jumlah_keluar = $barang_keluar['jumlah_keluar'];
        // Ambil id barang terkait
        $barang_id = $barang_keluar['barang_id'];

        // Ambil stok barang sebelum dihapus
        $barang = $this->admin->get('barang', ['id_barang' => $barang_id]);
        $stok_sebelumnya = $barang['stok'];

        // Mengembalikan stok barang yang dihapus
        $this->admin->update('barang', 'id_barang', $barang_id, ['stok' => $stok_sebelumnya + $jumlah_keluar]);

        // Hapus data barang keluar dari database
        if ($this->admin->delete('barang_keluar', 'id_barang_keluar', $id)) {
            set_pesan('Data berhasil dihapus.');
        } else {
            set_pesan('Gagal menghapus data.', false);
        }
    } else {
        set_pesan('Data tidak ditemukan.', false);
    }

    redirect('barangkeluar');
}

public function print_serah_terima($id)
{
    // Get data barang keluar berdasarkan ID
    $data['transaksi'] = $this->admin->getBarangKeluarById($id);
    
    if (!$data['transaksi']) {
        set_pesan('Data transaksi tidak ditemukan!', false);
        redirect('barangkeluar');
    }
    
    $data['title'] = "Berita Serah Terima Barang";
    
    // Load view untuk print
    $this->load->view('barang_keluar/print_serah_terima', $data);
}

// Method untuk get data by ID (revisi)
public function getBarangKeluarById($id)
{
    $this->db->select('bk.*, b.nama_barang, b.id_barang, b.foto_barang, s.nama_satuan, j.nama_jenis');
    $this->db->from('barang_keluar bk');
    $this->db->join('barang b', 'bk.barang_id = b.id_barang');
    $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
    $this->db->join('jenis j', 'b.jenis_id = j.id_jenis');
    $this->db->where('bk.id_barang_keluar', $id);
    return $this->db->get()->row_array();
}

}
