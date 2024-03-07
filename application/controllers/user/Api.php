<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *  File Name             : Api.php
 *  File Type             : Controller
 *  File Package          : CI_Controller
 ** * * * * * * * * * * * * * * * * * **
 *  Author                : Rizky Ardiansyah
 *  Date Created          : 13/09/2022
 *  Quots of the code     : 'Hanya seorang yang hobi berbicara dengan komputer.'
 */
class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_api', 'api');
    }

    public function index()
    {
        $data_param = [
            'query' => [
                'type' => 'count',
                // 'thn_akademik' => '20212',
                // 'nipd' => '141351059'
            ]
        ];
        $data = $this->api->mGet('Dosen', $data_param);
        var_dump($data);
    }
}
