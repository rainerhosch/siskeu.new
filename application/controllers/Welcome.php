<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_menu', 'menu');
	}
	public function index()
	{
		/*
		SELECT * FROM transaksi t 
		LEFT JOIN transaksi_detail td ON td.id_transaksi=t.id_transaksi 
		LEFT JOIN master_jenis_pembayaran mjp ON mjp.id_jenis_pembayaran=td.id_jenis_pembayaran 
		WHERE t.uang_masuk=0 AND td.jml_bayar > 200000 and td.id_jenis_pembayaran=9;
		*/

		// $this->db->select('t.id_transaksi');
		// $this->db->from('transaksi t');
		// $this->db->join('transaksi_detail td', 'td.id_transaksi=t.id_transaksi');
		// $this->db->join('master_jenis_pembayaran mjp', 'mjp.id_jenis_pembayaran=td.id_jenis_pembayaran');
		// $this->db->where([
		// 	't.uang_masuk' => 0,
		// 	'td.id_jenis_pembayaran' => 16
		// ]);
		// $data = $this->db->get()->result_array();
		// $dataUpdate =  [
		// 	'uang_masuk' => 1
		// ];
		// for ($i = 0; $i < count($data); $i++) {
		// 	$id_trx = $data[$i]['id_transaksi'];
		// 	$this->db->where('id_transaksi', $id_trx);
		// 	$this->db->update('transaksi', $dataUpdate);
		// }
		echo 'success';
	}
}
