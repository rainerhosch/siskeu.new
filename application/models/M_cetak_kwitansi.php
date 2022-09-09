<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : M_cetak_kwitansi.php
 *  File Type             : Model
 *  File Package          : CI_Models
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 22/06/2021
 *  Quots of the code     : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */
class M_cetak_kwitansi extends CI_Model
{
    public function getDataTransaksi($where = null)
    {
        // code here...
        $this->db->select('tx.*, m.nm_pd');
        $this->db->from('transaksi tx');
        $this->db->join('mahasiswa m', 'tx.nim=m.nipd');
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get();
    }
}
