<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * file name     : M_menu
 * file type     : models
 * file packages : CodeIgniter 3
 * author        : rizky ardiansyah
 * date-create   : 14 Dec 2020
 */

class M_menu extends CI_Model
{
    // Add new
    public function addData($table, $data)
    {
        return $this->db->insert($table, $data);
    }
    public function getUserAccessMenu()
    {
    }
    // methode get menu
    public function getMenu($where = null)
    {
        // code here...
        $this->db->select('*');
        $this->db->from('menu');
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get();
    }

    public function typeMenu()
    {
        $this->db->distinct();
        $this->db->select('type');
        $this->db->from('menu');
        return $this->db->get();
    }
    public function getMenuById($where)
    {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->where($where);
        return $this->db->get();
    }

    // Add new menu
    public function addNewMenu($data)
    {
        return $this->db->insert('menu', $data);
    }

    // Update Menu
    public function updateMenu($id_menu, $data)
    {
        $this->db->where('id_menu', $id_menu);
        $this->db->update('menu', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    // Delete Menu
    public function deleteMenu($data)
    {
        $this->db->where($data);
        $this->db->delete('menu');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // ==========================Sub Menu============================
    public function getSubmenuById($where)
    {
        $this->db->select('id_submenu, m.id_menu, m.nama_menu, nama_submenu, url, sm.icon, sm.is_active');
        $this->db->from('submenu sm');
        $this->db->join('menu m', 'sm.id_menu=m.id_menu');
        $this->db->where($where);
        return $this->db->get();
    }

    public function getSubMenu($where)
    {
        // code here...
        $this->db->select('*');
        $this->db->from('submenu sm');
        $this->db->where($where);
        return $this->db->get();
    }

    // methode get sub menu
    public function getSubMenuAll()
    {
        // code here...
        $this->db->select('id_submenu, nama_submenu, url, sm.icon, sm.is_active, m.nama_menu');
        $this->db->from('submenu sm');
        $this->db->join('menu m', 'sm.id_menu=m.id_menu');
        $this->db->where('editable =', 'YES');
        return $this->db->get();
    }

    // Add Submenu
    public function addNewSubmenu($data)
    {
        return $this->db->insert('submenu', $data);
    }

    // Update Sub Menu
    public function updateSubMenu($id_submenu, $data)
    {
        $this->db->where('id_submenu', $id_submenu);
        $this->db->update('submenu', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Delete Submenu
    public function deleteSubmenu($data)
    {
        $this->db->where($data);
        $this->db->delete('submenu');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // =================================================

    public function getUserMenu($where = null)
    {
        $this->db->distinct();
        $this->db->select('m.id_menu, m.nama_menu, m.link_menu, m.type, m.icon, m.is_active, uam.*');
        $this->db->from('menu m');
        $this->db->join('user_access_menu uam', 'm.id_menu=uam.menu_id');
        // $this->db->join('user_role ur', 'ur.id_role=uam.role_id');
        // $this->db->join('users u', 'u.role=ur.id_role');
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->order_by('m.id_menu', 'asc');
        return $this->db->get();
    }


    public function get_all_menu($where = null)
    {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->where(['editable !=' => 'N/A']);
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get();
    }

    public function get_user_access_menu($where = null)
    {
        // 'SELECT mn.* FROM menu mn JOIN user_access_menu uam ON uam.id_menu=mn.id_menu JOIN user_role ur ON ur.role_id=uam.role_id WHERE ur.role_id=1 AND mn.is_active=1';
        $this->db->select('mn.*, uam.*');
        $this->db->from('menu mn');
        $this->db->join('user_access_menu uam', 'uam.menu_id=mn.id_menu');
        $this->db->join('user_role ur', 'ur.id_role=uam.role_id');
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get();
    }

    public function delete_data($tbl, $where)
    {
        $this->db->where($where);
        $this->db->delete($tbl);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}