<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personnages_model extends CI_Model {

	public function __construct()	{
		parent::__construct();
		$this->db->db_select('db_perso');
	}

	public function getRaces(){
		$this->db->db_select('db_pilot');

		$this->db->select('Code, Nom');
		$query = $this->db->get('races');

		return $query->result();
	}

	public function getReligions(){
		$this->db->db_select('db_pilot');

		$this->db->select('Code, Nom');
		$query = $this->db->get('religions');

		return $query->result();
	}

	public function getClasses(){
		$this->db->db_select('db_pilot');

		$this->db->select('Code, Nom');
		$query = $this->db->get('classes');

		return $query->result();
	}

	public function getSubClasses(){
		$this->db->db_select('db_pilot');

		$this->db->select("sub.Code, sub.CodeClasse, sub.Nom as 'subNom', classe.Nom as Nom");
		$this->db->from('db_pilot.choix_comp_depart sub');
		$this->db->join('db_pilot.classes classe', 'classe.Code = sub.CodeClasse', 'left');
		$this->db->order_by('Nom, subNom', 'ASC');
		$query = $this->db->get();

		return $query->result();
	}

	public function getTitres($idPerso){
		$this->db->db_select('db_perso');

		$this->db->where('IdPersonnage', $idPerso);
		$query = $this->db->get('titres');

		return $query->result();
	}

	public function getAllTitres($idPerso){
		$this->db->db_select('db_pilot');

		$this->db->where('Type', 'PRESTIG');
		$this->db->or_where('Type', 'EMYTHIQ');
		$query = $this->db->get('competences_speciales');

		$titres = array();

		foreach ($query->result() as $key => $titre) {
			$titres[$key]['nom'] = $titre->Nom;
			$titres[$key]['is_available'] = false;

			if($titre->Nom != 'Baron'){
				$this->db->db_select('db_perso');
				$this->db->where('Titre', $titre->Nom);

				$query = $this->db->get('titres');
				if($query->result() == null){
					$titres[$key]['is_available'] = true;
				}
			}
		}

		return $titres;
	}

	public function getResults(){

		$this->db->db_select('db_indiv');

		$this->db->select('indiv.Nom as nomIndiv, indiv.Id as idIndiv, indiv.Prenom as prenomIndiv');
		$this->db->from('individus indiv');
		
		$this->db->join('db_perso.personnages perso', 'perso.IdIndividu = indiv.Id', 'left');

		/* WHEREs INDIV */
		if($this->input->post('prenomIndiv')){
			$this->db->like('indiv.Prenom', $this->input->post('prenomIndiv') );
		}
		if($this->input->post('nomIndiv')){
			$this->db->like('indiv.Nom', $this->input->post('nomIndiv') );
		}
		if($this->input->post('ddnIndiv')){
			$this->db->where('indiv.DateNaissance ' .$this->input->post('ddnIndivOp'), $this->input->post('ddnIndiv') );
		}
		/* WHEREs PERSO */
		if($this->input->post('prenomPerso')){
			$this->db->like('perso.Prenom', $this->input->post('prenomPerso') );
		}
		if($this->input->post('nomPerso')){
			$this->db->like('perso.Nom', $this->input->post('nomPerso') );
		}
		if($this->input->post('niveauPerso')){
			$this->db->where('perso.Niveau ' .$this->input->post('niveauPersoOp'), $this->input->post('niveauPerso') );
		}
		if($this->input->post('classePerso') != 'NULL'){
			$this->db->where('perso.CodeClasse', $this->input->post('classePerso') );
		}
		if($this->input->post('racePerso') != 'NULL'){
			$this->db->where('perso.CodeRace', $this->input->post('classePerso') );
		}
		if($this->input->post('religionPerso') != 'NULL'){
			$this->db->where('perso.CodeReligion', $this->input->post('classePerso') );
		}
		

		$this->db->group_by('idIndiv');

		$this->db->order_by('prenomIndiv, nomIndiv', 'asc');

		$query = $this->db->get();
		$vJoueurs = $query->result_array();

		$joueurs = [];

		foreach ($vJoueurs as $key =>$joueur) {

			$this->db->db_select('db_perso');
			$this->db->where('IdIndividu', $joueur['idIndiv'] );
			$this->db->where('CodeEtat !=', 'SUPPR');
			$query = $this->db->get('personnages');

			$joueurs[$key]['nomIndiv'] = $joueur['nomIndiv'];
			$joueurs[$key]['idIndiv'] = $joueur['idIndiv'];
			$joueurs[$key]['prenomIndiv'] = $joueur['prenomIndiv'];

			$joueurs[$key]['Personnages'] = $query->result();

		}

		return $joueurs;
	}

	public function getIndivInfo($idIndiv){
		$this->db->db_select('db_indiv');
		$this->db->select('Id, Prenom, Nom');
		$this->db->where('Id', $idIndiv);

		$query = $this->db->get('individus');
		return $query->row();
	}

	public function getPersoInfo($idPerso){
		$this->db->db_select('db_perso');

		$this->db->select('perso.*, race.Nom as Race, classe.Nom as Classe, religion.Nom as Religion');
		$this->db->from('personnages perso');
		$this->db->join('db_pilot.races race', 'race.Code = perso.CodeRace', 'left');
		$this->db->join('db_pilot.classes classe', 'classe.Code = perso.CodeClasse', 'left');
		$this->db->join('db_pilot.religions religion', 'religion.Code = perso.CodeReligion', 'left');
		$this->db->where('Id', $idPerso);

		$query = $this->db->get();
		return $query->row();
	}

	public function editReligion($idPerso) {
		$codeReligion = $this->input->post('newReligion');

		$data = array(
			'CodeReligion' => $codeReligion,
		);

		$this->db->db_select('db_perso');

		$this->db->where('Id', $idPerso);
		$this->db->update('personnages', $data);

	}

	public function editClasse($idPerso){
		$codeSubClasse = $this->input->post('newSubClasse');
		$codeRace = $this->input->post('codeRace');

		$this->db->where('IdPersonnage', $idPerso);
		$this->db->where('CodeAcquisition', 'DEPART');

		$this->db->delete('competences_acquises');

		$this->db->db_select('db_pilot');
		$this->db->select('CodeClasse');
		$this->db->where('Code', $codeSubClasse);
		$query = $this->db->get('choix_comp_depart');

		$result = $query->row();
		$codeNewClasse = $result->CodeClasse;

		$skillsClasse = [];
		$skillsRace = [];

		/*Competences de classe générale*/

		$this->db->select("compdep.CodeCompReg, compdep.CodeCompSpec, reg.Usages as 'Usages' ");
		$this->db->from('db_pilot.competences_depart compdep');
		$this->db->join('db_pilot.competences_regulieres reg', 'reg.Code = compdep.CodeCompReg', 'left');
		$this->db->join('db_pilot.competences_speciales spec', 'spec.Code = compdep.CodeCompSpec', 'left');
		$this->db->where('CodeClasse', $codeNewClasse);

		$query = $this->db->get();
		$results = $query->result();

		foreach ($results as $result) {
			if ($result->CodeCompReg == null) {
				$code = $result->CodeCompSpec;
				$type = 'CLASSE';
			} else {
				$code = $result->CodeCompReg;
				$type = 'REG';
			}

			$data = array(
				'IdPersonnage' 	 	=> $idPerso,
				'CodeCompetence'	=> $code,
				'Type' 				=> $type,
				'Usages'			=> $result->Usages,
				'CoutXP'			=> 0,
				'DateCreation'		=> date('Y-m-d H:i:s', time()),
				'CodeAcquisition'	=> 'DEPART',
				'CodeEtat'			=> 'ACTIF'
			);

			array_push($skillsClasse, $data);
		}

		/* Competences de sous-classe */

		$this->db->select('choix.CodeCompReg, choix.CodeCompSpec');
		$this->db->from('db_pilot.competences_choix choix');
		$this->db->join('db_pilot.competences_regulieres reg', 'reg.Code = choix.CodeCompReg', 'left');
		$this->db->where('CodeChoix', $codeSubClasse);

		$query = $this->db->get();
		$results = $query->result();

		foreach ($results as $result) {
			if ($result->CodeCompReg == null) {
				$code = $result->CodeCompSpec;
				$type = 'CLASSE';
			} else {
				$code = $result->CodeCompReg;
				$type = 'REG';
			}

			$data = array(
				'IdPersonnage' 	 	=> $idPerso,
				'CodeCompetence'	=> $code,
				'Type' 				=> $type,
				'Usages'			=> NULL,
				'CoutXP'			=> 0,
				'DateCreation'		=> date('Y-m-d H:i:s', time()),
				'CodeAcquisition'	=> 'DEPART',
				'CodeEtat'			=> 'ACTIF'
			);

			array_push($skillsClasse, $data);
		}

		/* Competences Raciales */

		$this->db->select('rac.CodeCompReg, rac.CodeCompSpec');
		$this->db->from('db_pilot.competences_raciales rac');
		$this->db->where('rac.CodeRace', $codeRace);

		$query = $this->db->get();
		$results = $query->result();

		foreach ($results as $result) {

			if ($result->CodeCompReg == null) {
				$code = $result->CodeCompSpec;
			} else {
				$code = $result->CodeCompReg;
			}

			$data = array(
				'IdPersonnage' 	 	=> $idPerso,
				'CodeCompetence'	=> $code,
				'Type' 				=> 'RACIALE',
				'Usages'			=> 1,
				'CoutXP'			=> 0,
				'DateCreation'		=> date('Y-m-d H:i:s', time()),
				'CodeAcquisition'	=> 'DEPART',
				'CodeEtat'			=> 'ACTIF'
			);

			array_push($skillsRace, $data);
		}

		/* Cherche Doublons pour skills niv.2 SELON CLASSE - RACE */
		$skills = [];

		foreach ($skillsClasse as $skillClass) {
			foreach ($skillsRace as $skillRace) {
				if($skillClasse['CodeCompetence'] == $skillRace['CodeCompetence']){
					$this->db->db_select('db_pilot');

					$this->db->select('Code, CodeCompRemplacee');
					$this->db->where('CodeCompRemplacee', $skill['CodeCompetence']);
					$query = $this->db->get('competences_regulieres');
					$result = $query->row();
					if($result) {
						$skillRace['CodeCompetence'] = $result->Code;
						$skillClasse['CodeEtat'] = 'REMPL';
					}
				}
			}
		}

		$this->db->db_select('db_perso');
		foreach ($skills as $skill) {
			$this->db->insert('competences_acquises', $skill);
		}

		$this->db->db_select('db_perso');
		$this->db->where('Id', $idPerso);

		$this->db->update('personnages', array('CodeClasse' => $codeNewClasse));

		return $skills;
		
	}

	public function getSkills($idPerso){

		$this->db->db_select('db_perso');

		$this->db->select('skills.Id, skills.CodeCompetence, skills.Type, SUM(skills.Usages) as UEC, skills.CoutXP, skills.DateCreation, skills.CodeAcquisition, skills.CodeEtat, reg.Nom as regNom, spec.Nom as specNom');
		$this->db->from('db_perso.competences_acquises skills');
		$this->db->join('db_pilot.competences_regulieres reg', 'reg.Code = skills.CodeCompetence', 'left');
		$this->db->join('db_pilot.competences_speciales spec', 'spec.Code = skills.CodeCompetence', 'left');

		$where = "skills.IdPersonnage = " .$idPerso ." AND (skills.CodeEtat = 'ACTIF' OR skills.CodeEtat = 'LEVEL' OR skills.CodeEtat = 'PRLVL' ) ";
		$this->db->where($where);
		$this->db->group_by('skills.CodeCompetence');

		$query = $this->db->get();

		return $query->result();
	}

	public function getRegSkills($idPerso){

		$this->db->db_select('db_perso');

		/* Get Perso XP */
		$this->db->select('perso.CodeClasse, perso.CodeRace, SUM(exp.XP) as XP');
		$this->db->from('db_perso.personnages perso');
		$this->db->join('db_perso.experience exp', 'exp.IdPersonnage = perso.Id', 'left');
		$this->db->where('perso.Id', $idPerso);

		$query = $this->db->get();
		$perso = $query->row();

		$persoXP = $perso->XP;
		$persoClasse = $perso->CodeClasse;
		$persoRace = $perso->CodeRace;

		/* Get Reg Skills */
		$this->db->where('IdPersonnage', $idPerso);
		$this->db->where('Type', 'REG');
		$query = $this->db->get('competences_acquises');
		$persoRegSkills = $query->result();

		/* Get Non Reg Skills */
		$this->db->where('IdPersonnage', $idPerso);
		$this->db->where('Type !=', 'REG');
		$query = $this->db->get('competences_acquises');
		$persoTalents = $query->result();

		$lSkillCodeCounts = array();
		foreach ($persoRegSkills as $key => $persoRegSkill) {
			$lSkillCodeCounts[$persoRegSkill->CodeCompetence] = 1;
			if ($key >1 && $persoRegSkills[$key]->CodeCompetence == $persoRegSkills[$key-1]->CodeCompetence) {
				$lSkillCodeCounts[$persoRegSkill->CodeCompetence] .= 1;
			}
		}

		//$lSkillCodeCounts = array_count_values($persoRegSkills['CodeCompetence']);

		$hasMagic = false;
		foreach ($persoRegSkills as $persoRegSkill) {
			if( $persoRegSkill->CodeCompetence == 'MAGIA1' || $persoRegSkill->CodeCompetence == 'MAGIC1' || $persoRegSkill->CodeCompetence == 'MAGIM1' || $persoRegSkill->CodeCompetence == 'MAGIS1' ) { 
				
				$hasMagic = true; 
			}
		}

		$hasSuperiorMagic = false;
		foreach ($persoTalents as $persoTalent) {
			if( $persoTalent->CodeCompetence == 'NIVMSUP' ) { $hasSuperiorMagic = true; }
		}
		

		$this->db->select('CodeCompetence');
		$this->db->where('IdEtudiant', $idPerso);
		$this->db->where('CodeEtat', 'ACTIF');
		$query = $this->db->get('enseignements');
		$teachings = $query->result();

		// Ask the database for the skill tree
		$query = 	"SELECT creg.Code, creg.Nom, creg.Categorie, ajc.Multiplicateur, (ajc.Multiplicateur*ccr.CoutXP) AS 'CoutMultiplie', creg.CodeCompPrerequise AS 'Prerequis', 
					creg.CodeCompRemplacee AS 'Remplace', creg.Usages, creg.Achats
				 FROM db_pilot.competences_regulieres creg
				    INNER JOIN db_pilot.ajustements_categorie ajc ON creg.Categorie = ajc.Categorie
						JOIN db_pilot.classes clas ON ajc.CodeClasse = clas.Code
				    INNER JOIN db_pilot.cout_competences_reg ccr  ON creg.Code = ccr.CodeCompReg
						JOIN db_pilot.races rac ON ccr.CodeRace = rac.Code
				 WHERE creg.CodeEtat = 'ACTIF'
				   AND clas.Code = '" . $persoClasse ."' 
				   AND rac.Code = '" . $persoRace ."' 
				 ORDER BY creg.Nom ASC";

		 $query = $this->db->query($query);
		 $skillTree = $query->result();

		// Build the skill tree
		$return = array();
		foreach($skillTree as $i => $skill) {

			// Calculate final cost
			$cost = $skill->CoutMultiplie;
			$trained = false;
			foreach ($teachings as $teaching) {
				if ($teaching->CodeCompetence == $skill->Code) {
					$trained = true;
				}
			}
			
			if( $skill->Multiplicateur < 1 && $skill->Remplace) { $cost *= 0.5; }	// If second-or-more level and in a bonus category... halved!
			if( $trained !== false ) { $cost *= 0.8; } 					// If character has received teaching for this skill... 20% bonus!

			// Handle skill attributes
			$return[$i]['code'] = $skill->Code;
			$return[$i]['name'] = $skill->Nom;
			$return[$i]['category'] = $skill->Categorie;
			$return[$i]['adjustment'] = $skill->Multiplicateur;
			$return[$i]['cost'] = ceil($cost);
			$return[$i]['prerequisites'] = explode( ";", $skill->Prerequis );
			$return[$i]['replace'] = $skill->Remplace;
			$return[$i]['uses'] = $skill->Usages;
			$return[$i]['maxpurchases'] = $skill->Achats;

			$return[$i]['obtained'] = 0;
			foreach ($persoRegSkills as $persoRegSkill) {
				if ($persoRegSkill->CodeCompetence == $skill->Code) {
					$return[$i]['obtained']++;
				}
			}

			if( $trained === false ) 
				{ $return[$i]['trained'] = false; }
			else 	{ $return[$i]['trained'] = true; }

			$return[$i]['buyable'] = true;
			if( $return[$i]['obtained'] >= $return[$i]['maxpurchases'] ) { $return[$i]['buyable'] = false; }
			elseif( $hasMagic && ($skill->Code == 'MAGIA1' || $skill->Code == 'MAGIC1' || $skill->Code == 'MAGIM1' ||$skill->Code == 'MAGIS1') ) { $return[$i]['buyable'] = false; }
			elseif( !$hasSuperiorMagic && ($skill->Code == 'MAGIA4' || $skill->Code == 'MAGIC4' || $skill->Code == 'MAGIM4' || $skill->Code == 'MAGIS4') ) { $return[$i]['buyable'] = false; }
			elseif( $skill->Prerequis ) {

				$lPrereqMet = false;
				$lOnlyOneLevel = true;

				// Check if prereq is obtained and is not on the 'Level up' list
				foreach( $return[$i]['prerequisites'] as $option) {
					foreach ($persoRegSkills as $persoRegSkill) {
						if ($persoRegSkill->CodeCompetence == $option) {
							$lPrereqMet = true;
						}
					}
				}

				// Check if skill is not the next level of a bought skill
				if( $skill->Remplace ) {
					foreach ($persoRegSkills as $persoRegSkill) {
						if ($persoRegSkill->CodeCompetence == $skill->Remplace) {
							$lOnlyOneLevel = true;
						}
					}
				}

				$return[$i]['buyable'] = $lPrereqMet && $lOnlyOneLevel;
			}

			if( $return[$i]['cost'] <= $persoXP ) { $return[$i]['affordable'] = true; }
			else { $return[$i]['affordable'] = false; }
		}

		return $return;

	}

	public function getSpecSkills(){
		$this->db->db_select('db_pilot');

		$this->db->select('Code, Nom, Type');
		$query = $this->db->get('competences_speciales');
		$this->db->order_by('Nom', 'asc');

		return $query->result();

	}

	public function getXP($idPerso){
		$this->db->db_select('db_perso');

		$this->db->select_sum('XP', 'XP');
		$this->db->where('IdPersonnage', $idPerso);

		$query = $this->db->get('experience');

		return $query->row();
	}

	public function getPV($idPerso){
		$this->db->db_select('db_perso');

		$this->db->select("*, (SELECT SUM(PV) FROM db_perso.points_de_vie WHERE IdPersonnage = " .$idPerso ." ) as 'SommePV'");
		$this->db->where('IdPersonnage', $idPerso);

		$query = $this->db->get('points_de_vie');

		return $query->result();
	}

	public function getTravail($idPerso){
		$this->db->db_select('db_group');

		$this->db->select('trav.*, group.Nom');
		$this->db->from('db_group.travailleurs trav');
		$this->db->where('IdPersonnage', $idPerso);
		$this->db->join('db_group.groupes group', 'trav.IdGroupe = group.Id', 'left');
		$query = $this->db->get();

		return $query->row();
	}

	public function paySkill($idPerso){
				

		$info = explode(',', $this->input->post('paySkill') );

		$code = $info[0];
		$cost = $info[1];

		$this->db->db_select('db_pilot');

		$this->db->select('Usages, Nom');
		$this->db->where('Code', $code);
		$query = $this->db->get('competences_regulieres');
		$usages = $query->row();

		$this->db->db_select('db_perso');

		if ($usages == 'NULL') : $usages = NULL; endif;

		$data = array(
			'IdPersonnage' => $idPerso,
			'CodeCompetence' => $code,
			'Type' => 'REG',
			'Usages' => $usages->Usages,
			'CoutXP' => $cost,
			'DateCreation' => date('Y-m-d H:i:s', time()),
			'CodeAcquisition' => 'NORMALE',
			'CodeEtat' => 'LEVEL',
		);

		$this->db->insert('competences_acquises', $data);

		$this->db->db_select('db_perso');

		$data = array(
			'IdPersonnage' => $idPerso,
			'Raison' => 'Achat - ' .$usages->Nom,
			'XP' => intval(-$cost),
			'DateInscription' => date('Y-m-d H:i:s', time()),
			'Commentaires' => NULL,
			'IdEnseignement' => NULL
		);

		$this->db->insert('experience', $data);

		if ( substr($code,0,2) == 'PV') {
			$data = array(
				'IdPersonnage' => $idPerso,
				'Raison' => 'Achat de PV',
				'PV' => 1,
				'DateInscription' => date('Y-m-d H:i:s', time()),
				'Commentaires' => null,
			);

			$this->db->insert('points_de_vie', $data);
		}

		$this->updatePreviousSkill($idPerso, $code);
	}

	public function giveSkill($idPerso){
		

		$info = explode(',', $this->input->post('giveSkill') );

		if( $info[1] == null ) {

			$this->db->db_select('db_pilot');
			$this->db->select('Usages');
			$this->db->where('Code', $info[0]);
			$query = $this->db->get('competences_regulieres');
			$usages = $query->row();
			$usages = $usages->Usages;
		}


		$this->db->db_select('db_perso');

		if( $info[1] != null ) {

			$code = $info[0];
			$type = 'REG';
			$usages = $info[1];
			$cost = 0;
			$codeAcquisition = 'GRATUIT';
			$etat = 'LEVEL';
		} else{ 
			$code = $info[0];
			$type = 'REG';
			$usages = NULL;
			$cost = 0;
			$codeAcquisition = 'GRATUIT';
			$etat = 'ACTIF';
		}

		if($usages == 0): $usages = null; endif;

		$data = array(
			'IdPersonnage' => $idPerso,
			'CodeCompetence' => $code,
			'Type' => $type,
			'Usages' => $usages,
			'CoutXP' => 0,
			'DateCreation' => date('Y-m-d H:i:s', time()),
			'CodeAcquisition' => $codeAcquisition,
			'CodeEtat' => $etat,
		);

		$this->db->insert('competences_acquises', $data);

		$this->updatePreviousSkill($idPerso, $code);
		
	}

	public function updatePreviousSkill($idPerso, $codeCompetence){
		$this->db->db_select('db_pilot');

		$this->db->select('CodeCompRemplacee');
		$this->db->where('Code', $codeCompetence);
		$query = $this->db->get('competences_regulieres');
		$codeCompRemplacee = $query->row();

		$this->db->db_select('db_perso');
		if($codeCompRemplacee && $codeCompRemplacee->CodeCompRemplacee != null){
			$this->db->where('IdPersonnage', $idPerso);
			$this->db->where('CodeCompetence', $codeCompRemplacee->CodeCompRemplacee);

			$data = array('CodeEtat' => 'REMPL');
			$this->db->update('competences_acquises', $data);
		}
	}

	public function deleteSkills($idSkill, $codeEtat){;

		if($codeEtat == 'ACTIF'){
			$updateData = array(
				'CodeEtat' => 'INACT',
			);

			$this->db->where('Id', $idSkill);
			$this->db->update('competences_acquises', $updateData);
			return;
		}
		if($codeEtat == 'LEVEL'){


			$this->db->select('acq.*, pilot.Nom as NomSkillComplet, pilot.CodeCompPrerequise as Prerequis');
			$this->db->from('db_perso.competences_acquises acq');
			$this->db->join('db_pilot.competences_regulieres pilot', 'pilot.Code = acq.CodeCompetence', 'left');
			$this->db->where('acq.Id', $idSkill);
			$query = $this->db->get();
			$acq = $query->row();

			/**/
			$this->db->where('Id', $idSkill);
			$this->db->delete('competences_acquises');

			/**/
			$this->db->db_select('db_perso');
			$raison = 'Achat - ' .$acq->NomSkillComplet;
			$sql = "DELETE FROM experience WHERE IdPersonnage = " .$acq->IdPersonnage ." AND Raison = '" .$raison ."' LIMIT 1;";
			$this->db->query($sql);

			/**/
			if($acq->CodeAcquisition == 'RABAIS'){
				$sql = "UPDATE db_perso.enseignements SET CodeEtat = 'ACTIF' WHERE IdEtudiant = " .$acq->IdPersonnage ." AND CodeCompetence = '" .$acq->CodeCompetence ."'  AND CodeEtat = 'INACT' LIMIT 1";
				$this->db->query($sql);
			}

			if($acq->Prerequis != null){
				$sql = "UPDATE db_perso.competences_acquises SET CodeEtat = 'ACTIF' WHERE IdPersonnage = " .$acq->IdPersonnage ." AND CodeCompetence = '" .$acq->Prerequis ."';";
				$this->db->query($sql);
			}

		}
	}

	public function declareMort($idPerso){

		$comment = $this->input->post('comment');

		if ($this->input->post('comment') == '' ):
			$comment = NULL;
		endif;

		$raison = $this->input->post('declareMort');

		$data = array(
			'IdPersonnage' => $idPerso,
			'Raison' => $raison,
			'PV' => '-1',
			'DateInscription' => date('Y-m-d H:i:s', time()),
			'Commentaires' => $comment,
		);

		$this->db->insert('points_de_vie', $data);

	}

	public function deleteTravail($idPerso){
		$this->db->db_select('db_group');

		$this->db->where('IdPersonnage',$idPerso);
		$this->db->delete('travailleurs');
	}

	public function levelUP($idPerso, $idIndiv, $currentLvl){
		$this->db->db_select('db_perso');

		$currentLvl = intval($currentLvl);

		/* Activate Temp skills */
		$data = array(
			'CodeEtat' => 'ACTIF'
		);

		$this->db->where('IdPersonnage',$idPerso);
		$this->db->where('CodeEtat','LEVEL');

		$this->db->update('competences_acquises',$data);

		$data = array(
			'CodeEtat' => 'ACTIF',
		);
		$this->db->where('IdIndividu',$idIndiv);
		$this->db->where('CodeEtat','LEVEL');
		$this->db->update('personnages',$data);


		$data = array(
			'CodeEtat' => 'ACTIF',
		);
		$this->db->where('Id',$idPerso);
		$this->db->update('personnages',$data);

		/* Grant a level */

		$data = array(
			'Niveau' => $currentLvl+1,
			'CodeEtat' => 'LEVEL'
		);
		$where = "Id= " .$idPerso ." AND CodeEtat IN ('NOUVO', 'ACTIF')";
		$this->db->where($where);
		$this->db->update('personnages', $data);

		/* Update character's journal */		

		$message = "Niveau " .($currentLvl+1) ." !";

		$data = array(
			'IdPersonnage' => $idPerso,
			'Message' => $message,
			'Type' => 'LEVEL',
			'DateCreation' => date('Y-m-d H:i:s', time())
		);

		$this->db->insert('remarques',$data);

		$this->db->db_select('db_indiv');

		/* CHECK IF TUTORED */
		$this->db->select('Tuteur');
		$this->db->where('Id',$idIndiv);

		$query = $this->db->get('individus');
		$boolTuteur = $query->row();

		/* Grant XP */	
		
		$this->db->db_select('db_perso');
		
		if( ($currentLvl+1) > 1 && ($currentLvl+1) <= 5){
			$message = "Niveau " .($currentLvl+1);

			$XP = 50;
			if( !is_null( $boolTuteur->Tuteur ) && $boolTuteur->Tuteur != 'GroupeCadre' ){
				$XP = 25;
			}

			$insertData = array(
				'IdPersonnage' => $idPerso,
				'Raison' => $message,
				'XP' => $XP,
				'DateInscription' => date('Y-m-d H:i:s', time()),
				'Commentaires' => null,
			);

			$this->db->insert('experience',$insertData);

		}
	}

	public function addTitre($idPerso, $idIndiv){
		$insertData = array(
			'IdPersonnage' => $idPerso,
			'Titre' => $this->input->post('titre'),
			'Description' => $this->input->post('description'),
			'Avantages' => $this->input->post('avantages'),
			'DateAcquisition' => date('Y-m-d H:i:s', time())
		);

		$this->db->db_select('db_perso');
		$this->db->insert('titres', $insertData);
	}

	public function removeTitre($idTitre){
		$this->db->db_select('db_perso');
		$this->db->where('Id', $idTitre);
		$this->db->delete('titres');
	}

	public function getSpells($idPerso){
		$this->db->db_select('db_perso');

		$query = "SELECT acq.*, reg.Nom as 'cleanNom' 
			FROM competences_acquises acq
			LEFT JOIN db_pilot.competences_regulieres reg ON reg.Code = acq.CodeCompetence 
			WHERE acq.IdPersonnage = {$idPerso} 
			AND (acq.CodeCompetence LIKE '%RECET%' OR acq.CodeCompetence LIKE '%SORT%' OR acq.CodeCompetence LIKE 'METIER') 
			AND (acq.CodeEtat = 'ACTIF' OR acq.CodeEtat = 'PRLVL' OR acq.CodeEtat = 'LEVEL')
			ORDER BY acq.CodeCompetence;";
		

		$query = $this->db->query($query);

		return $query->result();
	}

	public function getAllMetiers(){
		$this->db->db_select('db_pilot');

		$this->db->order_by('Nom', 'asc');
		$query = $this->db->get('metiers');

		return $query->result();
	}

	public function getAllSpells(){
		$this->db->db_select('db_pilot');

		$this->db->order_by('Nom', 'asc');
		$query = $this->db->get('sorts');

		return $query->result();
	}

	public function getAllRecettes(){
		$this->db->db_select('db_pilot');

		$this->db->order_by('Nom', 'asc');
		$query = $this->db->get('recettes');

		return $query->result();
	}

	public function updateSpells($idPerso, $idIndiv, $spells){

		foreach ($spells as $key => $spell) {
			$data = [
				'Precision' => $spell
			];

			$this->db->where('Id', $key);
			$this->db->where('IdPersonnage', $idPerso);
			$this->db->update('competences_acquises', $data);
		}
	}

	public function editNoteRapide($idPerso, $idIndiv, $data){
		$this->db->db_select('db_perso');

		$this->db->where('Id', $idPerso);
		$this->db->update('personnages', ['NoteRapide' => $data]);
	}

	public function changeState($idPerso, $newState){
		$this->db->db_select('db_perso');

		$this->db->where('Id', $idPerso);
		$this->db->update('personnages', ['CodeEtat' => $newState]);
	}

}

/* End of file Personnages_model.php */
/* Location: ./application/models/Personnages_model.php */ ?>