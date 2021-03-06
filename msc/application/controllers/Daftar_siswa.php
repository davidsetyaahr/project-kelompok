<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar_siswa extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->title = $this->common_lib->getTitle();
		if($this->session->userdata("status") != "login")
		{
			redirect(base_url()."login");
		}
		else if($this->session->userdata("hak_akses") == "Owner" || $this->session->userdata("hak_akses") == "Siswa" || $this->session->userdata("hak_akses") == "Tentor" || $this->session->userdata("hak_akses") == "Orang Tua" )
		{
			show_404();
		}
		else if($this->session->userdata("hak_akses") == "Owner" || $this->session->userdata("hak_akses") == "Siswa" || $this->session->userdata("hak_akses") == "Tentor" || $this->session->userdata("hak_akses") == "Orang Tua" )
		{
			show_404();
		}
	}
	
	public function index()
	{
		$cek = $this->common->getData("kode_siswa", "siswa", "", "", "");
		if(count($cek)==0){
			$kode = "MSC001";
		}
		else{
			$getKode = $this->common->getData("kode_siswa", ["siswa",1], "", "", ["kode_siswa","desc"]);
			$getInt = (int)substr($getKode[0]['kode_siswa'],3,3) + 1;
			if(strlen($getInt)==1){
				$nol = "00";
			}
			else if(strlen($getInt)==2){
				$nol = "0";
			}
			else if(strlen($getInt)==3){
				$nol = "";
			}
			$kode = "MSC".$nol.$getInt;
		}
		$data["siswa"] = $kode;		
        $menu = array(
            "title" => $this->title,
		);
		$card['title'] = "Pendafatran <span>> Daftar</span>";
        $data["data"] = $this->common->getData("*", "tentor", "", "", "");
		$data['group'] = $this->common->getData("*","group_siswa","","","");
		$data['biaya'] = $this->common->getData("pendaftaran, cicilan","biaya","","","");
		$this->load->view('common/menu', $menu);
		$this->load->view('common/card', $card);
		$this->load->view('daftar_siswa/tambah-daftar-siswa', $data);
		$this->load->view('common/slash-card');
        $this->load->view('common/footer');
        
	}

	public function tambah_daftar_siswa()
	{	
		$menu = array(
			"title" => $this->title,
			"btnHref" => base_url()."",
			"btnBg" => "primary","btnFa" => "keyboard",
		);
		$card['title'] = "Pendaftaran Siswa<span>> Input Pendaftaran Siswa</span>";
		$this->load->view('common/menu', $menu);
        $this->load->view('common/card', $card);
		$this->load->view('daftar_siswa/tambah-daftar-siswa');
		$this->load->view('common/slash-card');
        $this->load->view('common/footer');
    }
    public function insert_daftar	()
    {
    	echo "<pre>";
    	print_r($_POST);

    	$ortu = array(
    		"nama_ortu" => $_POST['nama_ortu'],
    		"no_hp" => $_POST['no_hp'],
    		"foto" => "default_ortu.png",
    	);
    	$this->common->insert("ortu",$ortu);
    	$getId = $this->common->getData("id_ortu",["ortu",1],"","",["id_ortu","desc"]);
    	$getSpp = $this->common->getData("spp","biaya","","","");
    	$siswa = array(
    		"kode_siswa" => $_POST['kode_siswa'],
    		"nama_siswa" => $_POST['nama_siswa'],
    		"tgl_lahir" => $_POST['tgl_lahir'],
    		"jk" => $_POST['jk'],
    		"alamat" => $_POST['alamat'],
    		"foto" => "default_siswa.png",
    		"no_hp" => $_POST['no_hpsiswa'],
    		"cicilan" => $_POST ['cicilan'],
    		"kelas" => $_POST ['kelas'],
    		"kode_group" => $_POST['kode_group'],
    		"id_ortu" => $getId[0]['id_ortu'],
			"tgl_daftar" => $_POST['tgl_daftar'],
			"awal_spp" => $getSpp[0]["spp"]
		);
		$this->common->insert("siswa",$siswa);
		$pendaftaran = array(
			"invoice" => date("dmYHi"),
			"kode_siswa" => $_POST["kode_siswa"],
			"nominal" => $_POST["nominal_pendaftaran"],
			"tgl_bayar" => date("Y-m-d")
		);
		$this->common->insert("biaya_pendaftaran", $pendaftaran);
		$getIdSiswa = $this->common->getData("id_siswa", ["siswa", 1], "", "", ["id_siswa", "desc"]);
		$valLapKeuangan = array(
			"id_parent" => $getIdSiswa[0]["id_siswa"],
			"tanggal" => date("Y-m-d"),
			"nominal" => $_POST["nominal_pendaftaran"],
			"tipe" => "Pendaftaran"
		);
		$this->common->insert("laporan_keuangan", $valLapKeuangan);
		$passwordSiswa = str_replace(" ","", $_POST["nama_siswa"]);
		$valUser = array(
			"username" => strtolower($this->input->post("kode_siswa")),
			"password" => password_hash(strtolower($passwordSiswa), PASSWORD_DEFAULT),
			"nama_pengguna" => $_POST["nama_siswa"],
			"hak_akses" => "Siswa",
			"id_child" => $_POST["kode_siswa"]	
		);
		$this->common->insert("user", $valUser);
		$passwordOrtu = str_replace(" ","", $_POST['nama_ortu']);
		$usernameOrtu = str_replace(" ","_", $_POST['nama_ortu']);
		$valUserOrtu = array(
			"username" => strtolower($usernameOrtu),
			"password" => password_hash(strtolower($passwordOrtu), PASSWORD_DEFAULT),
			"nama_pengguna" => $_POST['nama_ortu'],
			"hak_akses" => "Orang Tua",
			"id_child" => $getId[0]['id_ortu']	
		);
		$this->common->insert("user", $valUserOrtu);
		$this->session->set_flashdata("success", "Berhasil Menambahkan Data!!!");
		redirect(base_url()."data_siswa");
	}
	
}