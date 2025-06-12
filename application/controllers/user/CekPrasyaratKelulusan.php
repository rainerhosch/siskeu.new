<?php 
defined('BASEPATH') or exit('No direct script access allowed');
/**
    *  File Name             : CekPrasyaratKelulusan.php
    *  File Type             : Controller
    *  File Package          : CI_Controller
    ** * * * * * * * * * * * * * * * * * **
    *  Author                : Rizky Ardiansyah
    *  Date Created          : 10/06/2025
    *  Quots of the code     : 'Hanya seorang yang hobi berbicara dengan komputer.'
*/
class CekPrasyaratKelulusan extends CI_Controller
{
        public function __construct()
        {
            parent::__construct();
            $this->load->model('M_CekPrasyaratKelulusan', 'cekPrasyaratKelulusan');
            $this->load->model('M_masterdata', 'masterdata');
        }

        public function index()
        {
            // $this->load->model('user/M_CekPrasyaratKelulusan', 'cekPrasyaratKelulusan');
            $data['title'] = 'Siskeu New';
            $data['page'] = 'Cek Prasyarat Kelulusan';
            $data['content'] = 'v_cek_prasyarat_kelulusan';
            $this->load->view('template', $data);
        }
        
        public function cek_payment()
        {
            if ($this->input->is_ajax_request()) {
                $data_post = $this->input->post();
                $res = $data_post;
                echo json_encode($res);
            } else {
                show_404();
            }
        }

        public function show_data(){
            if ($this->input->is_ajax_request()) {
                // $data_post = $this->input->post();
                $data_mhs = $this->cekPrasyaratKelulusan->get_all()->result_array();
                foreach ($data_mhs as $key => $value) {
                    $filter = [1, 5, 6, 7, 10, 14, 15];
                    $data_mhs[$key]['data_trx'] = $this->masterdata->GetAllJenisTrx(null, $filter)->result_array();

                    // $param = [
                    //     'nim' => $value['nipd']
                    // ];
                    // $data_mhs[$key]['data_trx'] = $this->cekPrasyaratKelulusan->get_list_trx($param)->result_array();
                }
                $res = $data_mhs;
                echo json_encode($res);
            } else {
                show_404();
            }
        }
}