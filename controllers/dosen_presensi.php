<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dosen_presensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
		$this->load->helper('url');
		
			ini_set('memory_limit', '-1');
			set_time_limit(0);
			
		if (!$this->session->userdata('email_dosen')) {
				$this->session->set_flashdata('not-login', 'Gagal!');
				  redirect('welcome');
				}
	}

public function index()
	{
		$this->load->helper('url');		
		$this->load->view('dosen/dashboard/presensi_header');
		$this->load->view('dosen/dashboard/presensi_end');
		
			$this->load->model('dosen/rtrabm_model_datatable','sk');
			
			$data['center']='dosen/content/presensi_dosen_view_datatable';			
		$this->load->view('dosen/dashboard/presensi_v_dashboard_dosen',$data);

	}
	
public function presensi()
	{
		$this->load->helper('url');		
		$this->load->view('dosen/dashboard/presensi_header');
		$this->load->view('dosen/dashboard/presensi_end');
		
			$this->load->model('dosen/rtrabm_model_datatable','sk');
			
			$data['center']='dosen/content/presensi_dosen_view_datatable';			
		$this->load->view('dosen/dashboard/presensi_v_dashboard_dosen',$data);

	}	
		

public function dosen_presensi_hadir_semua()
	{
//	$sql = 'UPDATE rtrabm SET abhdrtrabm=1, abskttrabm=0, abijntrabm =0, abalptrabm =0, WHERE 1';		
	$data = array(
        'abhdrtrabm' => 0,
        'abskttrabm' => 0,
        'abijntrabm' => 0,
        'abijntrabm' => 0,		
        'abalptrabm' => 1				
	);
					$this->db->where('thsmstrabm',$this->session->userdata('thsms'));						
					$this->db->like('kdpsttrabm',$this->session->userdata('kdpstmsmks'));
					$this->db->like('kdkmktrabm',$this->session->userdata('kdkmkmsmks'));
					$this->db->where('kelastrabm',$this->session->userdata('kelasmsmks'));
					$this->db->where('ttmketrabm',$this->session->userdata('ttmketrabd')); 
					
/* 	$this->db->where('thsmstrabm',$this->session->userdata('thsmstrabm'));
	$this->db->where('kdpsttrabm',$this->session->userdata('kdpst'));
	$this->db->where('ttmketrabm',$this->session->userdata('ttmke'));	
	$this->db->where('kelastrabm',$this->session->userdata('kelas'));	
	$this->db->like('kdkmktrabm',$this->session->userdata('kdkmk')); */
	$this->db->update('rtrabm', $data);
 
	}  
	
public function formulir() {
	$this->load->helper('url');		
	$this->load->model('dosen/rtrabm_model');
	$data = array( 'title' => 'Data lulusan',
	'departments' => $this->rtrabm_model->get_rtrabm_mahasiswa_presensi());
	$this->load->view('dosen/content/presensi',$data);

}
public function form()
	{
 		$this->db = $this->load->database('widyama2_simakol', TRUE);
		$this->db->from('rtrabm');
			$this->db->where('thsmstrabm',$this->session->userdata('thsms'));	
			$this->db->like('kdpsttrabm',$this->session->userdata('kdpstmsmks'));
			$this->db->like('kdkmktrabm',$this->session->userdata('kdkmkmsmks'));
			$this->db->where('kelastrabm',$this->session->userdata('kelasmsmks'));
			$this->db->where('ttmketrabm',$this->session->userdata('ttmketrabd')); 
		$query = $this->db->get();

		$results = $query->result();
			echo "<table>";
			foreach ($results as $row) {
				echo "<tr>";	
				echo "<td>"."no"."</td>";	
				echo "<td>".$row->nimhstrabm; echo "</td>";
				echo "<td>".$row->nmmhstrabm; echo "</td>";	
				echo '<td><input type="radio" id="hadir" name="check_list[]" alt="Checkbox" value="merah"></td>';		
				echo '<td><input type="radio" id="ijin" name="check_list[]" alt="Checkbox" value="merah"></td>';						
				echo "</tr>";	
			}
			echo "</table>";

			echo 'Jumlah Peserta: ' . count($results);

		$data = $query->result_array();

		$this->load->view('dosen/content/presensi',$data);
	}


}
