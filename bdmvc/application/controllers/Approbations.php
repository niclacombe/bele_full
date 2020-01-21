<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approbations extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
		$data = array();

		$this->load->view('template/header', $data);
        $this->load->view('approbation', $data);
        $this->load->view('template/footer',$data);
	}

	public function approbationBG() {
		$data = array();

		$this->load->model('approbations_model');

		$data['approbationsBG'] = $this->approbations_model->getApprobationBGDemandes();

		$this->load->view('template/header', $data);
        $this->load->view('approbationBG', $data);
        $this->load->view('template/footer',$data);
	}

	public function getPersonnageHistoire(){
		$data = array();

		$id = $this->input->post('id');

		$this->load->model('approbations_model');
		$data['histoire'] = $this->approbations_model->getPersonnageHistoire($id);

		$this->load->model('individus_model');
		$data['individu'] = $this->individus_model->getSingleIndividu( $data['histoire']->IdIndividu );

		
		$this->load->view('ajax/displayHistoire', $data);
	}

	public function refusHistoire(){
		/*$this->load->library('email');*/

		$id = $this->input->post('idApprobation');
	    $emailContent = $this->input->post('emailContent');
	    $email = $this->input->post('email');

	    $this->load->model('approbations_model');
		$query = $this->approbations_model->updateREFDemande($id, $emailContent);

		header("Refresh:0; url=/");
	}

	public function acceptHistoire(){

		$id = $this->input->post('idApprobation');
	    $emailContent = $this->input->post('emailContent');
	    $email = $this->input->post('email');
	    $idPersonnage = $this->input->post('idPersonnage');
	    $idIndividu = $this->input->post('idIndividu');

	    $this->load->model('approbations_model');
		$query = $this->approbations_model->updateACCEPDemande($id, $email, $emailContent, $idPersonnage, $idIndividu);

		header("Refresh:0; url=page2.php");
	}

	public function plansDeCours() {
		$data = array();

		$this->load->view('template/header', $data);
        $this->load->view('approbations/plansdecours', $data);
        $this->load->view('template/footer',$data);
	}

	public function searchPersonnages() {
		$data = array();

		$this->load->model('old_personnages_model');

		$prenomJoueur = $_POST['prenomJoueur'];
		$nomJoueur = $_POST['nomJoueur'];

		$prenomPerso = $_POST['prenomPerso'];
		$nomPerso = $_POST['nomPerso'];

		$avantApres = $_POST['avantApres'];
		$ddn = $_POST['ddn'];

		$niveau = $_POST['niveau'];
		$niveauCtrl = $_POST['niveauCtrl'];

		$classe = $_POST['classe'];
		$race = $_POST['race'];
		$religion = $_POST['religion'];

		$data['searchResults'] = $this->old_personnages_model->getSearchResults($prenomJoueur, $nomJoueur, $avantApres, $ddn, $prenomPerso, $nomPerso, $classe, $race, $religion, $niveau, $niveauCtrl);

		$this->load->view('approbations/displaySearchResults', $data);        
	}

	public function getPersonnageCours($idPersonnage){
		$data = array();

		$this->load->model('approbations_model');		
		$data['personnageData'] = $this->approbations_model->getPersonnageCours($idPersonnage);

		$this->load->model('approbations_model');	
		$data['listeCompetences'] = $this->approbations_model->getSkillsList();

		$this->load->view('template/header', $data);
        $this->load->view('approbations/listeCours', $data);
        $this->load->view('template/footer',$data);
	}

	public function activateSkill(){
		$idCours = $_POST['idCours'];

		$this->load->model('approbations_model');
		$this->approbations_model->activateSkill($idCours);
	}

	public function desactivateSkill(){
		$idCours = $_POST['idCours'];

		$this->load->model('approbations_model');
		$this->approbations_model->desactivateSkill($idCours);
	}

	public function addSkill(){
		$codeCours = $_POST['codeCours'];
		$idPersonnage = $_POST['idPersonnage'];

		$this->load->model('approbations_model');
		$this->approbations_model->addSkill($codeCours, $idPersonnage);
	}

	/*******/

	public function approbRaces(){
		$data = array();

		$this->load->model('approbations_model');
		$data['demandes'] = $this->approbations_model->approbRaces();

		$this->load->view('template/header', $data);
        $this->load->view('approbations/approbRaces', $data);
        $this->load->view('template/footer',$data);
	}

	public function loadDemande(){
		$data = array();

		$this->load->model('approbations_model');
		$data['demandes'] = $this->approbations_model->approbRaces();
		$data['result'] = $this->approbations_model->loadDemande();

		$this->load->view('template/header', $data);
        $this->load->view('approbations/approbRaces', $data);
        $this->load->view('template/footer',$data);
	}

	public function acceptRace($idAppro){
		$this->load->model('approbations_model');
		$this->approbations_model->acceptRace($idAppro);

		redirect('approbations/approbRaces','refresh');
	}

	public function refusRace($idAppro){
		$this->load->model('approbations_model');
		$this->approbations_model->refusRace($idAppro);

		redirect('approbations/approbRaces','refresh');
	}
}

?>