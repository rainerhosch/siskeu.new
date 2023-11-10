<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * file name     : M_masterdata
 * file type     : models
 * file packages : CodeIgniter 3
 * author        : rizky ardiansyah
 * date-create   : 14 Dec 2020
 */

class M_masterdata extends CI_Model
{
    // get data mahasiswa from simak
    // public function getMahasiswa($nipd)
    // {
    //     $url = "https://api.wastu.digital/resources/mahasiswa?token=semogabahagia&nipd=" . $nipd;
    //     $curl_handle = curl_init();
    //     curl_setopt($curl_handle, CURLOPT_URL, "$url");
    //     curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($curl_handle, CURLOPT_HEADER, false);
    //     $data = curl_exec($curl_handle);
    //     curl_close($curl_handle);
    //     return $data;
    // }

    public function getDataAngkatan($where = null)
    {
        $this->db->distinct();
        $this->db->select('tahun_masuk');
        $this->db->from('mahasiswa');
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->group_by('tahun_masuk');
        $this->db->order_by('tahun_masuk', 'DESC');

        return $this->db->get();
    }

    public function getProdi($where = null)
    {
        $this->db->distinct();
        $this->db->select('nm_jur');
        $this->db->from('mahasiswa');
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->group_by('nm_jur');
        $this->db->order_by('id_jur', 'ASC');

        return $this->db->get();
    }

    public function getKelas($where = null)
    {
        $this->db->distinct();
        $this->db->select('m.id_kelas as id_kelas,wk.nama_kelas as nama_kelas');
        $this->db->from('mahasiswa m');
        $this->db->join('wastu_kelas wk', 'wk.id_kelas=m.id_kelas');
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->group_by('m.id_kelas');
        $this->db->order_by('m.id_kelas', 'ASC');

        return $this->db->get();
    }


    public function getDataListMhs($where = null)
    {
        $this->db->distinct();
        $this->db->select('*');
        $this->db->from('mahasiswa');
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get();
    }

    public function getDataPembayaranChart($where = null)
    {
        // $this->db->distinct();
        $this->db->select('t.*');
        $this->db->from('transaksi t');
        $this->db->join('mahasiswa m', 'm.nipd=t.nim');
        $this->db->join('transaksi_detail td', 'td.id_transaksi=t.id_transaksi');
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->group_by('m.nipd');
        return $this->db->get();
    }
    public function getDataDetailPembayaranChart($where = null)
    {
        // $this->db->distinct();
        $this->db->select('td.*, mjp.nm_jenis_pembayaran');
        $this->db->from('transaksi_detail td');
        $this->db->join('master_jenis_pembayaran mjp', 'td.id_jenis_pembayaran=mjp.id_jenis_pembayaran');
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get();
    }
    public function getDataPembayaranChartNoFIlter($where = null)
    {
        // $this->db->distinct();
        $this->db->select('t.*');
        $this->db->from('transaksi t');
        $this->db->join('mahasiswa m', 'm.nipd=t.nim');
        $this->db->join('transaksi_detail td', 'td.id_transaksi=t.id_transaksi');
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->where_in('td.id_jenis_pembayaran', [1, 2, 3]);
        $this->db->group_by('m.nipd');
        return $this->db->get();
    }
    public function getMahasiswaByNim($data)
    {
        $this->db->select('*');
        $this->db->from('mahasiswa');
        $this->db->where($data);
        return $this->db->get();
    }

    public function getDataMhs($data = null)
    {
        $this->db->select('*');
        $this->db->from('mahasiswa');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    public function getDataMhsByPrody($data = null)
    {
        $this->db->select('id_jur, nm_jur');
        $this->db->from('mahasiswa');
        if ($data != null) {
            $this->db->where($data);
        }
        $this->db->group_by('id_jur');
        return $this->db->get();
    }

    public function getDataKelas($data = null)
    {
        $this->db->select('*');
        $this->db->from('wastu_kelas');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    public function getDataMhsPagination($data = null, $limit = '', $start = '')
    {
        $this->db->select('*');
        $this->db->from('mahasiswa m');
        if ($data != null) {
            $this->db->like('m.nipd', $data, 'after');
        }

        // if limit and start provided
        if ($limit != "") {
            $this->db->limit($limit, $start);
        } else if ($start != "") {
            $this->db->limit($limit, $start);
        }
        return $this->db->get();
    }

    // insert data mahasiswa
    public function insertDataMhs($data)
    {
        $insert_mhs = $this->db->insert('mahasiswa', $data);
        if ($insert_mhs) {
            // insert to siskeu online
            $dbwastudig_siskeu = $this->load->database('wastudig_siskeu', TRUE);
            $insert_online = $dbwastudig_siskeu->insert('mahasiswa', $data);
            if ($insert_online) {
                return $insert_online;
            } else {
                return 'gagal insert online';
            }
        } else {
            return 'gagal insert lokal';
        }
    }

    // update data mahasiswa
    public function updateDataMhs($id, $data)
    {
        $this->db->where('id_pd', $id);
        $this->db->update('mahasiswa', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    // get semester aktif
    public function getSemesterAktif()
    {
        $this->db->select('id_smt, nm_smt, smt, id_thn_ajaran');
        $this->db->from('kalender_akademik');
        $this->db->where(['a_periode_aktif' => 1]);
        return $this->db->get();
    }
    // get semester aktif
    public function getDataSemester()
    {
        $this->db->select('id_smt, nm_smt, smt, id_thn_ajaran');
        $this->db->from('kalender_akademik');
        $this->db->order_by('id_smt', 'desc');
        return $this->db->get();
    }

    // get data max kalender akademik
    public function getMaxKalenderAkademik($data = null)
    {
        $this->db->select_max('id_smt');
        $this->db->from('kalender_akademik');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }
    // insert data from simak
    public function insertDataTahunAkademik($data)
    {
        return $this->db->insert('kalender_akademik', $data);
    }
    // ubah status aktif
    public function updateStatusAktif($id_smt, $data)
    {
        $this->db->where('id_smt', $id_smt);
        $this->db->update('kalender_akademik', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // get data biaya per angkatan
    public function getBiayaAngkatan($data, $jenjang)
    {
        if ($jenjang === 'S1') {
            $this->db->select('angkatan, PK as uang_bangunan, kmhs as kemahasiswaan, CS as cicilan_semester');
        } else {
            $this->db->select('angkatan, PK_D3 as uang_bangunan, kmhs as kemahasiswaan, CS_D3 as cicilan_semester');
        }
        $this->db->from('biaya_angkatan');
        $this->db->where($data);
        return $this->db->get();
    }

    public function getAllBiayaAngkatan($data)
    {
        $this->db->select('*');
        $this->db->from('biaya_angkatan');
        if ($data != null) {
            $this->db->where(['id_biaya' => $data]);
            $res = $this->db->get()->row_array();
        } else {
            $this->db->order_by('id_biaya', 'desc');
            $res = $this->db->get()->result_array();
        }
        return $res;
    }

    public function getPotonganBiayaSpp($data = null)
    {
        $this->db->select('*');
        $this->db->from('biaya_angkatan_potongan');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    // get Master Jenis Transaksi
    public function GetJenisPembayaran($data, $data2 = null)
    {
        $this->db->select('id_jenis_pembayaran as id_jp, nm_jenis_pembayaran as nm_jp');
        $this->db->from('master_jenis_pembayaran');
        $this->db->where($data);
        if ($data2 !== null) {
            if ($data2 == 'S1') {
                $this->db->where('id_jenis_pembayaran <>', '19');
            }
            if ($data2 == 'D3') {
                $this->db->where('id_jenis_pembayaran <>', '14');
            }
        }
        $this->db->order_by('nm_jp', 'asc');
        return $this->db->get();
    }
    // get Master Jenis Transaksi
    public function GetAllJenisTrx($data = null)
    {
        $this->db->select('*');
        $this->db->from('master_jenis_pembayaran');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    public function GetAllJenisPembayaran($data = null)
    {
        $this->db->select('id_jenis_pembayaran as id_jp, nm_jenis_pembayaran as nm_jp');
        $this->db->from('master_jenis_pembayaran');
        if ($data !== null) {
            $this->db->where($data);
        }
        $this->db->order_by('nm_jp', 'asc');
        return $this->db->get();
    }

    public function GetAllJenisPembayaranTunggakan($data = null)
    {
        $this->db->select('id_jenis_pembayaran as id_jp, nm_jenis_pembayaran as nm_jp');
        $this->db->from('master_jenis_pembayaran');
        if ($data !== null) {
            $this->db->where_not_in('id_jenis_pembayaran', $data);
        }
        $this->db->order_by('nm_jp', 'asc');
        return $this->db->get();
    }

    public function getBiayaPembayaranLain($data = null)
    {
        $this->db->select('mjp.id_jenis_pembayaran as id_jp, mjp.nm_jenis_pembayaran as nm_jp, bt.biaya, bt.potongan_biaya');
        $this->db->from('biaya_tambahan bt');
        $this->db->join('master_jenis_pembayaran mjp', 'bt.id_jenis_pembayaran=mjp.id_jenis_pembayaran');
        if ($data != null) {
            $this->db->where($data);
        }
        $this->db->order_by('nm_jp', 'asc');
        return $this->db->get();
    }


    // Add data
    public function insertData($table, $data)
    {
        $this->db->insert($table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    // Update Biaya Spp
    public function updateBiayaSpp($id, $data)
    {
        $this->db->where('id_biaya', $id);
        $this->db->update('biaya_angkatan', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updatePotonganBiayaCS($id, $data)
    {
        $this->db->where('id_potongan', $id);
        $this->db->update('biaya_angkatan_potongan', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateBiayaLainnya($id, $data)
    {
        $this->db->where('id_jenis_pembayaran', $id);
        $this->db->update('biaya_tambahan', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateJenisPembayaran($id, $data)
    {
        $this->db->where('id_jenis_pembayaran', $id);
        $this->db->update('master_jenis_pembayaran', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Delete data
    public function deleteData($table, $data)
    {
        $this->db->where($data);
        $this->db->delete($table);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getRegMhs($data = null, $limit = null, $start = null)
    {
        $this->db->select('ID_Reg, Tahun, Identitas_ID, Jurusan_ID, NIM, tgl_reg, aktif, keterangan, aktif_by');
        $this->db->from('reg_mhs');
        if ($data != null) {
            $this->db->where($data);
        }
        // if limit and start provided
        if ($limit != null) {
            $this->db->limit($limit, '');
        } else if ($start != null) {
            $this->db->limit(9999999999999999999999999, $start);
        }
        return $this->db->get();
    }

    public function getRegUjian($data = null, $limit = null, $start = null)
    {
        $this->db->select('id_reg, tahun, nim, tgl_reg, aktif, keterangan, aktif_by');
        $this->db->from('reg_ujian');
        if ($data != null) {
            $this->db->where($data);
        }
        // if limit and start provided
        if ($limit != null) {
            $this->db->limit($limit, '');
        } else if ($start != null) {
            $this->db->limit(9999999999999999999999999, $start);
        }
        return $this->db->get();
    }

    public function GetPembayaranPS($data = null)
    {
        $this->db->select('td.jml_bayar');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 'td.id_transaksi=t.id_transaksi');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }
}