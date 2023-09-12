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

        $this->load->library('pagination');
        $this->load->model('M_masterdata', 'masterdata');
        $this->load->model('M_aktivasi_mhs', 'aktivasi');
        $this->load->model('M_api', 'api');
    }


    public function getDataKrs()
    {
        $ApiDataKrs = $this->api->mGet('KrsNew', [
            'query' => [
                'type' => null
            ]
        ]);
        echo '<pre>';
        var_dump($ApiDataKrs);
        echo '</pre>';
        die;
        // return $ApiDataKrs['krs_new'];
    }


    public function getDataMhsPerangkatan()
    {
        if ($this->input->is_ajax_request()) {
            $data_post = $this->input->post();
            // $index = 0;
            // $year_now = date('Y');
            // $res['data'] = [];
            // $data_angkatan = $this->masterdata->getDataAngkatan()->result_array();
            // foreach ($data_angkatan as $i => $da) {
            //     if ($year_now - $da['tahun_masuk'] < 7) {
            //         $res['data'][$index] = $da;
            //         $index++;
            //     }
            // }

            $smtAktifRes = $this->masterdata->getSemesterAktif()->row_array();
            $list_simak = $this->aktivasi->cekStatusKelulusanMhs()->result_array();
            $cek_krs = $this->aktivasi->cekKrsMhsSimak(['nipd !=' => ''])->result_array();

            $res = $cek_krs;
            echo json_encode($res);
        } else {
            show_404();
        }
    }

    public function Mahasiswa()
    {
        $data['title'] = 'Master Data';
        $data['page'] = 'Data Mahasiswa';
        $data['content'] = 'admin/data_mahasiswa';
        $this->load->view('template', $data);
    }

    public function GetDataMhs()
    {
        if ($this->input->is_ajax_request()) {
            $post_limit = $this->input->post('limit');
            $post_offset = $this->input->post('offset');
            $key_cari = $this->input->post('keyword');
            if ($post_offset != null) {
                $offset = $post_offset;
            } else {
                $offset = 0;
            }
            if ($post_limit != 0) {
                $limit = $post_limit;
            } else {
                $limit = 10;
            }
            if ($offset != 0) {
                $offset = ($offset - 1) * $limit;
            }
            $allcount = $this->masterdata->getDataMhsPagination()->num_rows();
            $dataMhs = $this->masterdata->getDataMhsPagination($key_cari, $limit, $offset)->result_array();
            foreach ($dataMhs as $i => $mhs) {
                $data_homebase = $this->masterdata->getDataKelas(['id_kelas' => $mhs['id_kelas']])->row_array();
                if ($data_homebase != null) {
                    $dataMhs[$i]['homebase'] = $data_homebase['nama_kelas'];
                } else {
                    $dataMhs[$i]['homebase'] = '';
                }
            }
            // Pagination Configuration
            $config['base_url'] = base_url() . 'masterdata/Mahasiswa';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $allcount;
            $config['per_page'] = $limit;

            // ============ config css pagination ======================
            $config['full_tag_open'] = "<ul class='pagination pagination-sm remove-margin'>";
            $config['full_tag_close'] = '</ul>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';

            $config['prev_link'] = '<i class="fa fa-chevron-left"></i>';
            $config['prev_tag_open'] = '<li class="prev">';
            $config['prev_tag_close'] = '</li>';


            $config['next_link'] = '<i class="fa fa-chevron-right"></i>';
            $config['next_tag_open'] = '<li class="next">';
            $config['next_tag_close'] = '</li>';
            // ============ End config css pagination ======================

            // Initialize
            $this->pagination->initialize($config);

            // Initialize $data Array
            $data['pagination'] = $this->pagination->create_links();
            $data['data_mhs'] = $dataMhs;
            $data['total_result'] = $allcount;
            $data['row'] = $offset;
            $data['user_loged'] = $this->session->userdata('id_user');
        } else {
            $data = "Invalid Request";
        }
        echo json_encode($data);
    }

    public function syncUpdateMhs()
    {
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

    public function GetPotonganBiayaSPP()
    {
        if ($this->input->is_ajax_request()) {
            $where = [
                'id_potongan' => $this->input->post('id_potongan')
            ];
            $data = $this->masterdata->getPotonganBiayaSpp($where)->row_array();
        } else {
            $data = "Invalid Request";
        }
        echo json_encode($data);
    }

    public function getAllJenisPembayaran()
    {
        if ($this->input->is_ajax_request()) {
            $response = $this->masterdata->GetAllJenisPembayaran(['id_jenis_pembayaran <>' => '1'])->result_array();
        } else {
            $response = "Invalid Request";
        }
        echo json_encode($response);
    }

    public function getAllJenisTunggakan()
    {
        if ($this->input->is_ajax_request()) {
            $response = $this->masterdata->GetAllJenisPembayaranTunggakan([1, 2, 3, 4, 5])->result_array();
            // $response = $this->db->last_query();
        } else {
            $response = "Invalid Request";
        }
        echo json_encode($response);
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


    public function duplicateBiayaSPP()
    {
        $response = [];
        if ($this->input->is_ajax_request()) {
            $data_post = $this->input->post();
            $id = $data_post['id_biaya'];
            $data = $this->masterdata->getAllBiayaAngkatan($id);

            $table = 'biaya_angkatan';
            $dataInsert = [
                'angkatan' => $data['angkatan'] + 1,
                'PK' => $data['PK'],
                'PK_D3' => $data['PK_D3'],
                'kmhs' => $data['kmhs'],
                'CS' => $data['CS'],
                'CS_D3' => $data['CS_D3'],
                // 'potongan_CS' => 0
            ];
            $insert = $this->masterdata->insertData($table, $dataInsert);
            if (!$insert) {
                // error
                $response = [
                    'status' => false,
                    'msg' => 'Gagal duplikasi data!',
                    'data' => null
                ];
                // $this->session->set_flashdata('error', 'Gagal duplikasi data!');
                // redirect('masterdata/BiayaSpp');
            } else {
                $response = [
                    'status' => true,
                    'msg' => 'Success, insert ' . $data['angkatan'] + 1 . '!',
                    'data' => $data
                ];
                // $this->session->set_flashdata('success', 'Sukses, insert ' . $data['angkatan'] + 1 . '!');
                // redirect('masterdata/BiayaSpp');
            }
        } else {
            $response = [
                'code' => false,
                'msg' => 'Invalid Request!',
                'data' => null
            ];
        }
        echo json_encode($response);
    }

    public function insertBiayaSpp()
    {
        $tahun_angkatan = $this->input->post('tahun_angkatan');
        $PK = $this->input->post('biaya_bangunan');
        $PK_D3 = $this->input->post('biaya_bangunan_D3');
        $CS = $this->input->post('biaya_CS');
        $CS_D3 = $this->input->post('biaya_CS_D3');
        $kmhs = $this->input->post('biaya_kmhs');
        // $kmhs_D3        = $this->input->post('biaya_kmhs_D3');

        $table = 'biaya_angkatan';
        $dataInsert = [
            'angkatan' => $tahun_angkatan,
            'PK' => $PK,
            'PK_D3' => $PK_D3,
            'kmhs' => $kmhs,
            'CS' => $CS,
            'CS_D3' => $CS_D3,
            // 'potongan_CS' => 0
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
        $nm_jp = $this->input->post('nm_jp');
        $biaya = $this->input->post('biaya');
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
        $id = $this->input->post('id_biaya');
        $tahun_angkatan = $this->input->post('edit_tahun_angkatan');
        $PK = $this->input->post('edit_biaya_bangunan');
        $PK_D3 = $this->input->post('edit_biaya_bangunan_D3');
        $CS = $this->input->post('edit_biaya_CS');
        $CS_D3 = $this->input->post('edit_biaya_CS_D3');
        $kmhs = $this->input->post('edit_biaya_kmhs');
        // $potongan_CS    = $this->input->post('edit_biaya_potongan_cs');
        $dataUpdate = [
            'angkatan' => $tahun_angkatan,
            'PK' => $PK,
            'PK_D3' => $PK_D3,
            'kmhs' => $kmhs,
            'CS' => $CS,
            'CS_D3' => $CS_D3
            // 'potongan_CS'   => $potongan_CS
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

    public function UpdatePotonganBiayaCS()
    {
        $id = 1;
        $dataUpdate = [
            'potongan_C1' => $this->input->post('edit_pot_c1'),
            'potongan_C2' => $this->input->post('edit_pot_c2'),
            'potongan_C3' => $this->input->post('edit_pot_c3')
        ];
        $update = $this->masterdata->updatePotonganBiayaCS($id, $dataUpdate);
        if (!$update) {
            // error
            $this->session->set_flashdata('error', 'Gagal edit data!');
            redirect('masterdata/BiayaSpp');
        } else {
            $this->session->set_flashdata('success', 'Sukses edit data!');
            redirect('masterdata/BiayaSpp');
        }
    }

    public function UpdateBiayaLainnya()
    {
        // var_dump($this->input->post());
        // die;
        $id = $this->input->post('edit_id_jp');
        $nm_jp = $this->input->post('edit_nm_jp');
        $biaya = $this->input->post('edit_biaya');
        $potongan = $this->input->post('edit_potongan_biaya');
        $dataUpdateBiaya = [
            'biaya' => $biaya,
            'potongan_biaya' => $potongan
        ];
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
        $id = $this->input->post('hapus_id_biaya');
        $table = 'biaya_angkatan';
        $where = [
            'id_biaya' => $id
        ];
        $deleted = $this->masterdata->deleteData($table, $where);
        if (!$deleted) {
            // error
            $this->session->set_flashdata('error', 'Gagal hapus data!');
            redirect('masterdata/BiayaSPP');
        } else {
            $this->session->set_flashdata('success', 'Data berhasil di hapus!');
            redirect('masterdata/BiayaSPP');
        }
    }

    public function deleteDataPembayaranLain()
    {
        $id = $this->input->post('hapus_id_biaya');
        $where = [
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