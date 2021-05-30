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
    public function getUser($param)
    {
        return $this->db->get_where('users', $param);
        // return $this->db->get();
    }
}
