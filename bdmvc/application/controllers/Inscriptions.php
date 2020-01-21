<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inscriptions extends CI_Controller {

	public function __construct()	{
		parent::__construct();
		$this->load->model('inscriptions_model');
	}

	public function index($idActiv = null)	{
		$data['activites'] = $this->inscriptions_model->getActivites();

		$data['idActiv'] = $idActiv;

		$this->load->view('template/header', $data);
    $this->load->view('inscriptions/search', $data);
    $this->load->view('template/footer',$data);

	}

	public function searchIndiv($idActiv = null){
		$data['results'] = $this->inscriptions_model->searchIndiv();
		$data['activites'] = $this->inscriptions_model->getActivites();

		$data['idActiv'] = ( isset($_POST['idActiv'] ) )? $_POST['idActiv'] : $idActiv;

		$this->load->view('template/header', $data);
    $this->load->view('inscriptions/search', $data);
    $this->load->view('template/footer',$data);
	}

	public function editInscription($idIndiv, $idActiv){
		if($idActiv == 'null'){
			$idActiv = $this->input->post('idActiv');
		}
		$data['hasPaid'] = $this->inscriptions_model->hasPaid($idIndiv, $idActiv);
		$data['inscription'] = $this->inscriptions_model->isInscrit($idIndiv, $idActiv);

		$data['hasDebts'] = $this->inscriptions_model->hasDebts($idIndiv);

		$data['isLocation'] = $this->inscriptions_model->isLocation($idActiv);

		$data['personnages'] = $this->inscriptions_model->getPersonnages($idIndiv, $idActiv);

    $this->load->view('template/header', $data);
    $this->load->view('inscriptions/editInscription', $data);
    $this->load->view('template/footer',$data);

	}

	public function addInscription($idIndiv, $idActiv){
		$this->inscriptions_model->addInscription($idIndiv, $idActiv);
		$this->inscriptions_model->addXP($idIndiv, $idActiv);

		redirect('/inscriptions/editInscription/' .$idIndiv . '/' . $idActiv ,'refresh');
	}

	public function addPresence($idIndiv, $idActiv, $idPerso){
		$this->inscriptions_model->addPresence($idIndiv, $idActiv, $idPerso);
		

		redirect('/inscriptions/editInscription/' .$idIndiv . '/' . $idActiv ,'refresh');
	}

	public function addFreePresence($idIndiv, $idActiv){
		$this->inscriptions_model->addPresence($idIndiv, $idActiv);
		$this->inscriptions_model->removeActiviteGratuite($idIndiv);

		redirect('/inscriptions/editInscription/' .$idIndiv . '/' . $idActiv ,'refresh');
	}

	public function searchInscriptions(){
		$data['activites'] = $this->inscriptions_model->getActivites();

		$this->load->view('template/header', $data);
    $this->load->view('inscriptions/searchInscriptions', $data);
    $this->load->view('template/footer',$data);
	}

	public function getInscriptions(){
		$idActiv = $this->input->post('idActiv');

		$results = $this->inscriptions_model->searchInscriptions($idActiv);

		if(count($results) != 0){
			$data['results'] = $this->inscriptions_model->searchInscriptions($idActiv);
		} else {
			$data['noResults'] = true;
		}

		$data['activites'] = $this->inscriptions_model->getActivites();

		$this->load->view('template/header', $data);
        $this->load->view('inscriptions/searchInscriptions', $data);
        $this->load->view('template/footer',$data);
	}

	public function deleteInscription($idActiv, $idIndiv, $idPerso){
		$this->inscriptions_model->deleteInscription($idActiv, $idIndiv, $idPerso);

		redirect('/inscriptions/searchInscriptions','refresh');
	}

	public function deleteAllInscriptions($idActiv){
		$inscriptions = $this->inscriptions_model->searchInscriptions($idActiv);

		foreach ($inscriptions as $inscription) {
			$this->inscriptions_model->deleteInscription($inscription->IdActivite,$inscription->IdIndividu,$inscription->IdPersonnage);
		}
		redirect('/inscriptions/searchInscriptions','refresh');
	}

	public function searchPresences(){
		$data['activites'] = $this->inscriptions_model->getActivites();

		$this->load->view('template/header', $data);
        $this->load->view('inscriptions/searchPresences', $data);
        $this->load->view('template/footer',$data);
	}

	public function getPresences(){
		$idActiv = $this->input->post('idActiv');
		$data['results'] = $this->inscriptions_model->getPresences($idActiv);

		$data['total'] = 0;

		foreach ($data['results'] as $result) {
			$data['total'] += floatval($result->Recu);
		}

		$data['activites'] = $this->inscriptions_model->getActivites();

		$this->load->view('template/header', $data);
        $this->load->view('inscriptions/searchPresences', $data);
        $this->load->view('template/footer',$data);
	}

	public function downloadPresencesList($idActiv){
			// output headers so that the file is downloaded rather than displayed
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=ListedePrésences.csv');

			// create a file pointer connected to the output stream
			$output = fopen('php://output', 'w');

			// output the column headings
			fputcsv($output, array('Nom du joueur', 'Recu ($)'));

			$vRows = $this->inscriptions_model->downloadPresencesList($idActiv);

			foreach ($vRows as $row) {
				fputcsv($output, $row);
			}

		}
}

/* End of file Inscriptions.php */
/* Location: ./application/controllers/Inscriptions.php */ ?>