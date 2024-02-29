<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
    *  File Name             : M_pmb.php
    *  File Type             : Model
    *  File Package          : CI_Models
    ** * * * * * * * * * * * * * * * * * **
    *  Author                : Rizky Ardiansyah
    *  Date Created          : 29/02/2024
    *  Quots of the code     : 'sebuah code program bukanlah sebatas perintah-perintah yang ditulis di komputer, melainkan sebuah kesempatan berkomunikasi antara komputer dan manusia. (bagi yang tidak punya teman wkwk)'
*/
class M_pmb extends CI_Model
{
    public static $db_pmb;
    function getData($condition = null)
    {
        M_pmb::$db_pmb = $this->load->database('wastudig_pmb', TRUE);
        M_pmb::$db_pmb->select('*');
        M_pmb::$db_pmb->from('pmb_register');
        if ($condition != null) {
            M_pmb::$db_pmb->where($condition);
        }
        M_pmb::$db_pmb->order_by('id', 'DESC');
        return  M_pmb::$db_pmb->get();

    }

    public function updateDataPmbRegister($filter, $data)
    {
        M_pmb::$db_pmb = $this->load->database('wastudig_pmb', TRUE);
        M_pmb::$db_pmb->where($filter);
        M_pmb::$db_pmb->update('pmb_register', $data);
        if (M_pmb::$db_pmb->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}