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
    public function getTunggakanMhs($data = null)
    {
        $this->db->select('tg.id_tunggakan, tg.nim, tg.jenis_tunggakan, tg.jml_tunggakan, tg.idtahun, mjp.nm_jenis_pembayaran');
        $this->db->from('tunggakan tg');
        $this->db->join('master_jenis_pembayaran mjp', 'mjp.id_jenis_pembayaran=tg.jenis_tunggakan');
        if ($data !== null) {
            $this->db->where($data);
        }
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
        $this->db->where($id_tunggakan);
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

    public function getAllDataTunggakanMhs($data = null)
    {
        $this->db->select('m.id_pd, m.nipd, m.nm_pd, m.id_jur, m.nm_jur, mjp.nm_jenis_pembayaran, tg.jml_tunggakan');
        $this->db->from('tunggakan tg');
        $this->db->join('mahasiswa m', 'm.nipd=tg.nim');
        $this->db->join('master_jenis_pembayaran mjp', 'mjp.id_jenis_pembayaran=tg.jenis_tunggakan');
        if ($data !== null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }
}
