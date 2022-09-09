<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : M_laporan.php
 *  File Type             : Model
 *  File Package          : CI_Models
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 21/08/2021
 *  Quots of the code     : 'rapihkan code mu, sebagaimana kau merapihkan kumis mu'
 */
class M_laporan extends CI_Model
{
    public function template()
    {
        $this->db->select();
        $this->db->from();
        $this->db->join();
        $this->db->where();
        return $this->db->get();
    }
    public function getDataTx($data = null, $limit = '', $start = '')
    {
        $this->db->select('t.id_transaksi, t.tanggal, t.id_transaksi, t.jam, t.semester, t.nim, t.user_id, t.status_transaksi, t.transaksi_ke, t.uang_masuk, m.nm_pd, m.nm_pd, m.nm_jur, m.nm_jenj_didik, ts.icon_status_tx, ka.nm_smt, ka.smt, u.nama_user, u.ttd');
        $this->db->from('transaksi t');
        $this->db->join('mahasiswa m', 'm.nipd=t.nim');
        $this->db->join('transaksi_status ts', 'ts.kode_status_tx=t.status_transaksi');
        $this->db->join('users u', 'u.id_user=t.user_id');
        $this->db->join('kalender_akademik ka', 'ka.id_smt=t.semester');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        $this->db->join('master_jenis_pembayaran mjp', 'td.id_jenis_pembayaran=mjp.id_jenis_pembayaran');

        if ($data != null) {
            $this->db->where($data);
        }

        // if limit and start provided
        if ($limit != "") {
            $this->db->limit($limit, $start);
        } else if ($start != "") {
            $this->db->limit($limit, $start);
        }

        $this->db->group_by('t.id_transaksi');
        $this->db->order_by('t.tanggal desc');
        // $this->db->order_by('t.nim desc');
        return $this->db->get();
    }

    // get data transaksi detail
    public function getDetailTx($data = null)
    {
        $this->db->select('
        t.id_transaksi,
        td.id_detail_transaksi,
        t.tanggal, t.jam,
        t.nim, t.semester,
        td.id_jenis_pembayaran,
        mjp.nm_jenis_pembayaran,
        td.jml_bayar,
        mjp.jenis_kas,
        td.potongan
        ');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        $this->db->join('master_jenis_pembayaran mjp', 'td.id_jenis_pembayaran=mjp.id_jenis_pembayaran');
        if ($data != null) {
            $this->db->where($data);
        }
        $this->db->order_by('t.id_transaksi desc');
        return $this->db->get();
    }
}
