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
    var $column_order = array('t.id_transaksi', 't.tanggal', 't.jam', 't.semester', 't.nim', 't.user_id', 't.status_transaksi', 't.transaksi_ke', 'm.nm_pd', 'm.nm_jur', 'm.nm_jenj_didik', 'ts.icon_status_tx', 'u.nama_user', 'u.ttd'); //set column field database for datatable orderable
    var $column_search = array('t.id_transaksi', 't.tanggal', 't.jam', 't.semester', 't.nim',); //set column field database for datatable searchable 
    var $order = array('t.id_transaksi' => 'desc'); // default order 

    private function _get_datatables_query()
    {
        $this->db->from('transaksi t');
        $this->db->join('mahasiswa m', 'm.nipd=t.nim');
        $this->db->join('transaksi_status ts', 'ts.kode_status_tx=t.status_transaksi');
        $this->db->join('users u', 'u.id_user=t.user_id');
        $i = 0;
        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('transaksi');
        return $this->db->count_all_results();
    }






    // cex id tx : untuk pembuatan id transaksi
    public function cekTxId()
    {
        $this->db->select('MAX(id_transaksi) AS id_transaksi');
        $this->db->from('transaksi');
        return $this->db->get();
    }

    public function get_data_rekening($data = null)
    {
        $this->db->select('id_rek, bank, nama_rekening, no_rek');
        $this->db->from('data_rek_bank');
        if ($data != null) {
            $this->db->where($data);
        }
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


    public function getDataTransaksiPagenation($data = null, $limit = '', $start = '', $where = null)
    {
        $this->db->select('t.id_transaksi, t.tanggal, t.id_transaksi, t.jam, t.semester, t.nim, t.user_id, t.status_transaksi, t.transaksi_ke, t.bayar_via, t.rekening_trf, t.tgl_trf, t.jam_trf, t.uang_masuk, m.nm_pd, m.nm_jur, m.nm_jenj_didik, ts.icon_status_tx, drb.bank, drb.nama_rekening, u.nama_user, u.ttd');
        $this->db->from('transaksi t');
        $this->db->join('mahasiswa m', 'm.nipd=t.nim');
        $this->db->join('transaksi_status ts', 'ts.kode_status_tx=t.status_transaksi');
        $this->db->join('data_rek_bank drb', 't.rekening_trf=drb.id_rek', 'left');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        $this->db->join('master_jenis_pembayaran mjp', 'td.id_jenis_pembayaran=mjp.id_jenis_pembayaran');
        $this->db->join('users u', 'u.id_user=t.user_id');

        if ($where != null) {
            $this->db->where($where);
        }
        if ($data != null) {
            $this->db->like('m.nipd', $data, 'after');
            $this->db->or_like('m.nm_pd', $data, 'after');
        }

        // if limit and start provided
        if ($limit != "") {
            $this->db->limit($limit, $start);
        } else if ($start != "") {
            $this->db->limit($limit, $start);
        }
        $this->db->group_by('t.id_transaksi');

        $this->db->order_by('t.id_transaksi desc');
        return $this->db->get();
    }

    // get data transaksi
    public function getDataTransaksi($data = null)
    {
        // $this->db->select('t.id_transaksi, t.tanggal, t.id_transaksi, t.jam, t.semester, t.nim, t.total_bayar, t.user_id, t.status_transaksi, t.transaksi_ke, m.nm_pd, m.nm_jur, m.nm_jenj_didik, ts.icon_status_tx, ka.nm_smt, ka.smt, u.nama_user, u.ttd');
        $this->db->select('t.id_transaksi, t.tanggal, t.id_transaksi, t.jam, t.semester, t.nim, t.user_id, t.status_transaksi, t.transaksi_ke, m.nm_pd, m.nm_jur, m.nm_jenj_didik, ts.icon_status_tx, u.nama_user, u.ttd');
        $this->db->from('transaksi t');
        $this->db->join('mahasiswa m', 'm.nipd=t.nim');
        $this->db->join('transaksi_status ts', 'ts.kode_status_tx=t.status_transaksi');
        $this->db->join('users u', 'u.id_user=t.user_id');
        // $this->db->join('kalender_akademik ka', 'ka.id_smt=t.semester', 'left');

        if ($data != null) {
            $this->db->where($data);
        }
        $this->db->order_by('t.id_transaksi desc');
        return $this->db->get();
    }

    public function updateData($data)
    {
        $this->db->where($data['id']);
        $this->db->update($data['table'], $data['dataUpdate']);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getDataTransaksiSebelumnya($data = null)
    {
        $this->db->select('
        t.id_transaksi,
        td.id_detail_transaksi,
        t.tanggal, t.jam,
        t.nim, t.semester,
        td.id_jenis_pembayaran,
        mjp.nm_jenis_pembayaran,
        td.jml_bayar,
        td.potongan
        ');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 't.id_transaksi=td.id_transaksi');
        $this->db->join('master_jenis_pembayaran mjp', 'td.id_jenis_pembayaran=mjp.id_jenis_pembayaran');

        if ($data != null) {
            $this->db->where($data);
        }
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
        td.jml_bayar,
        td.potongan
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
        $query = "SELECT `t`.`id_transaksi`, `td`.`id_detail_transaksi`, `t`.`tanggal`, `t`.`jam`, `t`.`nim`, `t`.`semester`, `td`.`id_jenis_pembayaran`, `mjp`.`nm_jenis_pembayaran`, `td`.`jml_bayar`, `td`.`potongan`
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

    // Delete Transaksi
    public function deleteTransaksi($data)
    {
        $this->db->where($data);
        $this->db->delete('transaksi');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    // Delete Transaksi Detail
    public function deleteTransaksiDetail($data)
    {
        $this->db->where($data);
        $this->db->delete('transaksi_detail');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // get data transfer mhs
    // public function getBuktiTransfer($data = null)
    // {
    //     $this->db->select('bp.*, m.nm_pd, mjp.nm_jenis_pembayaran');
    //     $this->db->from('bukti_pembayaran bp');
    //     $this->db->join('mahasiswa m', 'm.nipd=bp.nipd');
    //     $this->db->join('master_jenis_pembayaran mjp', 'bp.id_jenis_bayar=mjp.id_jenis_pembayaran');
    //     if ($data != null) {
    //         $this->db->where($data);
    //     }
    //     return $this->db->get();
    // }


    // get data transfer from simak
    public function getDataBuktiPembayaran($where = null)
    {

        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->select('bp.*, mjp.nm_jenis_pembayaran');
        $dbwastudig_simak->from('adm_bukti_pembayaran bp');
        $dbwastudig_simak->join('adm_master_jenis_pembayaran mjp', 'bp.id_jenis_bayar=mjp.id_jenis_pembayaran');
        if ($where != null) {
            $dbwastudig_simak->where($where);
        }
        $dbwastudig_simak->order_by('id_bukti_trf', 'DESC');
        return $dbwastudig_simak->get();
    }

    public function updateBuktiPembayaran($filter, $data)
    {
        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->where($filter);
        $dbwastudig_simak->update('adm_bukti_pembayaran', $data);
        if ($dbwastudig_simak->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // get data transfer from simak
    public function getBuktiTransfer($data = null)
    {
        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->select('*');
        $dbwastudig_simak->from('adm_bukti_pembayaran');
        if ($data != null) {
            $dbwastudig_simak->where($data);
        }
        return $dbwastudig_simak->get();
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
