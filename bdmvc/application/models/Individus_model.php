<?php
class Individus_model extends CI_Model{

	public function __construct(){
		$this->load->database('db_indiv', TRUE);
	}

	public function getIndividus(){
		$this->db->select('Id,Compte');
		$query = $this->db->get('individus');

		return $query->result_array();
	}

	public function getSingleIndividu($id){

		$this->db->db_select('db_indiv');


		$this->db->select('*');
		$this->db->from('individus');
		$this->db->where('Id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function getFullIndividu($id){
		$this->db->db_select('db_indiv');

		$returnedIndividu = array();

		/*Dettes*/
		$this->db->where('IdIndividu',$id);
		$indivDettes = $this->db->get('sommes_dues');
		$returnedIndividu['indivDettes'] = $indivDettes->result_array();

		return $returnedIndividu;

	}

	public function quickInscription($compte, $prenomJoueur, $nomJoueur) {
		$this->db->db_select('db_indiv');

		$this->db->select('*');

		if ( !empty($compte) ){
			$this->db->like('Compte',$compte);
		}

		if ( !empty($prenomJoueur) ){
			$this->db->like('Prenom',$prenomJoueur);
		}

		if ( !empty($nomJoueur) ){
			$this->db->like('Nom',$nomJoueur);
		}

		$results = $this->db->get('individus');

		return $results->result_object();
	}

	public function insertDette($idIndiv, $montantDette, $commentaireDette){
		$this->db->db_select('db_indiv');

		$insertData = array(
			'IdIndividu' => $idIndiv,
			'Montant' => $montantDette,
			'Raison' => 'Ajout manuel',
			'DateInscription' => date('Y-m-d H:i:s', time()),
			'Commentaires' => $commentaireDette
		);

		$this->db->insert('sommes_dues',$insertData);

		return true;

	}

	public function getQtyArcanns($idIndiv){

		$this->db->select("SUM(Quantite) as Total");
		$this->db->from('db_indiv.arcanns');
		$this->db->where('IdIndividu', $idIndiv);
		$this->db->where('DateInscription >', date( 'Y-m-d', strtotime('January 1 last year')));


		$query = $this->db->get();

		return $query->row();
	}

	public function initArcann2019(){
		/* DELETE OLD DATA */
		/*$this->db->where('DateInscription >= ', '2019-01-01');
		$this->db->delete('arcanns');*/

		/*Init with 2019 data*/
		/*$this->db->select('pre.IdIndividu, COUNT(pre.IdActivite) as Total');
		$this->db->from('db_indiv.presences pre');
		$this->db->join('db_activ.activites act', 'act.Id = pre.IdActivite', 'left');
		$this->db->where('act.DateDebut >', '2019-01-01');
		$this->db->where('act.Type', 'GN');
		$this->db->group_by('pre.IdIndividu');

		$query = $this->db->get();

		$results = $query->result();

		foreach ($results as $result) {
			$this->db->where('Raison', 'Init Arcann 2017');
			$this->db->where('IdIndividu', $result->IdIndividu);
			$this->db->delete('db_indiv.arcanns');
		}
		*/

	}

	public function retraitArcann($idIndiv){
		$data = array(
				'IdIndividu' => $idIndiv,
				'Quantite' => strval(-($this->input->post('retrait_qty')) ),
				'DateInscription' => date('Y-m-d H:i:s', time()),
				'DateExpiration' => date('Y-m-d', strtotime('December 31 2050')),
				'Raison' => 'Retrait'
			);

			$this->db->insert('db_indiv.arcanns', $data);
	}

	
}