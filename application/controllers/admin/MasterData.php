<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name       : MasterData.php
 *  File Type       : Controller
 *  File Package    : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author          : Rizky Ardiansyah
 *  Date Created    : 28 Desember 2020
 */
class MasterData extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->has_userdata('username') == null) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
            redirect(base_url());
        }
    }
    public function BiayaKuliahAngkatan()
    {
        $data['title'] = 'Master Data';
        $data['content'] = 'admin/biaya_kuliah_angkatan';
        $this->load->view('template', $data);
    }
}
