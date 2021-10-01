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
    public function input_data_dispen_mhs($data)
    {
        return $this->db->insert('dispensasi', $data);
    }

    public function cekStatusAktifMhs($data = null, $table)
    {
        $this->db->select('m.nipd, m.nm_pd, m.nm_jur, t.aktif');
        $this->db->from($table);
        $this->db->join('mahasiswa m', 'm.nipd=t.nim');
        if ($data != null) {
            $this->db->where($data);
        }
        return $this->db->get();
    }

    public function getDataDispenMhs($data = null)
    {
        /*
        SELECT d.id_dispensasi, d.tanggal_input, m.nipd, m.nm_pd, m.nm_jur, d.tanggal_lunas, d.no_tlp, d.tg_dispen 
        FROM dispensasi d 
        JOIN mahasiswa m ON m.id_pd=d.id_reg_pd;
        */
        $this->db->select('d.id_dispensasi, d.jenis_dispen, d.tanggal_input, m.id_pd, m.nipd, m.nm_pd, m.id_jur, m.nm_jur, d.tgl_janji_lunas, d.no_tlp, d.tg_dispen, d.tahun_akademik, d.tgl_pelunasan, d.status, d.jml_kirim_pesan');
        $this->db->from('dispensasi d');
        $this->db->join('mahasiswa m', 'm.id_pd=d.id_reg_pd');
        // $this->db->join('kalender_akademik ka', 'ka.id_smt=d.tahun_akademik');
        if ($data != null) {
            $this->db->where($data);
        }
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
            return 'exists';
        }
    }
}
