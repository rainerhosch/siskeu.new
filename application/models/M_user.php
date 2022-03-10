<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* file name     : M_user
* file type     : models
* file packages : CodeIgniter 3
* author        : rizky ardiansyah
* date-create   : 14 Dec 2020
*/

class M_user extends CI_Model
{
    // Add user
    public function addUser($data)
    {
        return $this->db->insert('users', $data);
    }

    public function roleUser($where = null)
    {
        $this->db->distinct();
        $this->db->select('id_role, role_type');
        $this->db->from('user_role');
        if($where != null){
            $this->db->where('role <> 0');
        }
        return $this->db->get();
    }

    // Update user
    public function updateUser($id, $data)
    {
        $this->db->where('id_user', $id);
        $this->db->update('users', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // get one user
    public function getUser($param)
    {
        return $this->db->get_where('users', $param);
    }

    // get data all user
    public function getAllUser()
    {
        // code here...
        $this->db->select('id_user, nama_user, role');
        $this->db->from('users');
        $this->db->order_by('id_user', 'asc');
        return $this->db->get();
    }

    // Delete User
    public function deleteUser($data)
    {
        $this->db->where($data);
        $this->db->delete('users');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
