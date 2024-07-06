<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
    *  File Name             : M_krs.php
    *  File Type             : Model
    *  File Package          : CI_Models
    ** * * * * * * * * * * * * * * * * * **
    *  Author                : Rizky Ardiansyah
    *  Date Created          : 7/6/2024
    *  Quots of the code     : 'sebuah code program bukanlah sebatas perintah-perintah yang ditulis di komputer, melainkan sebuah kesempatan berkomunikasi antara komputer dan manusia. (bagi yang tidak punya teman wkwk)'
*/
class M_krs extends CI_Model
{
    public function getData($param)
    {
        return $this->db->get_where('krs_new', $param);
    }

    public function getDataKrsMhs($where = null)
    {
        $this->db->distinct();
        $this->db->select('m.*');
        $this->db->from('krs_new kn');
        $this->db->join('mahasiswa m', 'm.nipd=kn.nipd');
        if ($where != null) {
            $this->db->where($where);
        }
        $this->db->group_by('kn.nipd');
        return $this->db->get();
    }
}