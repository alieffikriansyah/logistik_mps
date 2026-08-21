<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bast extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    // public function index()
    // {
    //     $data['title'] = "BAST";
        
    //     // Get semua BAST dengan info user dan hitung total items
    //     $this->db->select('bast.*, u.nama as nama_user, COUNT(db.id_detail_bast) as total_items');
    //     $this->db->from('bast');
    //     $this->db->join('user u', 'u.id_user = bast.id_user', 'left');
    //     $this->db->join('detail_bast db', 'db.id_bast = bast.id_bast AND db.status = 1', 'left');
    //     $this->db->where('bast.status !=', 8);
    //     $this->db->group_by('bast.id_bast');
    //     $this->db->order_by('bast.id_bast', 'DESC');
    //     $data['bast'] = $this->db->get()->result_array();
        
    //     $this->template->load('templates/dashboard', 'bast/data', $data);
    // }
public function index()
{
    $data['title'] = "BAST";
    
    // Get semua BAST dengan info user, jenis barang, unit, dan hitung total items
    $this->db->select('bast.*, u.nama as nama_user, 
                      j.nama_jenis, bk.unit,
                      COUNT(db.id_detail_bast) as total_items');
    $this->db->from('bast');
    $this->db->join('user u', 'u.id_user = bast.id_user', 'left');
    $this->db->join('detail_bast db', 'db.id_bast = bast.id_bast AND db.status = 1', 'left');
    $this->db->join('barang_keluar bk', 'bk.id_barang_keluar = db.id_barang_keluar', 'left');
    $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left'); // DIUBAH DI SINI
    $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
    $this->db->where('bast.status !=', 8);
    $this->db->group_by('bast.id_bast');
    $this->db->order_by('bast.id_bast', 'DESC');
    $data['bast'] = $this->db->get()->result_array();
    
    $this->template->load('templates/dashboard', 'bast/data', $data);
}
//  public function add()
// {
//     // Ubah rules untuk array
//     $this->form_validation->set_rules('id_barang_keluar[]', 'Barang Keluar', 'required');
//     $this->form_validation->set_rules('unit', 'Unit', 'required');
    
//     if ($this->form_validation->run() == false) {
//         $data['title'] = "Tambah BAST";
        
//         // Get barang keluar yang belum ada di BAST (kode != 9)
//         $this->db->select('bk.*, b.nama_barang, b.id_barang, j.nama_jenis, s.nama_satuan');
//         $this->db->from('barang_keluar bk');
//         $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left');
//         $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
//         $this->db->join('satuan s', 's.id_satuan = b.satuan_id', 'left');
//         $this->db->where('bk.kode !=', 9);
//         $this->db->order_by('bk.tanggal_keluar', 'DESC');
//         $data['barang_keluar'] = $this->db->get()->result_array();
        
//         $this->template->load('templates/dashboard', 'bast/add', $data);
//     } else {
//         $input = $this->input->post(null, true);
        
//         // Debug: Lihat data yang dikirim
//         // echo "<pre>";
//         // echo "Data POST:\n";
//         // print_r($input);
//         // echo "</pre>";
//         // die();
        
//         // Handle upload dokumentasi - HARUS DIPANGGIL SEBELUM digunakan
//         $dokumentasi = $this->_upload_foto();
        
//         // Simpan BAST
//         $bast_data = [
//             'tanggal' => date('Y-m-d'),
//             'id_user' => $this->session->userdata('login_session')['user'],
//             'unit' => $input['unit'],
//             'dokumentasi' => $dokumentasi, // Gunakan variabel $dokumentasi yang sudah diupload
//             'status' => 1
//         ];

//         $insert_bast = $this->admin->insert('bast', $bast_data);
//         $id_bast = $this->db->insert_id();
        
//         if ($insert_bast) {
//             // Simpan ke detail_bast untuk SETIAP barang keluar yang dipilih
//             foreach ($input['id_barang_keluar'] as $id_barang_keluar) {
//                 $detail_data = [
//                     'id_bast' => $id_bast,
//                     'id_barang_keluar' => $id_barang_keluar,
//                     'status' => 1,
//                     'tanggal' => date('Y-m-d H:i:s')
//                 ];
                
//                 $this->admin->insert('detail_bast', $detail_data);
                
//                 // Update status barang keluar menjadi sudah di BAST (kode = 9)
//                 $this->admin->update('barang_keluar', 'id_barang_keluar', $id_barang_keluar, ['kode' => 9]);
//             }
            
//             set_pesan('BAST berhasil dibuat dengan ' . count($input['id_barang_keluar']) . ' item');
//             redirect('bast');
//         } else {
//             set_pesan('Gagal membuat BAST');
//             redirect('bast/add');
//         }
//     }
// }
public function add()
{
    // Ubah rules untuk array
    $this->form_validation->set_rules('id_barang_keluar[]', 'Barang Keluar', 'required');
    $this->form_validation->set_rules('unit', 'Unit', 'required');
    
    if ($this->form_validation->run() == false) {
        $data['title'] = "Tambah BAST";
        
        // Get barang keluar yang belum ada di BAST (kode != 9)
        $this->db->select('bk.*, b.nama_barang, b.id_barang, j.nama_jenis, j.id_jenis, s.nama_satuan');
        $this->db->from('barang_keluar bk');
        $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left');
        $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
        $this->db->join('satuan s', 's.id_satuan = b.satuan_id', 'left');
        $this->db->where('bk.kode !=', 9);
        $this->db->order_by('bk.tanggal_keluar', 'DESC');
        $data['barang_keluar'] = $this->db->get()->result_array();
        
        $this->template->load('templates/dashboard', 'bast/add', $data);
    } else {
        $input = $this->input->post(null, true);
        
        // VALIDASI: Cek apakah semua barang memiliki jenis yang sama
        $jenis_ids = [];
        $barang_jenis = [];
        
        foreach ($input['id_barang_keluar'] as $id_barang_keluar) {
            // Get jenis_id dari barang keluar yang dipilih
            $this->db->select('b.jenis_id, j.nama_jenis');
            $this->db->from('barang_keluar bk');
            $this->db->join('barang b', 'b.id_barang = bk.barang_id');
            $this->db->join('jenis j', 'j.id_jenis = b.jenis_id');
            $this->db->where('bk.id_barang_keluar', $id_barang_keluar);
            $barang = $this->db->get()->row_array();
            
            if ($barang) {
                $jenis_ids[] = $barang['jenis_id'];
                $barang_jenis[$id_barang_keluar] = $barang['nama_jenis'];
            }
        }
        
        // Cek apakah semua jenis_id sama
        $unique_jenis = array_unique($jenis_ids);
        if (count($unique_jenis) > 1) {
            // Ada lebih dari 1 jenis yang berbeda
            $error_message = "Tidak bisa menambahkan BAST. Semua barang harus memiliki jenis yang sama. ";
            $error_message .= "Jenis yang dipilih: " . implode(", ", array_unique($barang_jenis));
            
            set_pesan($error_message, false);
            
            // Redirect kembali ke form dengan data yang dipilih
            $data['title'] = "Tambah BAST";
            
            // Get barang keluar yang belum ada di BAST (kode != 9)
            $this->db->select('bk.*, b.nama_barang, b.id_barang, j.nama_jenis, j.id_jenis, s.nama_satuan');
            $this->db->from('barang_keluar bk');
            $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left');
            $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
            $this->db->join('satuan s', 's.id_satuan = b.satuan_id', 'left');
            $this->db->where('bk.kode !=', 9);
            $this->db->order_by('bk.tanggal_keluar', 'DESC');
            $data['barang_keluar'] = $this->db->get()->result_array();
            
            // Simpan input yang sudah dipilih untuk ditampilkan kembali
            $data['selected_items'] = $input['id_barang_keluar'];
            
            $this->template->load('templates/dashboard', 'bast/add', $data);
            return;
        }
        
        // Handle upload dokumentasi
        $dokumentasi = $this->_upload_foto();
        
        // Simpan BAST
        $bast_data = [
            'tanggal' => date('Y-m-d'), // Pastikan fieldnya 'tanggal_bast'
            'id_user' => $this->session->userdata('login_session')['user'],
            'unit' => $input['unit'],
            'dokumentasi' => $dokumentasi,
            'status' => 1
        ];

        $insert_bast = $this->admin->insert('bast', $bast_data);
        $id_bast = $this->db->insert_id();
        
        if ($insert_bast) {
            // Simpan ke detail_bast untuk SETIAP barang keluar yang dipilih
            foreach ($input['id_barang_keluar'] as $id_barang_keluar) {
                $detail_data = [
                    'id_bast' => $id_bast,
                    'id_barang_keluar' => $id_barang_keluar,
                    'status' => 1,
                    'tanggal' => date('Y-m-d H:i:s')
                ];
                
                $this->admin->insert('detail_bast', $detail_data);
                
                // Update status barang keluar menjadi sudah di BAST (kode = 9)
                $this->admin->update('barang_keluar', 'id_barang_keluar', $id_barang_keluar, ['kode' => 9]);
            }
            
            set_pesan('BAST berhasil dibuat dengan ' . count($input['id_barang_keluar']) . ' item. Jenis: ' . $barang_jenis[$input['id_barang_keluar'][0]]);
            redirect('bast');
        } else {
            set_pesan('Gagal membuat BAST');
            redirect('bast/add');
        }
    }
}
private function _upload_foto()
{
    // Cek apakah ada file yang diupload
    if (empty($_FILES['dokumentasi']['name'])) {
        return null;
    }
    
    $config['upload_path'] = './assets/uploads/bastkeunit/';
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['max_size'] = 2048; // 2MB
    $config['file_name'] = $this->_rename_file($_FILES['dokumentasi']['name']);
    $config['overwrite'] = false;

    // Buat folder jika belum ada
    if (!is_dir($config['upload_path'])) {
        mkdir($config['upload_path'], 0755, true);
    }

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('dokumentasi')) {
        // Jika upload gagal, return null
        $error = $this->upload->display_errors();
        log_message('error', 'Upload dokumentasi BAST gagal: ' . $error);
        return null;
    } else {
        // Jika upload berhasil, return nama file
        return $this->upload->data('file_name');
    }
}

private function _rename_file($filename)
{
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);
    
    // Bersihkan nama file dari karakter khusus
    $clean_name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
    
    // Tambahkan timestamp untuk membuat unique
    $new_filename = 'bast_' . date('Ymd_His') . '_' . $clean_name . '.' . $ext;
    
    return $new_filename;
}

    // public function detail($id)
    // {
    //     $data['title'] = "Detail BAST";
        
    //     // Get data BAST
    //     $this->db->select('bast.*, u.nama as nama_user, 
    //                   j.nama_jenis, bk.unit,
    //                   COUNT(db.id_detail_bast) as total_items');
    //     $this->db->from('bast');
    //     $this->db->join('user u', 'u.id_user = bast.id_user', 'left');
    //     $this->db->join('detail_bast db', 'db.id_bast = bast.id_bast AND db.status = 1', 'left');
    //     $this->db->join('barang_keluar bk', 'bk.id_barang_keluar = db.id_barang_keluar', 'left');
    //     $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left'); // DIUBAH DI SINI
    //     $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
    //     $this->db->where('bast.status !=', 8);
    //     $this->db->group_by('bast.id_bast');
    //     $data['bast'] = $this->db->get()->row_array();
        
    //     if (!$data['bast']) {
    //         set_pesan('BAST tidak ditemukan', false);
    //         redirect('bast');
    //     }
        
    //     // Get detail barang keluar dari BAST ini
    //     $this->db->select('db.*, bk.*, b.nama_barang, b.id_barang, j.nama_jenis, s.nama_satuan');
    //     $this->db->from('detail_bast db');
    //     $this->db->join('barang_keluar bk', 'bk.id_barang_keluar = db.id_barang_keluar');
    //     $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left');
    //     $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
    //     $this->db->join('satuan s', 's.id_satuan = b.satuan_id', 'left');
    //     $this->db->where('db.id_bast', $id);
    //     $this->db->where('db.status', 1);
    //     $data['bast_details'] = $this->db->get()->result_array();
        
    //     $this->template->load('templates/dashboard', 'bast/detail', $data);
    // }
public function detail($id)
{
    $data['title'] = "Detail BAST";
    
    // Get data BAST dengan join yang benar
    $this->db->select('bast.*, u.nama as nama_user, 
                  j.nama_jenis, bk.unit,
                  COUNT(db.id_detail_bast) as total_items');
    $this->db->from('bast');
    $this->db->join('user u', 'u.id_user = bast.id_user', 'left');
    $this->db->join('detail_bast db', 'db.id_bast = bast.id_bast AND db.status = 1', 'left');
    $this->db->join('barang_keluar bk', 'bk.id_barang_keluar = db.id_barang_keluar', 'left');
    $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left');
    $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
    $this->db->where('bast.id_bast', $id); // DIUBAH: where by ID bast
    $this->db->where('bast.status !=', 8);
    $this->db->group_by('bast.id_bast');
    $data['bast'] = $this->db->get()->row_array();
    
    if (!$data['bast']) {
        set_pesan('BAST tidak ditemukan', false);
        redirect('bast');
    }
    
    // Get detail barang keluar dari BAST ini
    $this->db->select('db.*, bk.*, b.nama_barang, b.id_barang, j.nama_jenis, s.nama_satuan');
    $this->db->from('detail_bast db');
    $this->db->join('barang_keluar bk', 'bk.id_barang_keluar = db.id_barang_keluar');
    $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left');
    $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
    $this->db->join('satuan s', 's.id_satuan = b.satuan_id', 'left');
    $this->db->where('db.id_bast', $id);
    $this->db->where('db.status', 1);
    $data['bast_details'] = $this->db->get()->result_array();
    
    $this->template->load('templates/dashboard', 'bast/detail', $data);
}
    public function delete($id)
    {
        // Get BAST yang akan dihapus
        $bast = $this->admin->get('bast', ['id_bast' => $id]);
        
        if ($bast) {
            // Get detail bast untuk restore kode barang_keluar
            $this->db->select('id_barang_keluar');
            $this->db->from('detail_bast');
            $this->db->where('id_bast', $id);
            $this->db->where('status', 1);
            $details = $this->db->get()->result_array();
            
            // Kembalikan status barang keluar ke semula (kode = 1)
            foreach ($details as $detail) {
                $this->admin->update('barang_keluar', 'id_barang_keluar', $detail['id_barang_keluar'], ['kode' => 1]);
            }
            
            // Soft delete BAST dan detail_bast
            $this->admin->update('bast', 'id_bast', $id, ['status' => 8]);
            $this->admin->update('detail_bast', 'id_bast', $id, ['status' => 8]);
            
            set_pesan('BAST berhasil dihapus');
        } else {
            set_pesan('BAST tidak ditemukan', false);
        }
        
        redirect('bast');
    }

public function print_bast($id)
{
    $data['title'] = "BERITA ACARA SERAH TERIMA";

    $this->db->select('bast.*, u.nama as nama_user, 
                      j.nama_jenis, bk.unit,
                      COUNT(db.id_detail_bast) as total_items');
    $this->db->from('bast');
    $this->db->join('user u', 'u.id_user = bast.id_user', 'left');
    $this->db->join('detail_bast db', 'db.id_bast = bast.id_bast AND db.status = 1', 'left');
    $this->db->join('barang_keluar bk', 'bk.id_barang_keluar = db.id_barang_keluar', 'left');
    $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left'); // DIUBAH DI SINI
    $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
    $this->db->where('bast.status !=', 8);
    $this->db->group_by('bast.id_bast');
    $this->db->order_by('bast.id_bast', 'DESC');
    $data['bast'] = $this->db->get()->row_array();
    
    if (!$data['bast']) {
        set_pesan('BAST tidak ditemukan', false);
        redirect('bast');
    }
    
    // Get detail barang keluar untuk print - TAMBAHKAN FOTO_BARANG
    $this->db->select('db.*, bk.*, b.nama_barang, b.id_barang, b.foto_barang, j.nama_jenis, s.nama_satuan');
    $this->db->from('detail_bast db');
    $this->db->join('barang_keluar bk', 'bk.id_barang_keluar = db.id_barang_keluar');
    $this->db->join('barang b', 'b.id_barang = bk.barang_id', 'left');
    $this->db->join('jenis j', 'j.id_jenis = b.jenis_id', 'left');
    $this->db->join('satuan s', 's.id_satuan = b.satuan_id', 'left');
    $this->db->where('db.id_bast', $id);
    $this->db->where('db.status', 1);
    $data['bast_details'] = $this->db->get()->result_array();
    

    // TAMBAHKAN: Function untuk menentukan nama koordinator berdasarkan unit
    $data['coordinator_name'] = $this->getCoordinatorName($data['bast']['unit'] ?? '');
    $data['show_signature'] = !empty($data['coordinator_name']);
    $this->load->view('bast/print_bast', $data);
}
// TAMBAHKAN FUNCTION INI DI CONTROLLER
private function getCoordinatorName($unit)
{
    $unit = trim($unit);
    
    // Group 1: Reynaldi Airlangga
    $group1 = [
        'High & Medium Voltage',
        'Power Station 1', 
        'Power Station 2',
        'Power Station 3',
        'Electrical Protection',
        'Electrical Network'
    ];
    
    // Group 2: Rico Pratama
    $group2 = [
        'North Visual Aid',
        'South Visual Aid',
        'Electrical Utility',
        'UPS & Converter'
    ];
    
    // Group 3: Syaiful Fahmi
    $group3 = [
        'Terminal 1',
        'Terminal 2',
        'Non Terminal Electrical Service'
    ];
    
    if (in_array($unit, $group1)) {
        return "Reynaldi Airlangga";
    } elseif (in_array($unit, $group2)) {
        return "Rico Pratama";
    } elseif (in_array($unit, $group3)) {
        return "Syaiful Fahmi";
    } elseif ($unit == 'Terminal 3') {
        return "Riswadi";
    } else {
        return ""; // Kosong untuk unit lainnya
    }
}
// Pakai Dompdf manual (tanpa Composer)
public function print_bast_pdf($id)
{
    require_once APPPATH . 'third_party/dompdf/autoload.inc.php';
    
    $data = $this->get_bast_data($id);
    
    $options = new Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('defaultFont', 'Arial');
    
    $dompdf = new Dompdf\Dompdf($options);
    
    $html = $this->load->view('bast/print_bast_pdf', $data, TRUE);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    $dompdf->stream("BAST_" . $data['bast']['id_bast'] . ".pdf", ["Attachment" => true]);
}
}