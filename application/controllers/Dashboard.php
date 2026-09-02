<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
    }

    public function index()
    {
        $data['title'] = "Dashboard";
        $data['barang'] = $this->admin->count('barang');
        $data['barang_masuk'] = $this->admin->count('barang_masuk');
        $data['barang_keluar'] = $this->admin->count('barang_keluar');
        $data['supplier'] = $this->admin->count('supplier');
        $data['user'] = $this->admin->count('user');
        $data['stok'] = $this->admin->sum('barang', 'stok');
        $data['barang_min'] = $this->admin->getBarangMin();
        $data['transaksi'] = [
            'barang_masuk' => $this->admin->getBarangMasuk(5),
            'barang_keluar' => $this->admin->getBarangKeluar(5)
        ];

        // TAMBAHKAN: Ambil 10 material request terbaru
        $data['material_request'] = $this->getMaterialRequest(10);

        // Line Chart
        $bln = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $data['cbm'] = [];
        $data['cbk'] = [];

        foreach ($bln as $b) {
            $data['cbm'][] = $this->admin->chartBarangMasuk($b);
            $data['cbk'][] = $this->admin->chartBarangKeluar($b);
        }

        $this->template->load('templates/dashboard', 'dashboard', $data);
    }

    // BUAT FUNCTION TERPISAH UNTUK AMBIL MATERIAL REQUEST
    private function getMaterialRequest($limit = 10)
    {
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
        
        $query .= " ORDER BY mr.id_mr DESC LIMIT " . $limit;
        
        $material_request = $this->db->query($query)->result_array();

        // Status mapping
        foreach ($material_request as &$row) {
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

        return $material_request;
    }

    // Method material_request untuk halaman khusus (jika masih diperlukan)
    public function material_request()
    {
        $data['title'] = "Material Request";
        $data['material_request'] = $this->getMaterialRequest(); // tanpa limit atau limit lebih besar
        $data['user_id'] = $this->session->userdata('login_session')['user'];

        $this->template->load('templates/dashboard', 'material_request/index', $data);
    }
}