<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authorization helper untuk Data Kontrak dan tombol global
 */

/**
 * Ambil role saat ini
 */
if (!function_exists('get_user_role')) {
    function get_user_role()
    {
        $CI = &get_instance();
        return $CI->session->userdata('user_role');
    }
}

/**
 * apakah guest
 */
if (!function_exists('is_guest')) {
    function is_guest()
    {
        $CI = &get_instance();
        $is_guest = $CI->session->userdata('is_guest');
        $role = strtolower($CI->session->userdata('user_role') ?? '');
        return $is_guest === true || $role === 'guest';
    }
}

/**
 * apakah admin
 */
if (!function_exists('is_admin')) {
    function is_admin()
    {
        $role = strtolower(get_user_role() ?? '');
        return in_array($role, ['admin', 'administrator'], true);
    }
}

/**
 * ROLE MAPPING KHUSUS DATA KONTRAK
 */
if (!function_exists('_role_allows_create_kontrak')) {
    function _role_allows_create_kontrak($role)
    {
        $role = strtolower($role ?? '');
        return in_array($role, [
            'admin',
            'administrator',
            'pemeliharaan',
            'fasilitas operasi',
            'har',
            'k3l & kam',
            'perencanaan',
            'kku',
        ], true);
    }
}

if (!function_exists('_role_allows_edit_kontrak')) {
    function _role_allows_edit_kontrak($role)
    {
        $role = strtolower($role ?? '');
        return in_array($role, [
            'admin',
            'administrator',
            'pemeliharaan',
            'fasilitas operasi',
            'har',
            'perencanaan',
            'pengadaan keuangan',
            'kku',
            'k3l & kam',
        ], true);
    }
}

if (!function_exists('_role_allows_delete_kontrak')) {
    function _role_allows_delete_kontrak($role)
    {
        $role = strtolower($role ?? '');
        // Hanya admin/administrator yang boleh hapus
        return in_array($role, ['admin', 'administrator'], true);
    }
}

/**
 * GENERIC can_create / can_edit / can_delete (dipakai banyak view)
 * - tetap mempertahankan logic lama untuk module lain
 */

if (!function_exists('can_create')) {
    function can_create($module = null)
    {
        $CI = &get_instance();
        $role = strtolower($CI->session->userdata('user_role') ?? '');
        $is_guest = $CI->session->userdata('is_guest');

        if ($is_guest || $role === 'guest') {
            return false;
        }

        if ($module === null) {
            $module = strtolower($CI->router->fetch_class());
        } else {
            $module = strtolower($module);
        }

        // KHUSUS DATA KONTRAK
        if (in_array($module, ['data_kontrak', 'input_kontrak'], true)) {
            if (is_admin()) return true;
            return _role_allows_create_kontrak($role);
        }

        // fallback: admin semua
        if (is_admin()) return true;

        // contoh mapping lama (biarkan)
        if ($role === 'up3') {
            return $module === 'pengaduan';
        }

        if ($role === 'pemeliharaan') {
            $allowed = [
                'unit','gardu_induk','gi_cell','gardu_hubung','gh_cell','pembangkit','kit_cell','pemutus',
                'sop','bpm','ik','road_map','spln','pengaduan','input_kontrak','rekomposisi','operasi','investasi'
            ];
            return in_array($module, $allowed, true);
        }

        if ($role === 'fasilitas operasi') {
            $allowed = [
                'unit','gardu_induk','gi_cell','gardu_hubung','gh_cell','pembangkit','kit_cell','pemutus',
                'sop','bpm','ik','road_map','spln','pengaduan','input_kontrak','rekomposisi','operasi','investasi'
            ];
            return in_array($module, $allowed, true);
        }

        if ($role === 'perencanaan') {
            $allowed = [
                'unit','gardu_induk','gi_cell','gardu_hubung','gh_cell','pembangkit','kit_cell','pemutus',
                'sop','bpm','ik','road_map','spln','pengaduan','input_kontrak','rekomposisi','operasi','investasi'
            ];
            return in_array($module, $allowed, true);
        }

        if (in_array($role, ['har','pengadaan keuangan'], true)) {
            return $module === 'input_kontrak';
        }

        // default: allow
        return true;
    }
}

if (!function_exists('can_edit')) {
    function can_edit($module = null)
    {
        $CI = &get_instance();
        $role = strtolower($CI->session->userdata('user_role') ?? '');
        $is_guest = $CI->session->userdata('is_guest');

        if ($is_guest || $role === 'guest') {
            return false;
        }

        if ($module === null) {
            $module = strtolower($CI->router->fetch_class());
        } else {
            $module = strtolower($module);
        }

        if (in_array($module, ['data_kontrak', 'input_kontrak'], true)) {
            if (is_admin()) return true;
            return _role_allows_edit_kontrak($role);
        }

        if (is_admin()) return true;

        if ($role === 'up3') return $module === 'pengaduan';

        if ($role === 'pemeliharaan' || $role === 'fasilitas operasi' || $role === 'perencanaan') {
            $allowed = [
                'unit','gardu_induk','gi_cell','gardu_hubung','gh_cell','pembangkit','kit_cell','pemutus',
                'sop','bpm','ik','road_map','spln','pengaduan','input_kontrak','rekomposisi','operasi','investasi'
            ];
            return in_array($module, $allowed, true);
        }

        if (in_array($role, ['har', 'pengadaan keuangan'], true)) {
            return $module === 'input_kontrak';
        }

        return true;
    }
}

if (!function_exists('can_delete')) {
    function can_delete($module = null)
    {
        $CI = &get_instance();
        $role = strtolower($CI->session->userdata('user_role') ?? '');
        $is_guest = $CI->session->userdata('is_guest');

        if ($is_guest || $role === 'guest') {
            return false;
        }

        if ($module === null) {
            $module = strtolower($CI->router->fetch_class());
        } else {
            $module = strtolower($module);
        }

        if (in_array($module, ['data_kontrak', 'input_kontrak'], true)) {
            return _role_allows_delete_kontrak($role);
        }

        if (is_admin()) return true;

        if ($role === 'up3') return $module === 'pengaduan';

        // default deny for others (safer)
        return false;
    }
}

/* ============================================================
   TAMBAHAN KHUSUS MODULE REKOMPOSISI (TANPA MENGUBAH YANG LAMA)
   - Hanya role: Admin/Administrator & Perencanaan
   - Ini opsional, tapi memudahkan jika mau dipanggil eksplisit.
   ============================================================ */

if (!function_exists('can_create_rekomposisi')) {
    function can_create_rekomposisi()
    {
        if (is_guest()) return false;
        $role = strtolower(get_user_role() ?? '');
        return in_array($role, ['admin', 'administrator', 'perencanaan'], true);
    }
}

if (!function_exists('can_edit_rekomposisi')) {
    function can_edit_rekomposisi()
    {
        if (is_guest()) return false;
        $role = strtolower(get_user_role() ?? '');
        return in_array($role, ['admin', 'administrator', 'perencanaan'], true);
    }
}

if (!function_exists('can_delete_rekomposisi')) {
    function can_delete_rekomposisi()
    {
        if (is_guest()) return false;
        $role = strtolower(get_user_role() ?? '');
        return in_array($role, ['admin', 'administrator', 'perencanaan'], true);
    }
}

/**
 * Helper "require" untuk controller (biar rapi).
 * Kalau tidak diizinkan, redirect balik + flash error.
 */
if (!function_exists('require_rekomposisi_create')) {
    function require_rekomposisi_create()
    {
        $CI = &get_instance();
        if (!can_create_rekomposisi()) {
            $CI->session->set_flashdata('error', 'Akses ditolak. Hanya Admin & Perencanaan yang dapat menambah data.');
            redirect('rekomposisi');
            exit;
        }
    }
}

if (!function_exists('require_rekomposisi_edit')) {
    function require_rekomposisi_edit()
    {
        $CI = &get_instance();
        if (!can_edit_rekomposisi()) {
            $CI->session->set_flashdata('error', 'Akses ditolak. Hanya Admin & Perencanaan yang dapat mengubah data.');
            redirect('rekomposisi');
            exit;
        }
    }
}

if (!function_exists('require_rekomposisi_delete')) {
    function require_rekomposisi_delete()
    {
        $CI = &get_instance();
        if (!can_delete_rekomposisi()) {
            $CI->session->set_flashdata('error', 'Akses ditolak. Hanya Admin & Perencanaan yang dapat menghapus data.');
            redirect('rekomposisi');
            exit;
        }
    }
}
