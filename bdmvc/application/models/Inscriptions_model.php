<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inscriptions_model extends CI_Model {
	public function __construct()	{
		parent::__construct();
		$this->db->db_select('db_activ');
	}

	public function getActivites(){
		$this->db->db_select('db_activ');

		$this->db->where('DateFin <', date('Y-m-d H:i:s', strtotime('+10 day') ) );
		$this->db->order_by('DateFin', 'desc');

		$query = $this->db->get('activites', 15);
		return $query->result();
	}

	public function searchIndiv(){
		$this->db->db_select('db_indiv');

		/* WHEREs INDIV */
		if($this->input->post('prenomIndiv') != ''){
			$this->db->like('Prenom', $this->input->post('prenomIndiv'), true );
		}
		if($this->input->post('nomIndiv') != ''){
			$this->db->like('Nom', $this->input->post('nomIndiv'), true );
		}
		if($this->input->post('compteIndiv') != ''){
			$this->db->like('Compte', $this->input->post('compteIndiv'), true );
		}

		$this->db->order_by('Prenom', 'asc');
		$query = $this->db->get('individus');
		return $query->result();

	}
	public function hasPaid($idIndiv, $idActiv){
		$this->db->db_select('db_indiv');
		$this->db->where('IdIndividu', $idIndiv);
		$this->db->where('IdActivite', $idActiv);
		$query = $this->db->get('presences');

		return $query->row();
	}

	public function isInscrit($idIndiv, $idActiv){
		$this->db->db_select('db_activ');
		$this->db->select('inscr.*, indiv.*, activ.Id as IdActiv, activ.Nom as NomActiv');
		$this->db->from('db_activ.inscriptions inscr');
		$this->db->join('db_indiv.individus indiv', 'indiv.Id = inscr.IdIndividu', 'left');
		$this->db->join('db_activ.activites activ', 'activ.Id = inscr.IdActivite', 'left');
		$this->db->where('inscr.IdIndividu', $idIndiv);
		$this->db->where('inscr.IdActivite', $idActiv);

		$query = $this->db->get('inscriptions');

		if ( $query->row() != null ){
			return $query->row();
		} else{

			$this->db->select('indiv.*, activ.Type, activ.Id as IdActiv, activ.Nom as NomActiv');
			$this->db->from('db_indiv.individus indiv, db_activ.activites activ');
			$this->db->where('indiv.Id', $idIndiv);
			$this->db->where('activ.Id', $idActiv);

			$query = $this->db->get();

			return $query->row();
		}

	}

	public function hasDebts($idIndiv){
		$this->db->db_select('db_indiv');

		$this->db->select('SUM(Montant) as Montant');
		$this->db->where('IdIndividu', $idIndiv);
		$query = $this->db->get('sommes_dues');
		return $query->result();
	}

	public function getPersonnages($idIndiv, $idActiv){
		$this->db->db_select('db_perso');

		$this->db->select('Id, CONCAT(Prenom, " ", Nom) as NomComplet');

		$this->db->where('IdIndividu', $idIndiv);
		$this->db->where('CodeEtat !=', 'MORT');
		$this->db->where('CodeEtat !=', 'SUPPR');

		$query = $this->db->get('personnages');

		return $query->result();
	}

	public function addInscription($idIndiv, $idActiv){
		$idPerso = $this->input->post('idPerso');

		$this->db->db_select('db_perso');
		$this->db->select('CONCAT(Prenom, " ", Nom) as NomComplet, Niveau');
		$this->db->where('Id', $idPerso);
		$query = $this->db->get('personnages');
		$nomPerso = $query->result();

		$message = "Niveau " .(($nomPerso[0]->Niveau)+1) ." !";

		$data = array(
			'IdPersonnage' => $idPerso,
			'Message' => $message,
			'Type' => 'LEVEL',
			'DateCreation' => date('Y-m-d H:i:s', time())
		);

		$this->db->insert('remarques',$data);

		$this->db->db_select('db_activ');

		$data = array(
			'IdActivite' => $idActiv,
			'IdIndividu' =>	$idIndiv,
			'IdPersonnage' => $idPerso,
			'NomPersonnage' => $nomPerso[0]->NomComplet,
			'DateInscription' => date('Y-m-d H:i:s', time()),
			'PrixInscrit' => $this->getMontant($idIndiv, $idActiv),
			'Commentaires' => null,
		);

		$this->db->insert('inscriptions', $data);
	}

	public function addPresence($idIndiv, $idActiv, $idPerso){
		if($idActiv == 'null'){
			$idActiv = $this->input->post('idActiv');
		}
		$montant = $this->input->post('montant');
		if($montant == null ){
			$montant = '0.00$';
		}

		$this->db->db_select('db_indiv');

		$data = array(
			'IdIndividu' => $idIndiv,
			'IdActivite' => $idActiv,
			'DateInscription' => date('Y-m-d H:i:s', time()),
			'Recu' => $montant,
		);

		$this->db->insert('presences', $data);

		$this->db->db_select('db_perso');

		$data = array( 'CodeEtat' => 'LEVEL' );
		$this->db->where('IdPersonnage', $idPerso);
		$this->db->where('CodeEtat', 'PRLVL');
		$this->db->update('competences_acquises', $data);


		$data = array(
			'IdIndividu' => $idIndiv,
			'Quantite' => '1',
			'DateInscription' => date('Y-m-d H:i:s', time()),
			'DateExpiration' => date('Y-m-d', strtotime('December 31 +1 year')),
			'Raison' => 'Presence'
		);

		$this->db->insert('db_indiv.arcanns', $data);




		
	}

	public function getMontant($idIndiv, $idActiv){
		$this->db->db_select('db_indiv');

		$this->db->select('DateNaissance, Tuteur');
		$this->db->where('Id', $idIndiv);

		$query = $this->db->get('individus');
		$info = $query->row();

		/*** Selon l'âge ***/
		$from = new DateTime($info->DateNaissance);
		$to   = new DateTime('today');
		$age = $from->diff($to)->y;

		/*** Get Type ***/
		$this->db->select('prix.PrixRegulier');
		$this->db->from('db_activ.activites activ');
		$this->db->join('db_activ.types_activite prix', 'prix.Type = activ.Type', 'left');
		$this->db->where('activ.Id', $idActiv);
		$query = $this->db->get();
		$prix = $query->row();


		if($age >= 16){
			$montant =  $prix->PrixRegulier;
		}
		elseif ($age >= 12 && $age <= 15) {
			$montant =  '25.00';
		}
		elseif ($age >= 6 && $age <= 11) {
			$montant =  '10.00';
		}
		elseif ($age <= 5) {
			$montant =  '0.00';
		}

		/*** Si Groupe Cadre ***/
		if($info->Tuteur == 'GroupeCadre' && $age >= 12){
			$montant =  '25.00';
		}

		/*** Si passe de saison ***/
		$this->db->db_select('db_activ');

		$this->db->from('db_indiv.passes_acquises acq');

		$this->db->join('db_activ.passes passes', 'passes.Id = acq.IdPasse', 'left');
		$this->db->join('db_activ.acces_par_passe app', 'app.IdPasse = acq.IdPasse', 'left');
		$this->db->where('acq.IdIndividu', $idIndiv);
		$this->db->where('app.IdActivite', $idActiv);
		$this->db->where('acq.CodeEtat !=', 'INACT');
		$query = $this->db->get();

		$hasPasse = $query->row();

		if($hasPasse){
			$montant = '0.00';
		}

		$this->db->db_select('db_activ');

		return $montant;

	}

	public function removeActiviteGratuite($idIndiv){
		$this->db->db_select('db_indiv');

		$this->db->select('ActivitesGratuites');
		$this->db->where('Id', $idIndiv);
		$query = $this->db->get('individus');

		$nbActivitesGratuites = $query->row();

		$data = array(
			'ActivitesGratuites' => ($nbActivitesGratuites->ActivitesGratuites)-1
		);
		$this->db->where('Id', $idIndiv);
		$this->db->update('individus', $data);
	}

	public function addXP($idIndiv, $idActiv){
		$idPerso = $this->input->post('idPerso');
		$this->db->db_select('db_activ');
		$this->db->select('Type, Nom');
		$this->db->where('Id', $idActiv);
		$query = $this->db->get('activites');
		$activ = $query->row();

		if($activ->Type == 'BANQUET' || $activ->Type == 'CONTRACT' || $activ->Type == 'MINI'){
			$this->db->db_select('db_indiv');
			$data = array(
				'IdIndividu' => $idIndiv,
				'Raison' => $activ->Nom,
				'XP' => 20,
				'DateInscription' => date('Y-m-d H:i:s', time()),
				'Commentaires' => null
			);

			if($activ->Type == 'MINI'): $data['XP'] = 10; endif;

			$this->db->insert('experience', $data);

		}elseif($activ->Type == 'BELEJR' || $activ->Type == 'ISOTOPE'){
				$this->db->db_select('db_indiv');
				$data = array(
						'IdIndividu' => $idIndiv,
						'Raison' => $activ->Nom,
						'XP' => 10,
						'DateInscription' => date('Y-m-d H:i:s', time()),
						'Commentaires' => null
				);

				$this->db->insert('experience', $data);
		}elseif($activ->Type == 'GN'){
			$this->db->db_select('db_perso');
			$this->db->select('Niveau');
			$this->db->where('Id', $idPerso);
			$query = $this->db->get('personnages');
			$perso = $query->row();
			/*** LVL UP ***/
			$data = array('Niveau' => ($perso->Niveau)+1);
			$this->db->where('Id', $idPerso);
			$this->db->update('personnages', $data);
			/*** Give XP ON LEVEL < 5 ***/
			if($perso->Niveau > 1 && $perso->Niveau < 5){
				$data = array(
					'IdPersonnage' => $idPerso,
					'Raison' => 'Niveau ' .(($perso->Niveau)+1),
					'XP' => 50,
					'DateInscription' => date('Y-m-d H:i:s', time()),
					'Commentaires' => null,
					'IdEnseignement' => null
				);

				$this->db->insert('experience', $data);
			}
			/*** UPDATE LEVEL SKILLS TO ACTIVE ***/
			$data = array( 'CodeEtat' => 'ACTIF' );
			$this->db->where('Id', $idPerso);
			$this->db->where('CodeEtat', 'LEVEL');
			$this->db->update('competences_acquises', $data);

			/***  Activate perso ***/
			$data = array(
				'CodeEtat' => 'ACTIF',
			);
			$this->db->where('IdIndividu',$idIndiv);
			$this->db->where('CodeEtat','LEVEL');
			$this->db->update('personnages',$data);

			$data = array(
				'CodeEtat' => 'LEVEL',
			);
			$this->db->where('Id',$idPerso);
			$this->db->update('personnages',$data);
		}
	}

	public function searchInscriptions($idActiv){
		$this->db->db_select('db_activ');
		$this->db->select('CONCAT (indiv.Prenom, " " , indiv.Nom) as NomIndivComplet, insc.*');
		$this->db->from('db_activ.inscriptions insc');
		$this->db->join('db_indiv.individus indiv', 'indiv.Id =  insc.IdIndividu', 'left');
		$this->db->join('db_indiv.presences pres', 'pres.IdIndividu = insc.IdIndividu AND pres.IdActivite = insc.IdActivite', 'left');
		$this->db->where('insc.IdActivite', $idActiv);
		$this->db->where('pres.IdIndividu', null);
		$this->db->where('indiv.Prenom !=', null);
		$this->db->order_by('indiv.Prenom', 'ASC');

		$query = $this->db->get();
		return $query->result();
	}

	public function deleteInscription($idActiv, $idIndiv, $idPerso){
		$this->db->db_select('db_activ');
		$this->db->where('IdIndividu', $idIndiv);
		$this->db->where('IdActivite', $idActiv);
		$this->db->where('IdPersonnage', $idPerso);

		$this->db->delete('inscriptions');

		/*** Remove XP Personnage ***/

		$this->db->select('Nom');
		$this->db->where('Id', $idActiv);
		$query = $this->db->get('activites');
		$nomActiv = $query->row();

		$data = array(
			'Raison' => 'Incr. '.$nomActiv->Nom .' annulée',
			'XP' => 0,
			'DateInscription' => date('Y-m-d H:i:s', time()),
		);

		$this->db->db_select('db_perso');
		$this->db->where('IdPersonnage', $idPerso);
		$this->db->like('Raison', $nomActiv->Nom);

		$this->db->update('experience', $data);

		/*** Level Down ***/

		$this->db->select('Niveau');
		$this->db->where('Id', $idPerso);
		$query = $this->db->get('personnages');
		$niveau = $query->row();

		$data = array(
			'Niveau' => ($niveau->Niveau)-1
		);

		$this->db->where('Id', $idPerso);
		$this->db->update('personnages', $data);

		$data = array(
			'IdPersonnage' => $idPerso,
			'Message' => 'Niveau annulé!',
			'Type' => 'LEVEL',
			'DateCreation' => date('Y-m-d H:i:s', time())
		);

		$this->db->insert('remarques',$data);
	}

	public function getPresences($idActiv){
		$this->db->select('pres.*, indiv.Compte, CONCAT(indiv.Prenom, " ", indiv.Nom) as NomIndivComplet');
		$this->db->from('db_indiv.presences pres');
		$this->db->join('db_indiv.individus indiv', 'indiv.Id = pres.IdIndividu', 'left');
		$this->db->where('pres.IdActivite', $idActiv);
		$this->db->order_by('NomIndivComplet', 'asc');

		$query = $this->db->get();
		return $query->result();
	}

	public function downloadPresencesList($idActiv){
		$this->db->select('CONCAT(indiv.Prenom, " ", indiv.Nom) as NomIndivComplet, pres.Recu');
		$this->db->from('db_indiv.presences pres');
		$this->db->join('db_indiv.individus indiv', 'indiv.Id = pres.IdIndividu', 'left');
		$this->db->where('pres.IdActivite', $idActiv);
		$this->db->order_by('NomIndivComplet', 'asc');

		$query = $this->db->get();
		return $query->result_array();
	}

	public function isLocation($idActiv){
		$this->db->db_select('db_activ');

		$this->db->select('typ.IndLocation');
		$this->db->from('db_activ.activites act');
		$this->db->where('Id', $idActiv);
		$this->db->join('db_activ.types_activite typ', 'typ.Type = act.Type', 'left');
		$query = $this->db->get();

		$activite = $query->row();

		if ($activite->IndLocation == '1') {
			return true;
		} else{
			return false;
		}
	}
	
}

/* End of file Inscriptions_model.php */
/* Location: ./application/models/Inscriptions_model.php */

?>