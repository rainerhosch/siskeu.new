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

    public function __construct()
    {
        parent::__construct();
        $this->_dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
    }


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

    function getIdTrx($data)
    {
        /*
        SELECT trx.*, td.jml_bayar, td.id_jenis_pembayaran, mjp.nm_jenis_pembayaran, mjp.jenis_kas 
        FROM transaksi trx JOIN transaksi_detail td ON td.id_transaksi=trx.id_transaksi 
        LEFT JOIN master_jenis_pembayaran mjp ON mjp.id_jenis_pembayaran=td.id_jenis_pembayaran 
        WHERE trx.semester=20222 AND mjp.jenis_kas=1 ORDER BY `id_transaksi` DESC;
        */
        // $this->db->select('trx.*, td.jml_bayar, td.id_jenis_pembayaran, mjp.nm_jenis_pembayaran, mjp.jenis_kas ');
        $this->db->select('trx.*');
        $this->db->from('transaksi trx');
        $this->db->join('transaksi_detail td', 'td.id_transaksi=trx.id_transaksi', 'left');
        $this->db->join('master_jenis_pembayaran mjp', 'mjp.id_jenis_pembayaran=td.id_jenis_pembayaran', 'left');
        $this->db->where($data['where']);
        $this->db->group_by('trx.id_transaksi');
        return $this->db->get();
    }

    function getTrxByNim($data)
    {
        $this->db->select('trx.*');
        $this->db->from('transaksi trx');
        $this->db->join('transaksi_detail td', 'td.id_transaksi=trx.id_transaksi', 'left');
        $this->db->join('master_jenis_pembayaran mjp', 'mjp.id_jenis_pembayaran=td.id_jenis_pembayaran', 'left');
        $this->db->where($data['where']);
        $this->db->group_by('trx.nim');
        return $this->db->get();
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

    public function getDataTransaksiOnly($data = null)
    {
        $this->db->select('*');
        $this->db->from('transaksi');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    public function getDataDetailTransaksiOnly($data = null)
    {
        $this->db->select('*');
        $this->db->from('transaksi_detail');
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
        $insertSiske = $this->db->insert('transaksi', $data);
        if (!$insertSiske) {
            return FALSE;
        } else {
            $dbwastudig_siskeu = $this->load->database('wastudig_siskeu', TRUE);
            $dbwastudig_siskeu->insert('transaksi', $data);
            if (!$dbwastudig_siskeu) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }
    public function syncTransaksi($data)
    {
        $dbwastudig_siskeu = $this->load->database('wastudig_siskeu', TRUE);
        $dbwastudig_siskeu->insert('transaksi', $data);
        if (!$dbwastudig_siskeu) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function syncDetailTransaksi($data)
    {
        $dbwastudig_siskeu = $this->load->database('wastudig_siskeu', TRUE);
        $dbwastudig_siskeu->insert('transaksi_detail', $data);
        if (!$dbwastudig_siskeu) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    // add Detail TX
    public function addNewDetailTransaksi($data)
    {
        $insertSiske = $this->db->insert('transaksi_detail', $data);
        if (!$insertSiske) {
            return FALSE;
        } else {
            $dbwastudig_siskeu = $this->load->database('wastudig_siskeu', TRUE);
            $dbwastudig_siskeu->insert('transaksi_detail', $data);
            if (!$dbwastudig_siskeu) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    // Delete Transaksi
    public function deleteTransaksi($data)
    {
        $this->db->where($data);
        $this->db->delete('transaksi');
        if ($this->db->affected_rows() > 0) {
            $dbwastudig_siskeu = $this->load->database('wastudig_siskeu', TRUE);
            $dbwastudig_siskeu->where($data);
            $dbwastudig_siskeu->delete('transaksi');
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
            $dbwastudig_siskeu = $this->load->database('wastudig_siskeu', TRUE);
            $dbwastudig_siskeu->where($data);
            $dbwastudig_siskeu->delete('transaksi_detail');
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

    // isert bukti transfer to siskeu
    public function insertBuktiTrfToSiskeu($data)
    {
        return $this->db->insert('adm_bukti_pembayaran', $data);
    }
    // get data transfer from simak
    public function getDataBuktiPembayaran($where = null, $limit = null, $offset = null)
    {
        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->select('bp.*, mjp.nm_jenis_pembayaran');
        $dbwastudig_simak->from('adm_bukti_pembayaran bp');
        $dbwastudig_simak->join('adm_master_jenis_pembayaran mjp', 'bp.id_jenis_bayar=mjp.id_jenis_pembayaran');
        if ($where != null) {
            // $dbwastudig_simak->group_start();
            $dbwastudig_simak->where($where);
            // $dbwastudig_simak->like('nipd', $where);
            // $dbwastudig_simak->or_like('nmpd', $where);
            // $dbwastudig_simak->group_end();
        }
        $dbwastudig_simak->order_by('id_bukti_trf', 'DESC');
        if ($limit != null && $offset != null) {
            $dbwastudig_simak->limit($limit, $offset);
        }
        return $dbwastudig_simak->get();
    }

    private $_column_order = array('bp.id_bukti_trf', 'bp.nipd', 'mjp.nm_jenis_pembayaran', 'bp.tgl_trf'); // Set kolom yang bisa diurutkan
    private $_column_search = array('bp.nipd');

    private function _data_tables_query()
    {
        $i = 0;

        $this->_dbwastudig_simak->select('bp.*, mjp.nm_jenis_pembayaran');
        $this->_dbwastudig_simak->from('adm_bukti_pembayaran bp');
        $this->_dbwastudig_simak->join('adm_master_jenis_pembayaran mjp', 'bp.id_jenis_bayar=mjp.id_jenis_pembayaran');
        // for search
        foreach ($this->_column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->_dbwastudig_simak->group_start();
                    $this->_dbwastudig_simak->like($item, $_POST['search']['value']);
                } else {
                    $this->_dbwastudig_simak->or_like($item, $_POST['search']['value']);
                }

                if (count($this->_column_search) - 1 == $i)
                    $this->_dbwastudig_simak->group_end();
            }
            $i++;
        }

        // config order
        $this->_dbwastudig_simak->order_by('id_bukti_trf', 'DESC');
        // if (isset($_POST['order'])) {
        //     $this->_dbwastudig_simak->order_by($this->_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        // } else if (isset($this->order)) {
        //     $order = $this->order;
        //     $this->_dbwastudig_simak->order_by(key($order), $order[key($order)]);
        // }
        // return $this->_dbwastudig_simak->get();
    }

    public function getDataBuktiPembayaranDataTables()
    {
        $this->_data_tables_query();
        if ($_POST['length'] != -1)
            $this->_dbwastudig_simak->limit($_POST['length'], $_POST['start']);
        return $this->_dbwastudig_simak->get();
    }

    public function count_filtered_databuktipembayaran()
    {
        $this->_data_tables_query();
        return $this->_dbwastudig_simak->get();
    }

    public function count_all_databuktipembayaran()
    {
        $this->_dbwastudig_simak->select('bp.*, mjp.nm_jenis_pembayaran');
        $this->_dbwastudig_simak->from('adm_bukti_pembayaran bp');
        $this->_dbwastudig_simak->join('adm_master_jenis_pembayaran mjp', 'bp.id_jenis_bayar=mjp.id_jenis_pembayaran');
        return $this->_dbwastudig_simak->count_all_results();
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

    public function getCountTrxAdmin($where = null)
    {
        $this->db->select('u.nama_user, t.user_id AS admin_id, COUNT(t.id_transaksi) AS jumlah_transaksi');
        $this->db->from('transaksi t');
        $this->db->join('users u', 't.user_id=u.id_user');
        if(isset($where)) {
            $this->db->where($where);
            // $this->db->where('status_transaksi', '1'); // Filter jika hanya ingin transaksi sukses
            // $this->db->where('tanggal >=', '2025-01-01');  // Filter tanggal mulai
            // $this->db->where('tanggal <=', '2025-01-09');  // Filter tanggal akhir
        }
        $this->db->group_by('user_id');
        $this->db->order_by('jumlah_transaksi', 'DESC');
        return $query = $this->db->get();

        // return $query->result_array(); // Mengembalikan hasil dalam bentuk array
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
