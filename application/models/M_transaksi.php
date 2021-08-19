<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
* file name     : M_transaksi
* file type     : models
* file packages : CodeIgniter 3
* author        : rizky ardiansyah
* date-create   : 14 Dec 2020
*/

class M_transaksi extends CI_Model
{
    // cex id tx : untuk pembuatan id transaksi
    public function cekTxId()
    {
        $this->db->select('MAX(id_transaksi) AS id_transaksi');
        $this->db->from('transaksi');
        return $this->db->get();
    }

    // cek min
    public function cekMinId($data = null)
    {
        $this->db->select('Min(id_transaksi) AS id_transaksi');
        $this->db->from('transaksi');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    // get data transaksi
    public function getDataTransaksi($data = null)
    {
        $this->db->select('t.id_transaksi, t.tanggal, t.id_transaksi, t.jam, t.semester, t.nim, t.total_bayar, t.user_id, t.status_transaksi, t.transaksi_ke, m.nm_pd, m.nm_jur, m.nm_jenj_didik, ts.icon_status_tx, ka.nm_smt, ka.smt, u.nama_user, u.ttd');
        $this->db->from('transaksi t');
        $this->db->join('mahasiswa m', 'm.nipd=t.nim');
        $this->db->join('transaksi_status ts', 'ts.kode_status_tx=t.status_transaksi');
        $this->db->join('users u', 'u.id_user=t.user_id');
        $this->db->join('kalender_akademik ka', 'ka.id_smt=t.semester');

        if ($data != null) {
            $this->db->where($data);
        }
        $this->db->order_by('t.id_transaksi desc');
        return $this->db->get();
    }

    // get data transaksi detail
    public function getDataTxDetail($data = null)
    {
        $this->db->select('
        t.id_transaksi,
        td.id_detail_transaksi,
        t.tanggal, t.jam,
        t.nim, t.semester,
        td.id_jenis_pembayaran,
        mjp.nm_jenis_pembayaran,
        td.jml_bayar
        ');
        // $this->db->from('transaksi_detail');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        $this->db->join('master_jenis_pembayaran mjp', 'td.id_jenis_pembayaran=mjp.id_jenis_pembayaran');
        if ($data != null) {
            $this->db->where($data);
        }
        $this->db->order_by('t.id_transaksi desc');
        return $this->db->get();
    }

    public function cekBayarSppdanKmhs($data)
    {
        // $this->db->distinct();
        $this->db->select_max('t.id_transaksi');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        $this->db->where($data);
        $this->db->where('td.id_jenis_pembayaran >=', 2);
        $this->db->where('td.id_jenis_pembayaran <=', 5);
        return $this->db->get();
    }

    public function cekBayarSppdanKmhsForCetak($data)
    {
        // $this->db->distinct();
        $this->db->select_min('t.id_transaksi');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        $this->db->where($data);
        $this->db->where('td.id_jenis_pembayaran >=', 2);
        $this->db->where('td.id_jenis_pembayaran <=', 7);
        return $this->db->get();
    }

    public function cekMaxDetailTransaksi($data)
    {
        $query = "SELECT `t`.`id_transaksi`, `td`.`id_detail_transaksi`, `t`.`tanggal`, `t`.`jam`, `t`.`nim`, `t`.`semester`, `td`.`id_jenis_pembayaran`, `mjp`.`nm_jenis_pembayaran`, `td`.`jml_bayar` 
        FROM `transaksi_detail` `td` 
        JOIN `transaksi` `t` ON `t`.`id_transaksi`=`td`.`id_transaksi` 
        JOIN `master_jenis_pembayaran` `mjp` ON `td`.`id_jenis_pembayaran`=`mjp`.`id_jenis_pembayaran`
        WHERE `td`.`id_detail_transaksi` IN (SELECT MAX(id_detail_transaksi)as id_detail_transaksi FROM `transaksi` `t` JOIN `transaksi_detail` `td` ON `t`.`id_transaksi`=`td`.`id_transaksi` WHERE `t`.`id_transaksi` = '$data')";
        return $this->db->query($query);
    }

    // Add New Tx
    public function addNewTransaksi($data)
    {
        return $this->db->insert('transaksi', $data);
    }

    // add Detail TX
    public function addNewDetailTransaksi($data)
    {
        return $this->db->insert('transaksi_detail', $data);
    }


    // get Data transaksi Hari ini
    public function getTxDateNow($data)
    {
        // code here...
        $this->db->select();
        $this->db->from('transaksi');
        $this->db->where($data);
        return $this->db->get()->num_rows();
    }

    public function countDataTxDetail($data = null)
    {
        /*
        SELECT count(td.id_detail_transaksi)
        FROM transaksi t
        JOIN transaksi_detail td ON t.id_transaksi=td.id_transaksi
        WHERE t.semester='20181' AND td.id_jenis_pembayaran BETWEEN 2 AND 4
        */
        $this->db->select('count(td.id_detail_transaksi) as jml');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    public function getMonthTX($data = null)
    {
        /*
        * SELECT DISTINCT(SUBSTRING(tanggal, 1, 7)) AS bulan FROM `transaksi`
        */
        $this->db->distinct();
        $this->db->select('SUBSTRING(tanggal, 1, 7) AS bulan_tx');
        $this->db->from('transaksi');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    public function getTxPerMonth($data = null)
    {
        /*
        *    SELECT SUBSTRING(B.tanggal, 1, 7) AS bulan,A.id_jenis_pembayaran AS id_jp, mjp.nm_jenis_pembayaran AS nm_JP,COUNT(A.id_jenis_pembayaran) AS Total_TX
        *    FROM transaksi_detail A 
        *    LEFT JOIN transaksi B ON A.id_transaksi = B.id_transaksi
        *    JOIN master_jenis_pembayaran mjp ON mjp.id_jenis_pembayaran=A.id_jenis_pembayaran
        *    WHERE SUBSTRING(B.tanggal, 1, 7)='2021-07'
        *    GROUP BY A.id_jenis_pembayaran
        */
        $this->db->select('SUBSTRING(tanggal, 1, 7) AS bulan_tx, td.id_jenis_pembayaran AS id_jp, mjp.nm_jenis_pembayaran AS nm_JP, COUNT(td.id_jenis_pembayaran) AS Total_TX');
        $this->db->from('transaksi_detail td');
        $this->db->join('transaksi t', 't.id_transaksi=td.id_transaksi');
        $this->db->join('master_jenis_pembayaran mjp', 'mjp.id_jenis_pembayaran=td.id_jenis_pembayaran');
        if ($data != null) {
            $this->db->where($data);
        }
        $this->db->group_by('td.id_jenis_pembayaran');
        return $this->db->get();
    }
}
