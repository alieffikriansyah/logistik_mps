<?php

function cek_login()
{
    $ci = get_instance();
    if (!$ci->session->has_userdata('login_session')) {
        set_pesan('silahkan login.');
        redirect('auth');
    }
}

function is_admin()
{
    $ci = get_instance();
    $role = $ci->session->userdata('login_session')['role'];

    $status = true;

    if ($role != 'admin') {
        $status = false;
    }

    return $status;
}

function set_pesan($pesan, $tipe = true)
{
    $ci = get_instance();
    if ($tipe) {
        $ci->session->set_flashdata('pesan', "<div class='alert alert-success'><strong>SUCCESS!</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
    } else {
        $ci->session->set_flashdata('pesan', "<div class='alert alert-danger'><strong>ERROR!</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
    }
}

function userdata($field)
{
    $ci = get_instance();
    $ci->load->model('Admin_model', 'admin');

    $userId = $ci->session->userdata('login_session')['user'];
    return $ci->admin->get('user', ['id_user' => $userId])[$field];
}

function output_json($data)
{
    $ci = get_instance();
    $data = json_encode($data);
    $ci->output->set_content_type('application/json')->set_output($data);
}

function formatBastId($id_bast, $nama_jenis, $unit, $tanggal)
{
    $bulan_romawi = numberToRoman(date('n', strtotime($tanggal)));
    $tahun = date('Y', strtotime($tanggal));
    
    // Ambil huruf depan dari setiap kata untuk nama_jenis saja
    $singkatan_jenis = '';
    if ($nama_jenis) {
        $kata = explode(' ', $nama_jenis);
        foreach ($kata as $k) {
            $singkatan_jenis .= strtoupper(substr($k, 0, 1));
        }
    } else {
        $singkatan_jenis = 'UM'; // Default untuk UMUM
    }
    
    // Mapping singkatan untuk unit
    $singkatan_unit = getSingkatanUnit($unit);
    
    return "BAST" . str_pad($id_bast, 4, '0', STR_PAD_LEFT) . "/" . 
           $singkatan_jenis . "/" . 
           $singkatan_unit . "/" . 
           $bulan_romawi . "/" . 
           $tahun;
}

function getSingkatanUnit($unit)
{
    $singkatan_map = [
        'HIGH & MEDIUM VOLTAGE' => 'HVMV',
        'POWER STATION 1' => 'PS1',
        'POWER STATION 2' => 'PS2', 
        'POWER STATION 3' => 'PS3',
        'ELECTRICAL PROTECTION' => 'EP',
        'ELECTRICAL NETWORK' => 'EN',
        'NORTH VISUAL AID' => 'NVA',
        'SOUTH VISUAL AID' => 'SVA',
        'ELECTRICAL UTILITY' => 'EU',
        'UPS & CONVERTER' => 'UC',
        'TERMINAL 1' => 'T1',
        'TERMINAL 2' => 'T2',
        'TERMINAL 3' => 'T3',
        'NON TERMINAL ELECTRICAL SERVICE' => 'NTES'
    ];
    
    $unit_upper = strtoupper(trim($unit));
    
    return $singkatan_map[$unit_upper] ?? strtoupper($unit ?: 'UNIT');
}

// Function untuk konversi angka ke romawi (jika belum ada)
function numberToRoman($number)
{
    $map = [
        'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
        'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
        'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
    ];
    
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if ($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

?>