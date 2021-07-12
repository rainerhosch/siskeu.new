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
    public function aktivasi_perwalian($data)
    {
        // insert to simak
        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->insert('regmhs', $data);

        // CODE HERE...
        return $this->db->insert('reg_mhs', $data);
    }

    public function aktivasi_ujian($data)
    {
        // insert to simak
        $dbwastudig_simak = $this->load->database('wastudig_simak', TRUE);
        $dbwastudig_simak->insert('reg_ujian', $data);

        // CODE HERE...
        return $this->db->insert('reg_ujian', $data);
    }
}
