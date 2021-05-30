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
    // methode get menu
    public function getMenu()
    {
        // code here...
        $this->db->select('*');
        $this->db->from('menu');
        return $this->db->get();
    }

    // methode get sub menu
    public function getSubMenu($where)
    {
        // code here...
        $this->db->select('*');
        $this->db->from('submenu sm');
        $this->db->where($where);
        return $this->db->get();
    }

    public function getUserMenu($where)
    {
        $this->db->distinct();
        $this->db->select('m.id_menu, m.nama_menu, m.link_menu, m.type, m.icon, m.is_active');
        $this->db->from('menu m');
        $this->db->join('user_access_menu uam', 'm.id_menu=uam.menu_id');
        // $this->db->join('user_role ur', 'ur.id_role=uam.role_id');
        // $this->db->join('users u', 'u.role=ur.id_role');
        $this->db->where($where);
        $this->db->order_by('m.id_menu', 'asc');
        return $this->db->get();
    }
}
