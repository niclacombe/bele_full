<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Histoire extends CI_Controller {

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -
   *    http://example.com/index.php/welcome/index
   *  - or -
   * Since this controller is set as the default controller in
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see https://codeigniter.com/user_guide/general/urls.html
   */


  public function index() {
    $data = array();
  }

  public function carte($json = false){
    $this->load->model('histoire_model');

    $data = array(
      'baronnies' => $this->histoire_model->getBaronnies(),
      'comtes' => $this->histoire_model->getComtes(),
      'duches' => $this->histoire_model->getDuches(),
      'royaumes' => $this->histoire_model->getRoyaumes(),
      'jsonSuccess' => $json,
    );

    $this->load->view('template/header', $data);
    $this->load->view('histoire/carte', $data);
    $this->load->view('template/footer',$data);
  }

  public function generateMapConfig(){
    $this->load->model('histoire_model');

    if( $_SESSION['infoUser']->NiveauAcces < 6 ){
      return;
    }

    $royaumes = $this->histoire_model->getRoyaumes();

    $data = array();    

    $baronnies = $this->histoire_model->getBaronnies();
    $comtes = $this->histoire_model->getComtes();

    foreach ($baronnies as $b) {
      foreach ($comtes as $c) {
        if($c->Id == $b->IdComte){
          $comte = $c;
        } 
      }

      $data['baronnies'][$b->Cadastre] = array(
        'Id' => $b->Id,
        'Cadastre' => $b->Cadastre,
        'Nom' => $b->Nom,
        'IdComte' => $b->IdComte,
        'CodeEtat' => $b->CodeEtat,
        'Couleur' => $comte->Couleur,
      );
    }

    $file = '/webdev/bele/bdmvc/assets/map/config.json';

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    file_put_contents($file, $json);    

    redirect('Histoire/carte/true', 'refresh');
  }

  public function editBaronnie($idBaronnie){
    $this->load->model('histoire_model');

    $this->histoire_model->editBaronnie($idBaronnie);

    redirect('Histoire/carte/', 'refresh');
  }

  public function editComte($idComte){
    $this->load->model('histoire_model');

    $this->histoire_model->editComte($idComte);

    redirect('Histoire/carte/', 'refresh');
  }

  public function editDuche($codeDuche){
    $this->load->model('histoire_model');

    $this->histoire_model->editDuche($codeDuche);

    redirect('Histoire/carte/', 'refresh');
  }

  public function trames($json = false){
    $this->load->model('histoire_model');

    $data = array(
      'comtes' => $this->histoire_model->getComtes(),
      'trames' => $this->histoire_model->getTrames(),

    );

    $this->load->view('template/header', $data);
    $this->load->view('histoire/trames', $data);
    $this->load->view('template/footer',$data);
  }

  public function addTrame(){
    $this->load->model('histoire_model');

    $this->load->library('form_validation');

    $this->form_validation->set_rules('Id', 'Identification', 'required|numeric');
    $this->form_validation->set_rules('Nom', 'Nom', 'required|is_unique[trames.Nom]|alpha_numeric_spaces');
    $this->form_validation->set_rules('IdComte', 'Comté', 'required|min_length[1]');
    $this->form_validation->set_rules('Description', 'Description', 'required|alpha_numeric_spaces');

    if ($this->form_validation->run() == FALSE){

      $this->load->model('histoire_model');

      $this->histoire_model->addTrame();

      $data = array(
        'comtes' => $this->histoire_model->getComtes(),
        'trames' => $this->histoire_model->getTrames(),
      );

      $this->load->view('template/header', $data);
      $this->load->view('histoire/trames', $data);
      $this->load->view('template/footer',$data);
      
    }
    else{
      $this->histoire_model->addTrame();
    }
    

    redirect('Histoire/trames/', 'refresh');
  }

  public function editTrame($idTrame){
    $this->load->model('histoire_model');

    $this->histoire_model->editTrame($idTrame);



    redirect('Histoire/trames/', 'refresh');
  }

}

?>