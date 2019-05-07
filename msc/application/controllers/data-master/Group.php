<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->title = $this->common_lib->getTitle();
	}

	public function index()
	{
		$menu = array(
			"title" => $this->title,
			"btnHref" => base_url()."data-master/group/input-group",
			"btnBg" => "success",
			"btnFa" => "keyboard",
			"btnText" => "Tambah Data"
		);
		$card['title'] = "Group <span>> List Group</span>";
		$this->load->view('common/menu', $menu);
		$this->load->view('common/card', $card);
		$this->load->view('data-master/group/list_group');
		$this->load->view('common/slash-card');
		$this->load->view('common/footer');
	}

	public function input_group()
	{
		$menu = array(
			"title" => $this->title,
			"btnHref" => base_url()."data-master/group",
			"btnBg" => "primary","btnFa" => "keyboard",
			"btnText" => "Lihat Data"
		);
		$card['title'] = "Group <span>> Input Group</span>";
		$this->load->view('common/menu', $menu);
		$this->load->view('common/card', $card);
		$this->load->view('data-master/group/input_group');
		$this->load->view('common/slash-card');
		$this->load->view('common/footer');
	}

}