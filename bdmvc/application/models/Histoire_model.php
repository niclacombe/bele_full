<?php
class Histoire_model extends CI_Model{

  public function __construct(){
    $this->load->database('db_histo');
    $this->db->db_select('db_histo');
  }

  public function getBaronnies($idComte = false, $idBaronnie = false){
    $this->db->db_select('db_histo');


    $this->db->select('b.*, c.Nom AS NomComte');
    $this->db->from('db_histo.baronnies b');
    $this->db->join('db_histo.comtes c', 'c.Id = b.IdComte' , 'left');
    $this->db->order_by('cast(b.Cadastre as unsigned)', 'asc');

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

  public function editRoyaume($codeRoyaume){
    $this->db->db_select('db_histo');

    $data = array(
      'Nom' => $_POST['Nom'],
      'Description' => $_POST['Description'],
      'CodeEtat' => $_POST['CodeEtat'],
    );

    $this->db->where('Code', $codeRoyaume);
    $this->db->update('royaumes', $data);
  }

  public function getTrames(){
    $this->db->db_select('db_histo');
    
    $this->db->select("tra.*, com.Nom as Comte, CONCAT(ind.Prenom, ' ' , ind.Nom) as Createur");
    $this->db->from("db_histo.trames tra");
    $this->db->join('db_histo.comtes com', 'com.Id = tra.IdComte' , 'left');
    $this->db->join('db_indiv.individus ind', 'ind.Id = tra.IdCreateur' , 'left');
    $this->db->order_by('tra.Id');

    $query = $this->db->get();

    return $query->result();
  }

  public function addTrame(){
    $this->db->db_select('db_histo');

    $data = array(
      'Id' => $_POST['Id'],
      'Nom' => $_POST['Nom'],
      'IdComte' => $_POST['IdComte'],
      'Description' => $_POST['Description'],
      'CodeEtat' => $_POST['CodeEtat'],
      'IdCreateur' => $_SESSION['infoUser']->Id,
      'DateCreation' => date('Y-m-d H:i:s', time()),
    );
    
    $this->db->insert('trames', $data);
  }

  public function editTrame($idTrame){
    $this->db->db_select('db_histo');

    $data = array(
      'Id' => $_POST['Id'],
      'IdComte' => $_POST['IdComte'],
      'Nom' => $_POST['Nom'],
      'CodeEtat' => $_POST['CodeEtat'],
      'Description' => $_POST['Description'],
    );

    $this->db->where('Id', $idTrame);
    $this->db->update('trames', $data);
  }

  public function getTrame($idTrame){
    $this->db->db_select('db_histo');

    $this->db->select("tr.*, CONCAT(ind.Prenom, ' ' , ind.Nom) as Createur");
    $this->db->from('trames tr');
    $this->db->join('db_indiv.individus ind', 'ind.Id = tr.IdCreateur');
    $this->db->where('tr.Id', $idTrame);
    $query = $this->db->get('trames');

    return $query->row();
  }

  public function getChapitres($idTrame){
    $this->db->db_select('db_histo');

    $this->db->where('IdTrame', $idTrame);
    $query = $this->db->get('chapitres');

    return $query->result();
  }

  public function addChapitre(){
    $this->db->db_select('db_histo');

    $data = array(
      'IdTrame' => $_POST['IdTrame'],
      'Numero' => $_POST['Numero'],
      'Texte' => $_POST['Texte'],
      'CodeEtat' => $_POST['CodeEtat'],
      'DateCreation' => date('Y-m-d H:i:s', time()),
    );

    $this->db->insert('chapitres', $data);
  }

  public function editChapitre($idChapitre){
    $this->db->db_select('db_histo');

    $data = array(
      'Numero' => $_POST['Numero'],
      'Texte' => $_POST['Texte'],
      'CodeEtat' => $_POST['CodeEtat'],
    );

    $this->db->where('Id', $idChapitre);
    $this->db->update('chapitres', $data);
  }

  public function ajax_getTrames($idComte){
    $this->db->db_select('db_histo');

    $this->db->where('IdComte',$idComte);
    $this->db->where('CodeEtat','ACTIF');
    $this->db->order_by('DateCreation','ASC');

    $query = $this->db->get('trames');

    return $query->result();
  }

  public function ajax_getComte($idComte){
    $this->db->db_select('db_histo');

    $this->db->where('Id',$idComte);

    $query = $this->db->get('comtes');

    return $query->row();
  }
  
}