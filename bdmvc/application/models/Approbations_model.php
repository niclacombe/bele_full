<?php
class Approbations_model extends CI_Model{

	public function __construct(){
		$this->load->database('db_perso');
	}


	public function getApprobationBGDemandes(){

		$this->db->db_select('db_perso');

		$this->db->select('personnages.Prenom, personnages.Nom, personnages.Id, approbations.DateDemande');
		$this->db->from('personnages','approbations');
		$this->db->join('approbations', 'approbations.IdPersonnage = personnages.Id');
		$this->db->where('approbations.CodeEtat','DEM');
		$this->db->where('approbations.Objet','Histoire');
		$this->db->order_by('approbations.DateDemande','ASC');
		

		$query = $this->db->get();

		return $query->result_array();
	}

	public function getPersonnageHistoire($id){
		$this->db->db_select('db_perso');

		$this->db->select('personnages.Histoire, personnages.IdIndividu, approbations.Id');
		$this->db->from('personnages', 'approbations');
		$this->db->join('approbations', 'approbations.IdPersonnage =' .$id);
		$this->db->where('personnages.Id',$id);
		$this->db->order_by('DateDemande','DESC');

		$query = $this->db->get();

		return $query->row();
	}

	public function updateREFDemande($id, $emailContent){
		$this->db->db_select('db_perso');

		$updateData = array(
			'CodeEtat' 			=> 'REFUS',
			'DateApprobation' 	=> date('Y-m-d H:i:s', time()),
			'Commentaires'		=> $_POST['emailContent'],
			);

		$this->db->where('Id',$id);
		$this->db->update('approbations',$updateData);

	}

	public function updateACCEPDemande($id, $email, $emailContent, $idPersonnage, $idIndividu){
		$this->db->db_select('db_perso');

		$updateData = array(
			'CodeEtat' 			=> 'ACCEP',
			'DateApprobation' 	=> date('Y-m-d H:i:s', time()),
			'Commentaires'		=> $_POST['emailContent'],
			);

		$this->db->where('Id',$id);
		$this->db->update('approbations',$updateData);

		// Valide et ajoute 5XP si premier BG accepté.

		$this->db->db_select('db_perso');

		$this->db->where('IdPersonnage', $idPersonnage);
		$this->db->where('Raison', 'Accep Histoire');

		$query = $this->db->get('experience');

		$queryResult = $query->row();

		if( $queryResult->Niveau < 2 ){

			$this->db->db_select('db_perso');

			$insertData = array(
				'IdPersonnage'		=> $idPersonnage,
				'Raison' 			=> 'Accep Histoire',
				'XP' 				=> 5,
				'DateInscription' 	=> date('Y-m-d H:i:s', time()),
				'Commentaires'		=> $emailContent,
				);

			$this->db->where('Id',$idPersonnage);
			$this->db->insert('experience',$insertData);
		}
	}

	public function getPersonnageCours($idPersonnage){

		$this->db->db_select('db_perso');

		$cours = array();

		$query = "SELECT maitres.*, pilotSkill.Nom as 'NomSkill'
					FROM db_perso.maitres maitres
					LEFT JOIN db_perso.personnages perso ON maitres.IdPersonnage = perso.Id
					LEFT JOIN db_pilot.competences_regulieres pilotSkill ON maitres.CodeCompetence = pilotSkill.Code
					WHERE maitres.IdPersonnage = " .$idPersonnage .";";

		$query = $this->db->query($query);

		$cours['listeCours'] = $query->result_array();

		$query = "SELECT perso.Prenom, perso.Nom, perso.Id
					FROM db_perso.personnages perso
					WHERE perso.Id = " .$idPersonnage .";";

		$query = $this->db->query($query);

		$cours['infoPerso'] = $query->result_array();

		return $cours;
	}

	public function activateSkill($idCours){
		$this->db->db_select('db_perso');

		$updateData = array(
			'CodeEtat' => 'ACTIF'
		);
		$this->db->where('Id',$idCours);

		$this->db->update('maitres',$updateData);
	}

	public function desactivateSkill($idCours){
		$this->db->db_select('db_perso');

		$updateData = array(
			'CodeEtat' => 'INACT'
		);
		$this->db->where('Id',$idCours);

		$this->db->update('maitres',$updateData);
	}

	public function getSkillsList(){
		$this->db->db_select('db_pilot');
		$query = "SELECT reg.Code, reg.Nom FROM db_pilot.competences_regulieres reg ORDER BY Nom";

		$listSkills = $this->db->query($query);
		$listSkills = $listSkills->result_array();

		return $listSkills;
	}

	public function addSkill($codeCours, $idPersonnage){
		$this->db->db_select('db_perso');

		$insertData = array(
			'IdPersonnage' 		=> $idPersonnage,
			'CodeCompetence' 	=> $codeCours,
			'CodeEtat' 			=> 'ACTIF',
			'DateCreation' 		=> date('Y-m-d H:i:s', time())
		);

		$this->db->insert('maitres',$insertData);
	}

	/*******/

	public function approbRaces(){
		$this->db->db_select('db_perso');

		$this->db->select('appro.*,perso.Prenom, perso.Nom, pilot.Nom as NomRace');
		$this->db->from('db_perso.approbations appro');
		$this->db->join('db_perso.personnages perso', 'perso.Id = appro.IdPersonnage', 'left');
		$this->db->join('db_pilot.races pilot', 'pilot.Code = perso.CodeRace', 'left');
		$this->db->where('appro.Objet', 'Race');
		$this->db->where('appro.CodeEtat', 'DEM');

		$query = $this->db->get();
		return $query->result();
	}

	public function loadDemande(){
		$idAppro = $this->input->post('idAppro');

		$this->db->db_select('db_perso');

		$this->db->select('perso.Histoire, appro.Id, appro.IdPersonnage');
		$this->db->from('db_perso.approbations appro');
		$this->db->join('db_perso.personnages perso', 'perso.Id = appro.IdPersonnage', 'left');
		$this->db->where('appro.Id', $idAppro);
		$query = $this->db->get();

		return $query->row();
	}

	public function acceptRace($idAppro){
		$this->db->db_select('db_perso');

		$commentaires = $this->input->post('refusCommentaire');

		if ($commentaires == "") {
			$commentaires = null;
		}

		$data = array(
			'CodeEtat' => 'ACCEP',
			'DateApprobation' => date('Y-m-d H:i:s', time()),
			'Commentaires' => $commentaires
		);

		$this->db->where('Id', $idAppro);
		$this->db->update('approbations', $data);
	}

	public function refusRace($idAppro){
		$this->db->db_select('db_perso');

		$data = array(
			'CodeEtat' => 'REFUS',
			'DateApprobation' => date('Y-m-d H:i:s', time()),
			'Commentaires' => $this->input->post('refusCommentaire')
		);

		$this->db->where('Id', $idAppro);
		$this->db->update('approbations', $data);
	}

	
}