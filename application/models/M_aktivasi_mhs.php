<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : M_reg_mhs.php
 *  File Type             : Model
 *  File Package          : CI_Models
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 12/07/2021
 *  Quots of the code     : 'rapihkan lah code mu, seperti halnya kau menata kehidupan'
 */
class M_aktivasi_mhs extends CI_Model
{

    public function cekStatusKelulusanMhs($data = null)
    {
        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->select('*');
        $dbwastudig_simak->from('mahasiswa_pt');
        if ($data != null) {
            $dbwastudig_simak->where($data);
        }
        return $dbwastudig_simak->get();

    }

    public function cekKrsMhsSimak($data = null)
    {
        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->select('*');
        $dbwastudig_simak->from('krs_new');
        if ($data != null) {
            $dbwastudig_simak->where($data);
        }
        $dbwastudig_simak->group_by('nipd');
        return $dbwastudig_simak->get();
    }

    public function cekKrsMhsSimakBefor($data = null)
    {
        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->select('*');
        $dbwastudig_simak->from('krs_new');
        if ($data != null) {
            $dbwastudig_simak->where($data);
        }
        return $dbwastudig_simak->get();
    }

    public function cekKrsMhsLokal($data = null)
    {
        $this->db->select('*');
        $this->db->from('krs_new');
        if ($data != null) {
            $this->db->where($data);
        }
        // $this->db->group_by('nipd');
        return $this->db->get();
    }

    public function insertKrsToLocal($data)
    {
        $insert_mhs = $this->db->insert('krs_new', $data);
        if ($insert_mhs) {
            return $insert_mhs;
        } else {
            return 'gagal insert lokal';
        }
    }
    public function cekKrsMhsSebelumnyaLokal($data = null)
    {
        $this->db->select('id_krs, nipd, id_tahun_ajaran');
        $this->db->from('krs_new');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    public function input_data_dispen_mhs($data)
    {
        return $this->db->insert('dispensasi', $data);
    }

    public function cekStatusAktifMhs($data = null, $table = '')
    {
        $this->db->select('m.nipd, m.nm_pd, m.nm_jur, t.aktif');
        $this->db->from($table);
        $this->db->join('mahasiswa m', 'm.nipd=t.nim');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    public function cekStatusAktifSimak($data = null, $table = '', $datawherein = null)
    {

        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->select('mpt.nipd, m.nm_pd, j.nm_jur, t.aktif');
        $dbwastudig_simak->from($table);
        $dbwastudig_simak->join('mahasiswa_pt mpt', 'mpt.nipd=t.nim');
        $dbwastudig_simak->join('jurusan j', 'j.id_jur=mpt.id_sms');
        $dbwastudig_simak->join('mahasiswa m', 'm.id_pd=mpt.id_pd');
        if ($data != null) {
            $dbwastudig_simak->where($data);
        }
        if ($datawherein != null) {
            $dbwastudig_simak->where_in('t.aktif', $datawherein);
        }
        return $dbwastudig_simak->get();
    }

    public function getDataDispenMhs($data = null)
    {
        /*
        SELECT d.id_dispensasi, d.tanggal_input, m.nipd, m.nm_pd, m.nm_jur, d.tanggal_lunas, d.no_tlp, d.tg_dispen 
        FROM dispensasi d 
        JOIN mahasiswa m ON m.id_pd=d.id_reg_pd;
        */
        // $this->db->select('*');
        // $this->db->select('d.id_dispensasi, d.jenis_dispen, d.tanggal_input, m.id_pd, m.nipd, m.nm_pd, m.id_jur, m.nm_jur, d.tgl_janji_lunas, d.no_tlp, d.tg_dispen, d.tahun_akademik, d.tgl_pelunasan, d.status, d.jml_kirim_pesan');
        $this->db->select([
            'd.id_dispensasi',
            'd.jenis_dispen',
            'd.tanggal_input',
            'm.id_pd',
            'm.nipd',
            'm.nm_pd',
            'm.id_jur',
            'm.nm_jur',
            'd.tgl_janji_lunas',
            'd.no_tlp',
            'd.tg_dispen',
            'd.tahun_akademik',
            'd.tgl_pelunasan',
            'd.status',
            'd.jml_kirim_pesan'
        ]);
        $this->db->from('dispensasi d');
        $this->db->join('mahasiswa m', 'm.id_pd = d.id_reg_pd', 'inner');
        // $this->db->join('mahasiswa m', 'm.id_pd=d.id_reg_pd');
        // Apply where conditions if any
        if (!is_null($data)) {
            $this->db->where($data);
        }
        $this->db->order_by('d.id_dispensasi', 'desc');
        return $this->db->get();
    }

    public function getDataDispenMhsV2($data = null)
    {
        $this->db->select('d.id_dispensasi, d.jenis_dispen, d.tanggal_input, m.id_pd, m.nipd, m.nm_pd, m.id_jur, m.nm_jur, d.tgl_janji_lunas, d.no_tlp, d.tg_dispen, d.tahun_akademik, d.tgl_pelunasan, d.status, d.jml_kirim_pesan');
        $this->db->from('dispensasi d');
        $this->db->join('mahasiswa m', 'm.id_pd=d.id_reg_pd');
        // $this->db->join('kalender_akademik ka', 'ka.id_smt=d.tahun_akademik');
        if ($data != null) {
            $this->db->where($data);
            // $this->db->where('(jenis_dispen=1 or jenis_dispen=3)');
        }
        $this->db->order_by('d.id_dispensasi', 'desc');
        return $this->db->get();
    }

    public function updateDataDispenMhs($id, $data)
    {
        $this->db->where('id_dispensasi', $id);
        $this->db->update('dispensasi', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    // Delete Data
    public function deleteDataDispen($data)
    {
        $this->db->where($data);
        $this->db->delete('dispensasi');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function aktivasi_perwalian($data)
    {
        $exists = $this->db->get_where('reg_mhs', [
            'Tahun' => $data['Tahun'],
            'NIM' => $data['NIM'],
            'aktif' => $data['aktif'],
        ]);

        $count = $exists->num_rows(); //counting result from query

        if ($count === 0) {
            $insert_local = $this->db->insert('reg_mhs', $data);
            if ($insert_local) {
                // insert to simak
                $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
                return $dbwastudig_simak->insert('regmhs', $data);
            } else {
                return 'gagal insert lokal';
            }
        } else {
            // insert to simak
            $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
            $dbwastudig_simak->insert('regmhs', $data);
            return 'exists';
        }
    }

    public function aktivasi_ujian($data)
    {
        $exists = $this->db->get_where('reg_ujian', [
            'tahun' => $data['tahun'],
            'nim' => $data['nim'],
            'aktif' => $data['aktif'],
        ]);
        $count = $exists->num_rows();
        // var_dump($count);
        // die;
        if ($count === 0) {
            // CODE HERE...
            $insert_local = $this->db->insert('reg_ujian', $data);
            if ($insert_local) {
                // insert to simak
                $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
                return $dbwastudig_simak->insert('reg_ujian', $data);
            } else {
                return 'gagal insert lokal';
            }
        } else {
            // insert to simak
            $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
            $dbwastudig_simak->insert('reg_ujian', $data);
            return 'exists';
        }
    }
}