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

        $this->load->model('M_masterdata', 'masterdata');
    }
    public function BiayaSpp()
    {
        $data['title'] = 'Master Data';
        $data['page'] = 'Biaya Spp';
        $data['content'] = 'admin/biaya_spp';
        $this->load->view('template', $data);
    }

    public function BiayaPembayaranLain()
    {

        $data['title'] = 'Master Data';
        $data['page'] = 'Biaya Lainnya';
        $data['content'] = 'admin/biaya_lainnya';
        $this->load->view('template', $data);
    }

    public function GetBiayaSpp()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id_biaya');
            $data = $this->masterdata->getAllBiayaAngkatan($id);
        } else {
            $data = "Invalid Request";
        }
        echo json_encode($data);
    }

    public function GetAllBiayaLainnya()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id_jp');
            if ($id != null) {
                $where = ['mjp.id_jenis_pembayaran' => $id];
                $data = $this->masterdata->getBiayaPembayaranLain($where)->row_array();
            } else {
                $where = 'mjp.id_jenis_pembayaran != 8';
                $data = $this->masterdata->getBiayaPembayaranLain($where)->result_array();
            }
        } else {
            $data = "Invalid Request";
        }
        echo json_encode($data);
    }

    public function insertBiayaSpp()
    {
        $tahun_angkatan = $this->input->post('tahun_angkatan');
        $PK             = $this->input->post('biaya_bangunan');
        $CS             = $this->input->post('biaya_CS');
        $CS_D3          = $this->input->post('biaya_CS_D3');
        $kmhs           = $this->input->post('biaya_kmhs');
        $kmhs_D3        = $this->input->post('biaya_kmhs_D3');

        $table = 'biaya_angkatan';
        $dataInsert   = [
            'angkatan'  => $tahun_angkatan,
            'PK'        => $PK,
            'kmhs'      => $kmhs,
            'kmhs_D3'   => $kmhs_D3,
            'CS'        => $CS,
            'CS_D3'     => $CS_D3
        ];
        $insert = $this->masterdata->insertData($table, $dataInsert);
        if (!$insert) {
            // error
            $this->session->set_flashdata('error', 'Gagal insert data!');
            redirect('masterdata/BiayaSpp');
        } else {
            $this->session->set_flashdata('success', 'Sukses menambahkan data biaya, angkatan ' . $tahun_angkatan . '!');
            redirect('masterdata/BiayaSpp');
        }
    }


    public function insertBiayaLainnya()
    {
        $nm_jp          = $this->input->post('nm_jp');
        $biaya          = $this->input->post('biaya');
        $dataInsertJP = ['nm_jenis_pembayaran' => $nm_jp];
        $table_jp = 'master_jenis_pembayaran';
        $table_bt = 'biaya_tambahan';


        $response_id = $this->masterdata->insertData($table_jp, $dataInsertJP);
        $dataInsertBiaya = [
            'id_jenis_pembayaran' => $response_id,
            'biaya' => $biaya
        ];

        $update = $this->masterdata->insertData($table_bt, $dataInsertBiaya);
        if (!$update) {
            // error
            $this->session->set_flashdata('error', 'Gagal insert data!');
            redirect('masterdata/BiayaPembayaranLain');
        } else {
            $this->session->set_flashdata('success', 'Sukses menambahkan data, ' . $nm_jp . '!');
            redirect('masterdata/BiayaPembayaranLain');
        }
    }

    public function UpdateBiayaSpp()
    {
        // code here
        $id             = $this->input->post('id_biaya');
        $tahun_angkatan = $this->input->post('edit_tahun_angkatan');
        $PK             = $this->input->post('edit_biaya_bangunan');
        $CS             = $this->input->post('edit_biaya_CS');
        $CS_D3          = $this->input->post('edit_biaya_CS_D3');
        $kmhs           = $this->input->post('edit_biaya_kmhs');
        $kmhs_D3        = $this->input->post('edit_biaya_kmhs_D3');
        $dataUpdate   = [
            'angkatan'  => $tahun_angkatan,
            'PK'        => $PK,
            'kmhs'      => $kmhs,
            'kmhs_D3'   => $kmhs_D3,
            'CS'        => $CS,
            'CS_D3'     => $CS_D3
        ];
        $update = $this->masterdata->updateBiayaSpp($id, $dataUpdate);
        if (!$update) {
            // error
            $this->session->set_flashdata('error', 'Gagal edit data!');
            redirect('masterdata/BiayaSpp');
        } else {
            $this->session->set_flashdata('success', 'Sukses edit data biaya, angkatan ' . $tahun_angkatan . '!');
            redirect('masterdata/BiayaSpp');
        }
    }

    public function UpdateBiayaLainnya()
    {
        // var_dump($this->input->post());
        // die;
        $id             = $this->input->post('edit_id_jp');
        $nm_jp          = $this->input->post('edit_nm_jp');
        $biaya          = $this->input->post('edit_biaya');
        $dataUpdateBiaya = ['biaya' => $biaya];
        $dataUpdateNamaJP = ['nm_jenis_pembayaran' => $nm_jp];

        // var_dump($id);
        // die;
        $this->masterdata->updateJenisPembayaran($id, $dataUpdateNamaJP);
        $update = $this->masterdata->updateBiayaLainnya($id, $dataUpdateBiaya);
        if (!$update) {
            // error
            $this->session->set_flashdata('error', 'Gagal edit data!');
            redirect('masterdata/BiayaPembayaranLain');
        } else {
            $this->session->set_flashdata('success', 'Sukses edit data, ' . $nm_jp . '!');
            redirect('masterdata/BiayaPembayaranLain');
        }
    }

    public function deleteDataSpp()
    {
        $id             = $this->input->post('hapus_id_biaya');
        $table          = 'biaya_angkatan';
        $where          = [
            'id_biaya' => $id
        ];
        $deleted = $this->masterdata->deleteData($table, $where);
        if (!$deleted) {
            // error
            $this->session->set_flashdata('error', 'Gagal hapus data!');
            redirect('masterdata/BiayaPembayaranLain');
        } else {
            $this->session->set_flashdata('success', 'Data berhasil di hapus!');
            redirect('masterdata/BiayaPembayaranLain');
        }
    }

    public function deleteDataPembayaranLain()
    {
        $id             = $this->input->post('hapus_id_biaya');
        $where          = [
            'id_jenis_pembayaran' => $id
        ];
        $table_jp = 'master_jenis_pembayaran';
        $table_bt = 'biaya_tambahan';
        $deleted1 = $this->masterdata->deleteData($table_jp, $where);
        if (!$deleted1) {
            // error
            $this->session->set_flashdata('error', 'Gagal hapus Jenis Pembayaran!');
            redirect('masterdata/BiayaPembayaranLain');
        } else {
            $deleted2 = $this->masterdata->deleteData($table_bt, $where);
            if (!$deleted2) {
                $this->session->set_flashdata('error', 'Gagal hapus Biaya!');
                redirect('masterdata/BiayaPembayaranLain');
            } else {
                $this->session->set_flashdata('success', 'Data berhasil di hapus!');
                redirect('masterdata/BiayaPembayaranLain');
            }
        }
    }
}
