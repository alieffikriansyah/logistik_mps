<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kebutuhan extends CI_Controller
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
        $data['title'] = "Data Kebutuhan";
        
        // Ambil parameter filter dari URL
        $jenis_filter = $this->input->get('jenis_filter');
        $tahun_filter = $this->input->get('tahun_filter');
        
        // Default tahun ke tahun sekarang jika tidak dipilih
        if (!$tahun_filter) {
            $tahun_filter = date('Y');
        }
        
        // QUERY DATA KEBUTUHAN sesuai format SQL Anda
        $this->db->select('
            k.id_kebutuhan, 
            k.id_barang, 
            k.tanggal, 
            k.kebutuhan, 
            k.status, 
            b.nama_barang, 
            b.stok, 
            b.merk, 
            b.lokasi, 
            s.nama_satuan, 
            j.nama_jenis, 
            j.id_jenis
        ');
        $this->db->from('kebutuhan k');
        $this->db->join('barang b', 'b.id_barang = k.id_barang', 'left');
        $this->db->join('satuan s', 's.id_satuan = b.satuan_id', 'left');
        $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
        
        // Filter tahun (gunakan parameter tahun_filter)
        if ($tahun_filter && $tahun_filter != '') {
            $this->db->where('YEAR(k.tanggal)', $tahun_filter);
        }
        
        // Filter jenis
        if ($jenis_filter && $jenis_filter != '') {
            $this->db->where('j.id_jenis', $jenis_filter);
        }
        
        // Order by
        $this->db->order_by('k.tanggal', 'DESC');
        $this->db->order_by('k.id_kebutuhan', 'DESC');
        
        // Eksekusi query
        $query = $this->db->get();
        $data['kebutuhan'] = $query->result_array();
        
        // Hitung persentase untuk setiap data
        foreach ($data['kebutuhan'] as &$row) {
            $stok = (float) $row['stok'];
            $kebutuhan_jumlah = (float) $row['kebutuhan'];
            
            // Rumus persentase: (kebutuhan ÷ stok) × 100
            if ($stok > 0) {
                $row['persentase'] = ($kebutuhan_jumlah / $stok) * 100;
            } else {
                $row['persentase'] = 0;
            }
            
            // Format persentase untuk display
            $row['persentase_formatted'] = number_format($row['persentase'], 2, ',', '.') . '%';
            
            // Tentukan level persentase
            $row['persentase_level'] = 'low';
            if ($row['persentase'] > 50) {
                $row['persentase_level'] = 'medium';
            }
            if ($row['persentase'] > 75) {
                $row['persentase_level'] = 'high';
            }
        }
        
        // Get data jenis untuk dropdown filter
        $data['jenis'] = $this->db->order_by('nama_jenis', 'ASC')->get('jenis')->result_array();
        
        // Get distinct tahun dari data kebutuhan untuk dropdown filter tahun
        $this->db->select('YEAR(tanggal) as tahun');
        $this->db->from('kebutuhan');
        $this->db->group_by('YEAR(tanggal)');
        $this->db->order_by('tahun', 'DESC');
        $data['tahun_list'] = $this->db->get()->result_array();
        
        $data['selected_jenis'] = $jenis_filter;
        $data['selected_tahun'] = $tahun_filter;
        
        $this->template->load('templates/dashboard', 'kebutuhan/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('kebutuhan', 'Jumlah Kebutuhan', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[1,0]');
    }

    public function add()
    {
        $data['title'] = "Tambah Data Kebutuhan";
        
        // Get data barang untuk dropdown
        $this->db->select('b.id_barang, b.nama_barang, b.stok, j.nama_jenis');
        $this->db->from('barang b');
        $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
        $this->db->order_by('b.nama_barang', 'ASC');
        $data['barang'] = $this->db->get()->result_array();

        $this->_validasi();
        
        if ($this->form_validation->run() == false) {
            $this->template->load('templates/dashboard', 'kebutuhan/add', $data);
        } else {
            $input = $this->input->post(null, true);
            
            // Format tanggal ke YYYY-MM-DD
            if (!empty($input['tanggal'])) {
                $input['tanggal'] = date('Y-m-d', strtotime($input['tanggal']));
            }
            
            // Debug: cek data yang akan diinsert
            // echo '<pre>'; print_r($input); die;
            
            $insert = $this->admin->insert('kebutuhan', $input);

            if ($insert) {
                set_pesan('Data kebutuhan berhasil disimpan');
                redirect('kebutuhan');
            } else {
                set_pesan('Gagal menyimpan data kebutuhan', false);
                redirect('kebutuhan/add');
            }
        }
    }

    public function edit($getId)
{
    $id = encode_php_tags($getId);
    
    $data['title'] = "Edit Data Kebutuhan";
    
    // Get data kebutuhan berdasarkan ID dengan JOIN ke tabel barang
    $this->db->select('
        k.id_kebutuhan,
        k.id_barang,
        k.tanggal,
        k.kebutuhan,
        k.status,
        b.nama_barang,
        b.stok,
        s.nama_satuan,
        j.nama_jenis
    ');
    $this->db->from('kebutuhan k');
    $this->db->join('barang b', 'b.id_barang = k.id_barang', 'left');
    $this->db->join('satuan s', 's.id_satuan = b.satuan_id', 'left');
    $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
    $this->db->where('k.id_kebutuhan', $id);
    
    $data['kebutuhan'] = $this->db->get()->row_array();
    
    if (!$data['kebutuhan']) {
        set_pesan('Data tidak ditemukan', false);
        redirect('kebutuhan');
    }
    
    // Get data barang untuk dropdown (semua barang)
    $this->db->select('b.id_barang, b.nama_barang, b.stok, j.nama_jenis');
    $this->db->from('barang b');
    $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
    $this->db->order_by('b.nama_barang', 'ASC');
    $data['barang'] = $this->db->get()->result_array();

    $this->_validasi();
    
    if ($this->form_validation->run() == false) {
        $this->template->load('templates/dashboard', 'kebutuhan/edit', $data);
    } else {
        $input = $this->input->post(null, true);
        
        // Format tanggal ke YYYY-MM-DD
        if (!empty($input['tanggal'])) {
            $input['tanggal'] = date('Y-m-d', strtotime($input['tanggal']));
        }
        
        $update = $this->admin->update('kebutuhan', 'id_kebutuhan', $id, $input);

        if ($update) {
            set_pesan('Data kebutuhan berhasil diupdate');
            redirect('kebutuhan');
        } else {
            set_pesan('Gagal mengupdate data kebutuhan', false);
            redirect('kebutuhan/edit/' . $id);
        }
    }
}

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        
        if ($this->admin->delete('kebutuhan', 'id_kebutuhan', $id)) {
            set_pesan('Data kebutuhan berhasil dihapus.');
        } else {
            set_pesan('Data kebutuhan gagal dihapus.', false);
        }
        redirect('kebutuhan');
    }

    // Function untuk mendapatkan stok barang
    public function get_stok_barang($id_barang)
    {
        $this->db->select('
            b.id_barang,
            b.nama_barang,
            b.stok,
            s.nama_satuan
        ');
        $this->db->from('barang b');
        $this->db->join('satuan s', 's.id_satuan = b.satuan_id', 'left');
        $this->db->where('b.id_barang', $id_barang);
        
        $data = $this->db->get()->row_array();
        
        if ($data) {
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan'
            ]);
        }
    }

    // Function untuk laporan kebutuhan per jenis
    public function laporan()
    {
        $data['title'] = "Laporan Kebutuhan";
        
        // Ambil parameter filter dari URL
        $jenis_filter = $this->input->get('jenis_filter');
        $tahun_filter = $this->input->get('tahun_filter');
        
        // Default tahun ke tahun sekarang jika tidak dipilih
        if (!$tahun_filter) {
            $tahun_filter = date('Y');
        }
        
        // QUERY LAPORAN PER JENIS
        $this->db->select('
            j.id_jenis,
            j.nama_jenis,
            COUNT(k.id_kebutuhan) as total_barang,
            SUM(k.kebutuhan) as total_kebutuhan
        ');
        $this->db->from('jenis j');
        $this->db->join('barang b', 'b.jenis_id = j.id_jenis', 'left');
        $this->db->join('kebutuhan k', 'k.id_barang = b.id_barang', 'left');
        
        // Filter tahun
        if ($tahun_filter && $tahun_filter != '') {
            $this->db->where('YEAR(k.tanggal)', $tahun_filter);
        }
        
        // Filter jenis
        if ($jenis_filter && $jenis_filter != '') {
            $this->db->where('j.id_jenis', $jenis_filter);
        }
        
        $this->db->group_by('j.id_jenis, j.nama_jenis');
        $this->db->order_by('j.nama_jenis', 'ASC');
        
        $data['laporan'] = $this->db->get()->result_array();
        
        // Get data jenis untuk dropdown filter
        $data['jenis'] = $this->db->order_by('nama_jenis', 'ASC')->get('jenis')->result_array();
        
        // Get distinct tahun dari data kebutuhan untuk dropdown filter tahun
        $this->db->select('YEAR(tanggal) as tahun');
        $this->db->from('kebutuhan');
        $this->db->group_by('YEAR(tanggal)');
        $this->db->order_by('tahun', 'DESC');
        $data['tahun_list'] = $this->db->get()->result_array();
        
        $data['selected_jenis'] = $jenis_filter;
        $data['selected_tahun'] = $tahun_filter;
        
        $this->template->load('templates/dashboard', 'kebutuhan/laporan', $data);
    }
}