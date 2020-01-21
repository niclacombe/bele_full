<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activites_model extends CI_Model {

	public function __construct()	{
		parent::__construct();
		$this->db->db_select('db_activ');

	}

	public function getLastActivites() {
		$date6m = date('Y-m-d', strtotime("last year"));

		$this->db->where('DateDebut >', $date6m);
		$this->db->order_by('DateDebut', 'desc');
		$query = $this->db->get('activites', 30);

		return $query->result();
	}

	public function addActivite(){

		$data = array(
            'Nom'        	=> $this->input->post('nom'),
            'Description'   => $this->input->post('description'),
            'Type'      	=> $this->input->post('typeActivite'),
            'DateDebut'     => $this->input->post('dateDebut'),
            'DateFin'    	=> $this->input->post('dateFin'),
        );

		$this->db->insert('activites',$data);
	}

	public function getSingleActivite($id){
		$this->db->where('Id', $id);
		$query = $this->db->get('activites');

		return $query->row();
	}

	public function updateActivite($id){
		$data = array(
            'Nom'        	=> $this->input->post('nom'),
            'Description'   => $this->input->post('description'),
            'Type'      	=> $this->input->post('typeActivite'),
            'DateDebut'     => $this->input->post('dateDebut'),
            'DateFin'    	=> $this->input->post('dateFin'),
        );

        $this->db->where('Id', $id);
        $this->db->update('activites', $data);

	}

	public function deleteActivite($id){
		$this->db->where('Id', $id);
        $this->db->delete('activites');
	}
}


/* End of file Activites_model.php */
/* Location: ./application/models/Activites_model.php */
?>