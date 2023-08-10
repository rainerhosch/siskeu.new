<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : MigrasiTrxToTg.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 13/09/2022
 *  Quots of the code     : 'Hanya seorang yang hobi berbicara dengan komputer.'
 */
class MigrasiTrxToTg extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Utility';
        $data['content'] = 'layout/my_error';
        $this->load->view('template', $data);
    }
}