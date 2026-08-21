<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SuratJalan extends CI_Controller
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
    $data['title'] = "Surat Jalan";
    
    $this->db->select('
        surat_jalan.*, 
        COUNT(ds.id_detail_surat_jalan) as total_items, 
        dokumentasi_surat_jalan.nama_file,
        b.nama_barang,
        s.nama_supplier,
        bm.jumlah_masuk,
        satuan.nama_satuan
    ');
    $this->db->from('surat_jalan');
    $this->db->join('detail_surat_jalan ds', 'ds.id_surat_jalan = surat_jalan.id_surat_jalan AND ds.status != 8', 'left');
    $this->db->join('dokumentasi_surat_jalan', 'dokumentasi_surat_jalan.id_dokumentasi_surat_jalan = surat_jalan.id_dokumentasi_surat_jalan', 'left');
    
    // TAMBAHKAN JOIN UNTUK BARANG, SUPPLIER, DAN DETAIL LAINNYA
    $this->db->join('barang_masuk bm', 'bm.id_barang_masuk = ds.id_barang_masuk', 'left');
    $this->db->join('barang b', 'b.id_barang = bm.barang_id', 'left');
    $this->db->join('supplier s', 's.id_supplier = bm.supplier_id', 'left');
    $this->db->join('satuan', 'satuan.id_satuan = b.satuan_id', 'left');
    
    $this->db->where('surat_jalan.status !=', 8);
    $this->db->group_by('surat_jalan.id_surat_jalan');
    $this->db->order_by('surat_jalan.id_surat_jalan', 'DESC');
    
    $data['surat_jalan'] = $this->db->get()->result_array();
    
    $this->template->load('templates/dashboard', 'surat_jalan/data', $data);
}

public function add()
{
    $data['title'] = "Tambah Surat Jalan";
    
    // Load data barang_masuk
    $this->db
        ->select('bm.*, b.nama_barang, s.nama_supplier, b.id_barang, bm.jumlah_masuk, bm.tanggal_masuk, satuan.nama_satuan')
        ->from('barang_masuk bm')
        ->join('barang b', 'b.id_barang = bm.barang_id', 'left')
        ->join('supplier s', 's.id_supplier = bm.supplier_id', 'left')
        ->join('satuan', 'satuan.id_satuan = b.satuan_id', 'left')
        ->where('bm.status', 1);
    $data['barang_masuk'] = $this->db->get()->result_array();

    // Set validation rules
    $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|trim');
    $this->form_validation->set_rules('id_barang_masuk[]', 'Barang Masuk', 'required');

    if ($this->form_validation->run() == false) {
        $this->template->load('templates/dashboard', 'surat_jalan/add', $data);
    } else {
        // Process the form data
        $input = $this->input->post(null, true);
        
        $foto_bukti = $this->_upload_foto_bukti();
        $dokumentasi_ids = $this->_upload_dokumentasi();

        $surat_data = [
            'keterangan' => $input['keterangan'],
            'status' => 1,
            'tanggal' => date('Y-m-d H:i:s'),
            'id_dokumentasi_surat_jalan' => !empty($dokumentasi_ids) ? $dokumentasi_ids[0] : null,
            'foto_bukti' => $foto_bukti
        ];

        if ($this->admin->insert('surat_jalan', $surat_data)) {
            $id_surat_jalan = $this->db->insert_id();

            if (!empty($input['id_barang_masuk'])) {
                foreach ($input['id_barang_masuk'] as $id_barang_masuk) {
                    $this->admin->insert('detail_surat_jalan', [
                        'id_surat_jalan' => $id_surat_jalan,
                        'id_barang_masuk' => $id_barang_masuk,
                        'status' => 9
                    ]);
                    
                    $this->admin->update('barang_masuk', 'id_barang_masuk', $id_barang_masuk, ['status' => 9]);
                }
            }
            
            set_pesan('Surat Jalan berhasil ditambah');
            redirect('suratjalan');
        } else {
            set_pesan('Gagal menambah Surat Jalan', false);
            redirect('suratjalan/add');
        }
    }
}


    private function _upload_foto_bukti()
    {
        if (empty($_FILES['foto_bukti']['name'])) return null;
        $config['upload_path']   = './assets/uploads/foto_bukti_surat_jalan/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size']      = 2048;
        $config['file_name']     = $this->_rename_file($_FILES['foto_bukti']['name']);
        if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0755, true);
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('foto_bukti')) return null;
        return $this->upload->data('file_name');
    }

    private function _upload_dokumentasi()
{
    $ids = [];
    
    // Cek jika ada file yang diupload
    if (!empty($_FILES['dokumentasi_files']['name'][0])) {
        $files = $_FILES['dokumentasi_files'];
        $file_count = count($files['name']);
        
        for ($i = 0; $i < $file_count; $i++) {
            if ($files['error'][$i] == 0) {
                $_FILES['single']['name']     = $files['name'][$i];
                $_FILES['single']['type']     = $files['type'][$i];
                $_FILES['single']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['single']['error']    = $files['error'][$i];
                $_FILES['single']['size']     = $files['size'][$i];

                $config['upload_path']   = './assets/uploads/dokumentasi_surat_jalan/';
                $config['allowed_types'] = 'jpg|png|jpeg|pdf|doc|docx';
                $config['max_size']      = 5120; // 5MB
                $config['file_name']     = $this->_rename_file($files['name'][$i]);
                
                // Buat folder jika belum ada
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, true);
                }

                $this->upload->initialize($config);
                
                if ($this->upload->do_upload('single')) {
                    $file_data = $this->upload->data();
                    $this->admin->insert('dokumentasi_surat_jalan', [
                        'nama_file' => $file_data['file_name'],
                        'status' => 1
                    ]);
                    $ids[] = $this->db->insert_id();
                } else {
                    // Log error upload jika perlu
                    // echo $this->upload->display_errors();
                }
            }
        }
    }
    return $ids;
}
    private function _rename_file($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $clean_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        return 'suratjalan_' . date('Ymd_His') . '_' . $clean_name . '.' . $ext;
    }

    public function detail($id)
{
    $data['title'] = "Detail Surat Jalan";
    
    // Query untuk data utama surat jalan
    $this->db->select('sj.*, dokumentasi_surat_jalan.nama_file AS dok_file');
    $this->db->from('surat_jalan sj');
    $this->db->join('dokumentasi_surat_jalan', 'dokumentasi_surat_jalan.id_dokumentasi_surat_jalan = sj.id_dokumentasi_surat_jalan', 'left');
    $this->db->where('sj.id_surat_jalan', $id);
    $this->db->where('sj.status !=', 8);
    $data['surat_jalan'] = $this->db->get()->row_array();

    if (!$data['surat_jalan']) {
        set_pesan('Surat Jalan tidak ditemukan', false);
        redirect('suratjalan');
    }

    // Ambil dokumentasi
    $this->db->where('id_dokumentasi_surat_jalan', $data['surat_jalan']['id_dokumentasi_surat_jalan']);
    $data['dokumentasi'] = $this->db->get('dokumentasi_surat_jalan')->result_array();

    // Detail semua barang_masuk dengan supplier
    $this->db->select('ds.*, bm.*, b.nama_barang, s.nama_supplier, satuan.nama_satuan, jenis.nama_jenis');
    $this->db->from('detail_surat_jalan ds');
    $this->db->join('barang_masuk bm', 'bm.id_barang_masuk = ds.id_barang_masuk', 'left');
    $this->db->join('barang b', 'b.id_barang = bm.barang_id', 'left');
    $this->db->join('supplier s', 's.id_supplier = bm.supplier_id', 'left');
    $this->db->join('satuan', 'satuan.id_satuan = b.satuan_id', 'left');
    $this->db->join('jenis', 'jenis.id_jenis = b.jenis_id', 'left');
    $this->db->where('ds.id_surat_jalan', $id);
    $this->db->where('ds.status !=', 8);
    $data['detail'] = $this->db->get()->result_array();

    $this->template->load('templates/dashboard', 'surat_jalan/detail', $data);
}
    public function delete($id)
    {
        $sj = $this->admin->get('surat_jalan', ['id_surat_jalan' => $id]);
        if ($sj) {
            // Ambil semua barang_masuk pada detail_surat_jalan
            $this->db->select('id_barang_masuk');
            $this->db->from('detail_surat_jalan');
            $this->db->where('id_surat_jalan', $id);
            $details = $this->db->get()->result_array();

            // Kembalikan status barang_masuk jadi 1
            foreach ($details as $detail) {
                $this->admin->update('barang_masuk', 'id_barang_masuk', $detail['id_barang_masuk'], ['status' => 1]);
            }
            // Soft delete Surat Jalan dan detail_surat_jalan
            $this->admin->update('surat_jalan', 'id_surat_jalan', $id, ['status' => 8]);
            $this->admin->update('detail_surat_jalan', 'id_surat_jalan', $id, ['status' => 8]);

            set_pesan('Surat Jalan berhasil dihapus');
        } else {
            set_pesan('Surat Jalan tidak ditemukan', false);
        }
        redirect('suratjalan');
    }

}
