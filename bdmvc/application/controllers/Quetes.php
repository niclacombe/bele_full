<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quetes extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('quetes_model');
	}


	public function viewQuests(){

		$idUser = null;
		if($_SESSION['infoUser']->NiveauAcces < 5){
			$idUser = $_SESSION['infoUser']->Id;
		}

		$data['quetes'] = $this->quetes_model->viewQuests($idUser);

		$this->load->view('template/header', $data);
		$this->load->view('quetes/view-quests', $data);
		$this->load->view('template/footer',$data);
	}

	

}

/* End of file Quetes.php */
/* Location: ./application/controllers/Quetes.php */ ?>