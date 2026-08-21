<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function get($table, $data = null, $where = null)
    {
        if ($data != null) {
            return $this->db->get_where($table, $data)->row_array();
        } else {
            return $this->db->get_where($table, $where)->result_array();
        }
    }

    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }

    public function insert($table, $data, $batch = false)
    {
        return $batch ? $this->db->insert_batch($table, $data) : $this->db->insert($table, $data);
    }

    public function delete($table, $pk, $id)
    {
        return $this->db->delete($table, [$pk => $id]);
    }

    public function getUsers($id)
    {
        /**
         * ID disini adalah untuk data yang tidak ingin ditampilkan. 
         * Maksud saya disini adalah 
         * tidak ingin menampilkan data user yang digunakan, 
         * pada managemen data user
         */
        $this->db->where('id_user !=', $id);
        return $this->db->get('user')->result_array();
    }


    public function getBarangMasuk($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('user u', 'bm.user_id = u.id_user');
        $this->db->join('supplier sp', 'bm.supplier_id = sp.id_supplier');
        $this->db->join('barang b', 'bm.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
         $this->db->where('bm.status !=', 8);
        if ($limit != null) {
            $this->db->limit($limit);
        }

        if ($id_barang != null) {
            $this->db->where('id_barang', $id_barang);
        }

        if ($range != null) {
            $this->db->where('tanggal_masuk' . ' >=', $range['mulai']);
            $this->db->where('tanggal_masuk' . ' <=', $range['akhir']);
        }

        $this->db->order_by('id_barang_masuk', 'DESC');
        return $this->db->get('barang_masuk bm')->result_array();
    }

    public function getBarangKeluar($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('user u', 'bk.user_id = u.id_user');
        $this->db->join('barang b', 'bk.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if ($id_barang != null) {
            $this->db->where('id_barang', $id_barang);
        }
        if ($range != null) {
            $this->db->where('tanggal_keluar' . ' >=', $range['mulai']);
            $this->db->where('tanggal_keluar' . ' <=', $range['akhir']);
        }
        $this->db->order_by('id_barang_keluar', 'DESC');
        return $this->db->get('barang_keluar bk')->result_array();
    }

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

    public function getMax($table, $field, $kode = null)
    {
        $this->db->select_max($field);
        if ($kode != null) {
            $this->db->like($field, $kode, 'after');
        }
        return $this->db->get($table)->row_array()[$field];
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function sum($table, $field)
    {
        $this->db->select_sum($field);
        return $this->db->get($table)->row_array()[$field];
    }

    public function min($table, $field, $min)
    {
        $field = $field . ' <=';
        $this->db->where($field, $min);
        return $this->db->get($table)->result_array();
    }

    public function chartBarangMasuk($bulan)
    {
        $like = 'T-BM-' . date('y') . $bulan;
        $this->db->like('id_barang_masuk', $like, 'after');
        return count($this->db->get('barang_masuk')->result_array());
    }

    public function chartBarangKeluar($bulan)
    {
        $like = 'T-BK-' . date('y') . $bulan;
        $this->db->like('id_barang_keluar', $like, 'after');
        return count($this->db->get('barang_keluar')->result_array());
    }

    public function laporan($table, $mulai, $akhir)
    {
        $tgl = $table == 'barang_masuk' ? 'tanggal_masuk' : 'tanggal_keluar';
        $this->db->where($tgl . ' >=', $mulai);
        $this->db->where($tgl . ' <=', $akhir);
        return $this->db->get($table)->result_array();
    }

    public function cekStok($id)
    {
        $this->db->join('satuan s', 'b.satuan_id=s.id_satuan');
        return $this->db->get_where('barang b', ['id_barang' => $id])->row_array();
    }
   // Get BAST by ID
    public function getBastById($id)
    {
        $this->db->select('bast.*, u.nama as nama_user');
        $this->db->from('bast');
        $this->db->join('user u', 'u.id_user = bast.id_user', 'left');
        $this->db->where('bast.id_bast', $id);
        return $this->db->get()->row_array();
    }

    // Get BAST detail (hanya id_barang_keluar)
    public function getBastDetail($id_bast)
    {
        $this->db->select('id_barang_keluar');
        $this->db->from('bast_detail');
        $this->db->where('id_bast', $id_bast);
        return $this->db->get()->result_array();
    }

    // Get BAST detail dengan informasi barang
    public function getBastDetailWithItems($id_bast)
    {
        $this->db->select('bd.*, bk.tanggal_keluar, bk.jumlah_keluar, bk.unit, bk.nama_pic, 
                        b.nama_barang, s.nama_satuan, j.nama_jenis');
        $this->db->from('bast_detail bd');
        $this->db->join('barang_keluar bk', 'bk.id_barang_keluar = bd.id_barang_keluar');
        $this->db->join('barang b', 'b.id_barang = bk.barang_id');
        $this->db->join('satuan s', 's.id_satuan = b.satuan_id');
        $this->db->join('jenis j', 'j.id_jenis = b.jenis_id');
        $this->db->where('bd.id_bast', $id_bast);
        return $this->db->get()->result_array();
    }
    // Di dalam Admin_model
   // Ganti dengan yang ini (method baru dengan parameter filter)
    public function getBarang($jenis_filter = null)
    {
        $this->db->select('b.*, j.nama_jenis, s.nama_satuan');
        $this->db->from('barang b');
        $this->db->join('jenis j', 'b.jenis_id = j.id_jenis');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        
        // Tambahkan filter jika ada
        if (!empty($jenis_filter)) {
            $this->db->where('j.nama_jenis', $jenis_filter);
        }
        
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result_array();
    }
}
