<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* file name     : M_tunggakan
* file type     : models
* file packages : CodeIgniter 3
* author        : rizky ardiansyah
* date-create   : 14 Dec 2020
*/

class M_tunggakan extends CI_Model
{
    // ambil data tunggakan
    public function getTunggakanMhs($data)
    {
        $this->db->select('id_tunggakan, nim, jml_tunggakan, idtahun');
        $this->db->from('tunggakan');
        $this->db->where($data);
        return $this->db->get();
    }

    // Add new tunggakan
    public function addNewTunggakan($data)
    {
        return $this->db->insert('tunggakan', $data);
    }
    // Update tunggakan
    public function updateTunggakan($id_tunggakan, $data)
    {
        $this->db->where('id_tunggakan', $id_tunggakan);
        $this->db->update('tunggakan', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Delete tunggakan
    public function deleteTunggakan($data)
    {
        $this->db->where($data);
        $this->db->delete('tunggakan');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
