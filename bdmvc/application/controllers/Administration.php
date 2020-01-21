<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administration extends CI_Controller {

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
    $this->load->view('admin/credits', $data);
    $this->load->view('template/footer',$data);
	}

	public function creditsEtDettes() {
		$data = array();

		$this->load->model('administration_model');

		$data['sommaire'] = $this->administration_model->getSommaire();

		$this->load->view('template/header', $data);
        $this->load->view('admin/dettes', $data);
        $this->load->view('template/footer',$data);
	}

	public function deleteCreditOuDette($idCredit){

		$this->load->model('administration_model');

		$this->administration_model->deleteCreditOuDette($idCredit);

		redirect('/administration/creditsEtDettes','refresh');
	}

	public function searchIndividusCredit(){
		$data = array();

		$this->load->model('individus_model');

		$compte = $_POST['compte'];
		$prenomJoueur = $_POST['prenomJoueur'];
		$nomJoueur = $_POST['nomJoueur'];

		$data['individus'] = $this->individus_model->quickInscription($compte, $prenomJoueur, $nomJoueur);

		$this->load->model('old_inscriptions_model');
		$data['activites'] = $this->old_inscriptions_model->getActivites();

		$this->load->view('ajax/displayAdminCreditsResult',$data);
	}

	public function searchIndividusDette(){
		$data = array();

		$this->load->model('individus_model');

		$compte = $_POST['compte'];
		$prenomJoueur = $_POST['prenomJoueur'];
		$nomJoueur = $_POST['nomJoueur'];

		$data['individus'] = $this->individus_model->quickInscription($compte, $prenomJoueur, $nomJoueur);

		$this->load->view('ajax/displayAdminDettesResult',$data);
	}

	public function addCredit(){
		$montant = $_POST['montantCredit'];
		$idIndividu = $_POST['idIndividu'];
		$raison = $_POST['raisonCredit'];
		$commentaires = $_POST['commentairesCredit'];

		$this->load->model('administration_model');
		$this->administration_model->addCreditOrDebt($idIndividu, $raison, $montant, $commentaires);
		
	}

	public function addDette(){
		$montant = -$_POST['montantDette'];
		$idIndividu = $_POST['idIndividu'];
		$raison = $_POST['raisonDette'];
		$commentaires = $_POST['commentairesDette'];

		$this->load->model('administration_model');
		$this->administration_model->addCreditOrDebt($idIndividu, $raison, $montant, $commentaires);

	}

	public function removeCreditOrDebt(){
		$idSomme = $_POST['idSomme'];

		$this->load->model('administration_model');
		$this->administration_model->removeCreditOrDebt($idSomme);
	}

	public function getCreditsAndDebts($idIndiv){
		$this->load->model('administration_model');
		$data['CreditsAndDebts'] = $this->administration_model->getCreditsAndDebts($idIndiv);

		$this->load->view('template/header', $data);
        $this->load->view('admin/credits-and-debts', $data);
        $this->load->view('template/footer',$data);
	}

	public function avertissements(){
		$data = array();

		$this->load->view('template/header', $data);
        $this->load->view('admin/avertissements', $data);
        $this->load->view('template/footer',$data);
	}

	public function searchIndividusAvertissements(){
		$data = array();

		$this->load->model('individus_model');

		$compte = $_POST['compte'];
		$prenomJoueur = $_POST['prenomJoueur'];
		$nomJoueur = $_POST['nomJoueur'];

		$data['individus'] = $this->individus_model->quickInscription($compte, $prenomJoueur, $nomJoueur);

		$this->load->view('admin/displayAvertissementSearch',$data);

	}

	public function getavertissements($idIndiv){
		$this->load->model('administration_model');
		$data['avertissements'] = $this->administration_model->getavertissements($idIndiv);

		$this->load->view('template/header', $data);
        $this->load->view('admin/sommaireAvertissements', $data);
        $this->load->view('template/footer',$data);
	}

	public function acces(){
		$data = array();

		$this->load->view('template/header', $data);
        $this->load->view('admin/acces', $data);
        $this->load->view('template/footer',$data);
	}

	public function searchIndividusAcces(){
		$data = array();

		$this->load->model('individus_model');

		$compte = $_POST['compte'];
		$prenomJoueur = $_POST['prenomJoueur'];
		$nomJoueur = $_POST['nomJoueur'];
		//$acceslvl = $_POST['acceslvl'];

		$data['individus'] = $this->individus_model->quickInscription($compte, $prenomJoueur, $nomJoueur);

		$this->load->view('admin/displayAccesSearch',$data);

	}

	public function updateNiveauAcces(){
		$this->load->model('administration_model');

		$idIndividu = $_POST['idIndividu'];
		$newNiveauAcces = $_POST['newNiveauAcces'];

		$this->administration_model->updateNiveauAcces($idIndividu, $newNiveauAcces);

		$this->load->view('template/header', $data);
    $this->load->view('admin/acces', $data);
    $this->load->view('template/footer',$data);
	}

}

?>