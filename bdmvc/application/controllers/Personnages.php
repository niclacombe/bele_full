<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personnages extends CI_Controller {
	public function __construct()	{
		parent::__construct();
		$this->load->model('personnages_model');
	}

	public function index()	{
		$data = array();

		$data['races'] = $this->personnages_model->getRaces();
		$data['religions'] = $this->personnages_model->getReligions();
		$data['classes'] = $this->personnages_model->getClasses();

		$this->load->view('template/header', $data);
        $this->load->view('personnages/search', $data);
        $this->load->view('template/footer',$data);
	}

	public function searchPerso(){
		$data = array();

		$data['races'] = $this->personnages_model->getRaces();
		$data['religions'] = $this->personnages_model->getReligions();
		$data['classes'] = $this->personnages_model->getClasses();

		$data['results'] = $this->personnages_model->getResults();

		$this->load->view('template/header', $data);
        $this->load->view('personnages/search', $data);
        $this->load->view('template/footer',$data);
	}

	public function editPersonnage($idPerso, $idIndiv){
		$data = array();

		$data['races'] = $this->personnages_model->getRaces();
		$data['religions'] = $this->personnages_model->getReligions();
		$data['classes'] = $this->personnages_model->getClasses();
		$data['subClasses'] = $this->personnages_model->getSubClasses();

		$data['infoIndiv'] = $this->personnages_model->getIndivInfo($idIndiv);
		$data['infoPerso'] = $this->personnages_model->getPersoInfo($idPerso);

		$data['XP'] = $this->personnages_model->getXP($idPerso);
		$data['PV'] = $this->personnages_model->getPV($idPerso);

		$data['travail'] = $this->personnages_model->getTravail($idPerso);

		$data['titres'] = $this->personnages_model->getTitres($idPerso);

		$data['allTitres'] = $this->personnages_model->getAllTitres($idPerso);

		$data['skills'] = $this->personnages_model->getSkills($idPerso);

		$data['spells'] = $this->personnages_model->getSpells($idPerso);

		$this->load->view('template/header', $data);
        $this->load->view('personnages/singleJoueur', $data);
        $this->load->view('template/footer',$data);

	}

	public function editReligion($idPerso, $idIndiv){

		$this->personnages_model->editReligion($idPerso);

		redirect('/personnages/editPersonnage/' .$idPerso . '/' . $idIndiv ,'refresh');

	}

	public function editClasse($idPerso, $idIndiv){
		$this->personnages_model->editClasse($idPerso);
		redirect('/personnages/editPersonnage/' .$idPerso . '/' . $idIndiv ,'refresh');
	}

	public function editSkills($idPerso, $idIndiv){
		$data['infoIndiv'] = $this->personnages_model->getIndivInfo($idIndiv);
		$data['infoPerso'] = $this->personnages_model->getPersoInfo($idPerso);

		$data['XP'] = $this->personnages_model->getXP($idPerso);

		$data['skills'] = $this->personnages_model->getSkills($idPerso);
		$data['regSkills'] = $this->personnages_model->getRegSkills($idPerso);

		$data['specSkills'] = $this->personnages_model->getSpecSkills();

		$this->load->view('template/header', $data);
        $this->load->view('personnages/editskills', $data);
        $this->load->view('template/footer',$data);
	}

	public function paySkill($idPerso, $idIndiv){
		$this->personnages_model->paySkill($idPerso);

		redirect('/personnages/editSkills/' .$idPerso . '/' . $idIndiv ,'refresh');

	}

	public function giveSkill($idPerso, $idIndiv){
		$this->personnages_model->giveSkill($idPerso);

		redirect('/personnages/editSkills/' .$idPerso . '/' . $idIndiv ,'refresh');
	}

	public function deleteSkill($idPerso, $idIndiv, $idSkill, $codeEtat){
		$this->personnages_model->deleteSkills($idSkill, $codeEtat);

		redirect('/personnages/editSkills/' .$idPerso . '/' . $idIndiv ,'refresh');
	}

	public function declareMort($idPerso, $idIndiv){
		$this->personnages_model->declareMort($idPerso);

		redirect('/personnages/editPersonnage/' .$idPerso . '/' . $idIndiv ,'refresh');
	}

	public function deleteTravail($idPerso, $idIndiv){
		$this->personnages_model->deleteTravail($idPerso);

		redirect('/personnages/editPersonnage/' .$idPerso . '/' . $idIndiv ,'refresh');
	}

	public function levelUP($idPerso, $idIndiv, $currentLvl){
		$this->personnages_model->levelUP($idPerso, $idIndiv, $currentLvl);

		redirect('/personnages/editPersonnage/' .$idPerso . '/' . $idIndiv ,'refresh');
	}

	public function addTitre($idPerso, $idIndiv){

		$this->personnages_model->addTitre($idPerso, $idIndiv);
		redirect('/personnages/editPersonnage/' .$idPerso . '/' . $idIndiv ,'refresh');

	}

	public function removeTitre($idPerso, $idIndiv, $idTitre){

		$this->personnages_model->removeTitre($idTitre);
		redirect('/personnages/editPersonnage/' .$idPerso . '/' . $idIndiv ,'refresh');

	}

	public function editSpells($idPerso, $idIndiv){
		$data['infoIndiv'] = $this->personnages_model->getIndivInfo($idIndiv);
		$data['infoPerso'] = $this->personnages_model->getPersoInfo($idPerso);

		$data['allMetiers'] = $this->personnages_model->getAllMetiers();
		$data['allSpells'] = $this->personnages_model->getAllSpells();
		$data['allRecettes'] = $this->personnages_model->getAllRecettes();

		$data['spells'] = $this->personnages_model->getSpells($idPerso);

		$this->load->view('template/header', $data);
    $this->load->view('personnages/editspells', $data);
    $this->load->view('template/footer',$data);
	}

	public function updateSpells($idPerso, $idIndiv){
		$spells = [];

		foreach ($_POST as $key => $value) {
			if ($value != "") {
				$spells[$key] = $value;
			}
		}

		$this->personnages_model->updateSpells($idPerso, $idIndiv, $spells);

		redirect('/personnages/editSpells/' .$idPerso . '/' . $idIndiv ,'refresh');
	}

	public function editNoteRapide($idPerso, $idIndiv){

		$data = $this->input->post('noteRapide');

		$this->personnages_model->editNoteRapide($idPerso, $idIndiv, $data);

		redirect('/personnages/editPersonnage/' .$idPerso . '/' . $idIndiv ,'refresh');
	}

	public function changeState($idPerso, $idIndiv){

		if($_POST['newState']){
			$newState = $_POST['newState'];
			$this->personnages_model->changeState($idPerso, $newState);
		}

		redirect('/personnages/editPersonnage/' .$idPerso . '/' . $idIndiv ,'refresh');
	}


}

/* End of file Personnages.php */
/* Location: ./application/controllers/Personnages.php */ 
?>