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
    }
    public function BiayaKuliahAngkatan()
    {
        $data['title'] = 'Master Data';
        $data['content'] = 'admin/biaya_kuliah_angkatan';
        $this->load->view('template', $data);
    }
}
