<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groupes extends CI_Controller {

	public function __construct()	{
		parent::__construct();
		$this->load->model('groupes_model');
	}

	public function index()	{
		$data = array();

		$data['groupes'] = $this->groupes_model->getGroupes();

		$this->load->view('template/header', $data);
        $this->load->view('groupes/index', $data);
        $this->load->view('template/footer',$data);
	}

	public function singleGroupe($idGroupe){
		$data['groupeData'] = $this->groupes_model->singleGroupe($idGroupe);
		$data['membres'] = $this->groupes_model->getMembres($idGroupe);
		$data['responsables'] = $this->groupes_model->getResponsables($idGroupe);
		$data['objectifs'] = $this->groupes_model->getObjectifs($idGroupe);
		$data['profils'] = $this->groupes_model->getProfils($idGroupe);
		$data['pilotProfils'] = $this->groupes_model->getpilotProfils();
		$data['specs'] = $this->groupes_model->getSpecs($idGroupe);
		$data['avantages'] = $this->groupes_model->getAllowedAvantages($idGroupe);

		$this->load->view('template/header', $data);
        $this->load->view('groupes/singlegroupe', $data);
        $this->load->view('template/footer',$data);
	}

	public function officialGroupe($idGroupe){
		$this->groupes_model->officialGroupe($idGroupe);

		redirect('groupes/singleGroupe/' .$idGroupe,'refresh');
	}

	public function removeFromGroupe($idPerso, $idGroupe){
		$this->groupes_model->removeFromGroupe($idPerso, $idGroupe);

		redirect('groupes/singleGroupe/' .$idGroupe,'refresh');
	}

	public function demoteResponsable($idIndiv, $idGroupe){
		$this->groupes_model->demoteResponsable($idIndiv, $idGroupe);

		redirect('groupes/singleGroupe/' .$idGroupe,'refresh');
	}

	public function promoteResponsable($idIndiv, $idGroupe){
		$this->groupes_model->promoteResponsable($idIndiv, $idGroupe);

		redirect('groupes/singleGroupe/' .$idGroupe,'refresh');
	}

	public function editInfluence($idGroupe){
		$data['groupeData'] = $this->groupes_model->singleGroupe($idGroupe);
		$data['influences'] = $this->groupes_model->getInfluence($idGroupe);
		$data['activite'] = $this->groupes_model->getLastActivity();

		$this->load->view('template/header', $data);
        $this->load->view('groupes/editinfluence', $data);
        $this->load->view('template/footer',$data);
	}

	public function deleteInfluence($idInfluence, $idGroupe){
		$this->groupes_model->deleteInfluence($idInfluence);

		redirect('groupes/editInfluence/' .$idGroupe,'refresh');
	}

	public function addInfluence($idGroupe){
		$this->groupes_model->addInfluence($idGroupe);

		redirect('groupes/editInfluence/' .$idGroupe,'refresh');
	}

	public function viewActions(){
		$data = array();

		$data['demandes'] = $this->groupes_model->viewActions();

		$this->load->view('template/header', $data);
        $this->load->view('groupes/viewactions', $data);
        $this->load->view('template/footer',$data);
	}

	public function acceptAction($idGroupe,$idAction){

		$this->groupes_model->acceptAction($idGroupe,$idAction);

		redirect('groupes/viewActions','refresh');
	}

	public function refusAction($idGroupe,$idAction){

		$this->groupes_model->refusAction($idGroupe,$idAction);

		redirect('groupes/viewActions','refresh');
	}

	public function displayRefusModal($idGroupe,$idAction){
		$data = array('idGroupe' => $idGroupe, 'idAction' => $idAction);
		$this->load->view('groupes/displayRefusModal', $data);
	}

	public function removeProfil($idGroupe, $codeProfil){
		$this->groupes_model->removeProfil($idGroupe, $codeProfil);

		redirect('groupes/singleGroupe/' .$idGroupe,'refresh');
	}

	public function addProfil($idGroupe){
		$this->groupes_model->addProfil($idGroupe);

		redirect('groupes/singleGroupe/' .$idGroupe,'refresh');
	}

	public function addSpec($idGroupe){
		$this->groupes_model->addSpec($idGroupe);

		redirect('groupes/singleGroupe/' .$idGroupe,'refresh');
	}
}

/* End of file controllername.php */
/* Location: ./application/controllers/controllername.php */ ?>