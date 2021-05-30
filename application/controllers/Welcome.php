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
		$kondisi = [
			'is_active' => 1
		];
		$data['menu'] = $this->menu->getMenu($kondisi)->result_array();
		$data['submenu'] = $this->menu->getSubMenu($kondisi)->result_array();
		// var_dump($data['menu']);
		// die;
		$data['content'] = 'page_test';
		$data['title'] = 'SiskeuNEW';
		$this->load->view('template', $data);
		// $this->load->view('app');
	}
}
