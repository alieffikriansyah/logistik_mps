<?php
if (!function_exists('sess')) {
    function sess($key = null)
    {
        $CI =& get_instance();
        $session_data = $CI->session->userdata('login_session');
        
        if ($key) {
            return $session_data[$key] ?? null;
        }
        
        return $session_data;
    }
}
?>