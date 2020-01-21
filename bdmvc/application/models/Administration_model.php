<?php
class Administration_model extends CI_Model{

	public function __construct(){
		$this->load->database('db_perso');
	}

	public function addCreditOrDebt($idIndividu, $raison, $montant, $commentaires){
		$this->db->db_select('db_indiv');

		$insertData = array(
			'IdIndividu' => $idIndividu,
			'Montant' => $montant,
			'DateInscription' => date('Y-m-d H:i:s', time()),
			'Raison' => $raison,
			'Commentaires' => $commentaires
		);

		$this->db->insert('sommes_dues',$insertData);
	}

	public function getCreditsAndDebts($idIndiv){
		$this->db->db_select('db_indiv');

		$this->db->where('IdIndividu',$idIndiv);
		$sommes = $this->db->get('sommes_dues');
		$returned['sommes'] = $sommes->result_array();


		$this->db->where('Id',$idIndiv);
		$infosJoueurs = $this->db->get('individus');
		$returned['infosJoueurs'] = $infosJoueurs->result_array();

		return $returned;
	}

	public function removeCreditOrDebt($idSomme){
		$this->db->db_select('db_indiv');

		$this->db->where('Id',$idSomme);
		$this->db->delete('sommes_dues');
	}

	public function getavertissements($idIndiv){
		$this->db->db_select('db_indiv');

		$query = "SELECT avert.*, indiv.Prenom, indiv.Nom
		FROM db_indiv.avertissements avert 
		LEFT JOIN db_indiv.individus indiv ON avert.IdInscripteur = indiv.Id 
		WHERE IdCible = " .$idIndiv .";";

		$indivAvertissements = $this->db->query($query);
		$returned['avertissements'] = $indivAvertissements->result_array();

		$this->db->where('NiveauAcces >=', 4);
		$inscripteurs = $this->db->get('individus');
		$returned['inscripteurs'] = $inscripteurs->result_array();

		$this->db->where('Id', $idIndiv);
		$infosJoueur = $this->db->get('individus');
		$returned['infosJoueur'] = $infosJoueur->result_array();

		return $returned;

	}

	public function updateNiveauAcces($idIndividu, $newNiveauAcces){
		$this->db->db_select('db_indiv');

		$data = array('NiveauAcces'=>$newNiveauAcces);

		$this->db->where('id',$idIndividu);
		$this->db->update('individus',$data);

	}

	public function getSommaire(){
		$this->db->db_select('db_indiv');

		$this->db->select("som.*, CONCAT(ind.Prenom, ' ', ind.Nom) as nomIndiv");
		$this->db->from('db_indiv.sommes_dues som');
		$this->db->join('db_indiv.individus ind', 'ind.Id = som.IdIndividu', 'left');
		$this->db->order_by('nomIndiv', 'asc');
		$query = $this->db->get();

		return $query->result();
	}

	public function deleteCreditOuDette($idCredit){
		$this->db->db_select('db_indiv');

		$this->db->where('Id', $idCredit);

		$this->db->delete('sommes_dues');
	}

	public function getBaronnies($idComte = false, $idBaronnie = false){
		$this->db->db_select('db_histo');


		$this->db->select('b.*, c.Nom AS NomComte');
		$this->db->from('db_histo.baronnies b');
		$this->db->join('db_histo.comtes c', 'c.Id = b.IdComte' , 'left');

		if($idComte){
			$this->db->where('b.IdComte', $idComte);
		}

		if($idBaronnie){
			$this->db->where('b.Id', $idBaronnie);
		}

		$this->db->order_by('b.IdComte ASC, b.Cadastre ASC');
		$query = $this->db->get();

		return $query->result();

	}

	public function getComtes($codeDuche = false){
		$this->db->db_select('db_histo');


		$this->db->select('c.*, d.Nom AS NomDuche');
		$this->db->from('db_histo.comtes c');
		$this->db->join('db_histo.duches d', 'd.Code = c.CodeDuche' , 'left');

		if($codeDuche){
			$this->db->where('c.CodeDuche', $codeDuche);
		}

		$this->db->order_by('d.Nom ASC, c.Nom ASC');
		$query = $this->db->get();

		return $query->result();

	}

	public function getDuches(){
		$this->db->db_select('db_histo');

		$this->db->select('d.*, r.Nom AS NomRoyaume');
		$this->db->from('db_histo.duches d');
		$this->db->join('db_histo.royaumes r', 'r.Code = d.CodeRoyaume' , 'left');

		$this->db->order_by('r.Nom ASC, d.Nom ASC');
		$query = $this->db->get();

		return $query->result();
	}

	public function getRoyaumes(){
		$this->db->db_select('db_histo');


		$this->db->order_by('Code', 'ASC');
		$query = $this->db->get('royaumes');

		return $query->result();

	}

	public function editBaronnie($idBaronnie){
		$this->db->db_select('db_histo');

		$data = array(
			'IdComte' => $_POST['idComte'],
			'Nom' => $_POST['Nom'],
			'Baron' => $_POST['Baron'],
			'CodeEtat' => $_POST['CodeEtat'],
		);

		$this->db->where('Id', $idBaronnie);
		$this->db->update('baronnies', $data);

	}

	public function editComte($idComte){
		$this->db->db_select('db_histo');

		$data = array(
			'Nom' => $_POST['Nom'],
			'Couleur' => $_POST['Couleur'],
			'CodeDuche' => $_POST['CodeDuche'],
			'Dirigeant' => $_POST['Dirigeant'],
			'DescriptionDirigeant' => $_POST['DescriptionDirigeant'],
			'Scribe' => $_POST['Scribe'],
			'DescriptionScribe' => $_POST['DescriptionScribe'],
		);

		$this->db->where('Id', $idComte);
		$this->db->update('comtes', $data);
	}

	public function editDuche($codeDuche){
		$this->db->db_select('db_histo');

		$data = array(
			'Nom' => $_POST['Nom'],
			'CodeRoyaume' => $_POST['CodeRoyaume'],
			'Description' => $_POST['Description']
		);

		$this->db->where('Code', $codeDuche);
		$this->db->update('duches', $data);
	}
	
}