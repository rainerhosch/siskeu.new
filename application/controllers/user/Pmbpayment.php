<?php 

defined('BASEPATH') or exit('No direct script access allowed');
/**
    *  File Name             : Pmbpayment.php
    *  File Type             : Controller
    *  File Package          : CI_Controller
    ** * * * * * * * * * * * * * * * * * **
    *  Author                : Rizky Ardiansyah
    *  Date Created          : 29/02/2024
    *  Quots of the code     : 'Hanya seorang yang hobi berbicara dengan komputer.'
*/
class Pmbpayment extends CI_Controller
{
        public function __construct()
        {
            parent::__construct();
            if ($this->session->has_userdata('username') == null) {
                $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button> <h4><i class='icon fa fa-warning'></i> Alert!</h4> Harus Login Terlebih Dahulu</div>");
                redirect(base_url());
            }
            date_default_timezone_set('Asia/Jakarta');
            $this->load->library('Terbilang');
            $this->load->library('FormatTanggal');
            $this->load->model('M_pmb', 'pmbgateway');
        }

        public function index()
        {
            // code here...
            $data['title'] = 'SiskeuNEW';
            $data['page'] = 'PMB';
            $data['content'] = 'pmb/v_pembayaran';
            // $where_date = [
            //     'tanggal' => date('Y-m-d')
            // ];
            // $data['jumlah_tx_hari_ini'] = $this->transaksi->getTxDateNow($where_date);
            $this->load->view('template', $data);
        }
        public function getdatatrf()
        {
            if ($this->input->is_ajax_request()) {
                $res = $this->pmbgateway->getData(['bukti_tf !=' => ''])->result_array();
                $data = [
                    'status' => true,
                    'code' => 200,
                    'msg' => 'Ok!',
                    'data' => $res
                ];
            } else {
                $data = [
                    'status' => false,
                    'code' => 500,
                    'msg' => 'Invalid Request!',
                    'data' => null
                ];
            }
            echo json_encode($data);
        }

        public function acc_payment()
        {
            if ($this->input->is_ajax_request()) {
                $id_pendaftar = $this->input->post('id');
                $update = $this->pmbgateway->updateDataPmbRegister(['id'=>$id_pendaftar],['status' => 2]);
                if($update){
                    $data = [
                        'status' => true,
                        'code' => 200,
                        'msg' => 'Ok!',
                        'data' => 1
                    ];
                }else{
                    $data = [
                        'status' => false,
                        'code' => 201,
                        'msg' => 'update error!',
                        'data' => 0
                    ];
                }
            } else {
                $data = [
                    'status' => false,
                    'code' => 500,
                    'msg' => 'Invalid Request!',
                    'data' => null
                ];
            }
            echo json_encode($data);
        }
        public function reject_payment()
        {
            if ($this->input->is_ajax_request()) {
                $id_pendaftar = $this->input->post('id');
                $update = $this->pmbgateway->updateDataPmbRegister(['id' => $id_pendaftar],['status' => 4]);
                if($update){
                    $data = [
                        'status' => true,
                        'code' => 200,
                        'msg' => 'Ok!',
                        'data' => 1
                    ];
                }else{
                    $data = [
                        'status' => false,
                        'code' => 201,
                        'msg' => 'update error!',
                        'data' => 0
                    ];
                }
            } else {
                $data = [
                    'status' => false,
                    'code' => 500,
                    'msg' => 'Invalid Request!',
                    'data' => null
                ];
            }
            echo json_encode($data);
        }

}