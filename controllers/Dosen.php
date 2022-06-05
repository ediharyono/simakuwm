<?php 
//File in controller Dosen and save highchart in assets folder 
defined('BASEPATH') OR exit('No direct script access allowed');
class Dosen extends CI_Controller {

public function __construct()
	{
	parent::__construct();
	$this->load->model('dosen/dosen_model_datatable','sk');
	
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('token/token_model','tkn');
	}

public function index()
	{
		$this->session->set_userdata('judul_aplikasi','Dosen');
	$this->load->helper('url');
	$this->load->model('dosen/dosen_model_datatable','sk');	
	$data['center']='dosen/dosen_view_datatable';
	$this->load->view('dashboard/v_dashboard_uii',$data);
	}

public function ajax_list()
	{
	$this->load->model('dosen/dosen_model_datatable','sk');		
	$list = $this->sk->get_datatables();
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $sk) {
	$no++;
	$row = array();
	$row[] = $no;

	$row[] = $sk->id_pduii_dosen;
	$row[] = $sk->id_dosen;
	$row[] = $sk->nama_dosen;
	$row[] = $sk->nidn;
	$row[] = $sk->nip;	
	$row[] = $sk->jenis_kelamin;
	$row[] = $sk->id_agama;	
	$row[] = $sk->nama_agama;	
	
	$row[] = '<center><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_dosen('."'".$sk->id_pduii_dosen."'".')"><i class="glyphicon glyphicon-pencil"></i>  </a>
 
	  
	  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_dosen('."'".$sk->id_pduii_dosen."'".')"><i class="glyphicon glyphicon-trash"></i> </a>
 
	  </center>';
	  
	
	  
	$data[] = $row;
	}

	$output = array(
	"draw" => $_POST['draw'],
	"recordsTotal" => $this->sk->count_all(),
	"recordsFiltered" => $this->sk->count_filtered(),
	"data" => $data,
	);
	//output to json format
	echo json_encode($output);
	}

public function ajax_edit($id_pduii_dosen)
	{
	$this->load->model('dosen/dosen_model_datatable','sk');
	$data = $this->sk->get_by_id($id_pduii_dosen);
	echo json_encode($data);
	}

public function ajax_preview($id_pduii_dosen)
	{
	$this->load->model('dosen/dosen_model_datatable','sk');		
	$data = $this->sk->get_by_id($id_pduii_dosen);
	echo json_encode($data);
	}
public function ajax_add()
	{
		
	$this->load->library('Uuid');	
	$this->load->model('dosen/dosen_model_datatable','sk');	
	$data = array(

//============================================================================================//	
	
	'id_pduii_dosen' => $this->uuid->v4(),
	'id_dosen' => $this->input->post('id_dosen'),
	'nama_dosen' => $this->input->post('nama_dosen'),
	'nidn' => $this->input->post('nidn'),
	'nip' => $this->input->post('nip'),
	'jenis_kelamin' => $this->input->post('jenis_kelamin'),
	'id_agama' => $this->input->post('id_agama'),
	'nama_agama' => $this->input->post('nama_agama'),
	'tanggal_lahir' => $this->input->post('tanggal_lahir'),
	'id_status_aktif' => $this->input->post('id_status_aktif'),
	'nama_status_aktif' => $this->input->post('nama_status_aktif'),
	
//============================================================================================//	
 
	);
	$insert = $this->sk->save($data);
	echo json_encode(array("status" => TRUE));
	}

public function ajax_update()
	{
	$this->load->model('dosen/dosen_model_datatable','sk');	
	$data = array(
	
//============================================================================================//	
	'id_pduii_dosen' => $this->input->post('id_pduii_dosen'),
	'id_dosen' => $this->input->post('id_dosen'),
	'nama_dosen' => $this->input->post('nama_dosen'),
	'nidn' => $this->input->post('nidn'),
	'nip' => $this->input->post('nip'),
	'jenis_kelamin' => $this->input->post('jenis_kelamin'),
	'id_agama' => $this->input->post('id_agama'),
	'nama_agama' => $this->input->post('nama_agama'), 
	'tanggal_lahir' => $this->input->post('tanggal_lahir'), 
	'id_status_aktif' => $this->input->post('id_status_aktif'), 
	'nama_status_aktif' => $this->input->post('nama_status_aktif'), 
		
 //============================================================================================//

	);
	$this->sk->update(array('id_pduii_dosen' => $this->input->post('id_pduii_dosen')), $data);
	echo json_encode(array("status" => TRUE));
	}

public function ajax_delete($id_pduii_dosen)
	{
	$this->load->model('dosen/dosen_model_datatable','sk');		
	$this->sk->delete_by_id($id_pduii_dosen);
	echo json_encode(array("status" => TRUE));
	}

//chart =========================================
public function chart()
	{
	$this->load->helper('url');
	$this->load->model('dosen/dosen_model');
	$data['departments']=$this->dosen_model->get_chart_dosen();
	$data['center']='dosen/dosen_chart_view';
	$this->load->view('dashboard/v_dashboard_uii',$data);
	} 


//csv
public function csv()
	{		
	$this->load->helper('url');
	$data['center']='dosen/dosen_csv_view';
	$this->load->view('dashboard/v_dashboard_uii',$data);
	}

public function uploadData()
	{
	$this->load->helper('url');
	$this->load->model('dosen/dosen_model');
	$data['departments']=$this->dosen_model->uploadData_dosen();
	redirect('dosen');
    }	

//export ke dalam format excel

public function view_excel() 
	{
	$this->load->helper('url');
	$this->load->model('dosen/dosen_model');
	$data = array( 'title' => 'Data lulusan',
	'departments' => $this->dosen_model->get_dosen());
	$this->load->view('dosen/dosen_excel_view',$data);
    }

public function export_excel(){
	$this->load->helper('url');
	$this->load->model('dosen/dosen_model');
			   $data = array( 'title' => 'Laporan Data Siswa',
	'departments' => $this->dosen_model->get_dosen());
	$this->load->view('dosen/dosen_export_excel_view',$data);
	}

//print
public function cetak_fpdf()
	{
	$this->load->helper('url');
	$this->load->model('dosen/dosen_model');
	$data['departments']=$this->dosen_model->get_dosen();
	$this->load->view('dosen/dosen_fpdf_view',$data);
	}

public function cetak_fpdf_1($id)
	{
	$this->load->helper('url');
	$this->load->model('dosen/dosen_model');
	$data['departments']=$this->dosen_model->get_by_id($id);
	$this->load->view('dosen/dosen_fpdf_view',$data);
	}

public function cetak_mpdf()
	{	
		$this->load->library('M_pdf');
		$this->load->helper('url');
		$this->load->view('dosen/dosen_mpdf_view');
	}

public function cetak_mpdf_1()
	{	
		$this->load->library('M_pdf');
		$this->load->helper('url');
			$this->load->model('dosen/dosen_model');
			$data['departments']=$this->dosen_model->get_dosen();		
		$this->load->view('dosen/dosen_mpdf_view',$data);
	}



public function detail(string $id = ''):void
	{
	
	if ('' !== $id) {

		$this->session->set_userdata('induk_aplikasi','PERKULIAHAN');			
		$this->session->set_userdata('judul_aplikasi','Dosen');
		$this->session->set_userdata('id_sk',$id);		
		
		$this->load->helper('url');
	
		$this->load->model('dosen/dosen_model');
			$data = array( 
				'title' => 'Dosen',
				'departments' => $this->dosen_model->get_by_id($id),				
				'department' => $this->dosen_model->get_by_id($id)
			);
	
		$data['center']='dosen/dosen_view_detail';
		$this->load->view('dashboard/v_dashboard_uii',$data);										
			} else {
				redirect('dosen');
			}
	}	
	
function import_excel()
	{
			$this->load->helper('url');
			$this->load->library('Excel');
			$data['center']='dosen/dosen_excel_import';
			$this->load->view('dashboard/v_dashboard_uii',$data);
	}
	
public function import()
	{
		$this->load->helper('url');
		$this->load->model('dosen/dosen_model');
		$this->load->library('Uuid');
		$this->load->library('Excel');		
		
		if(isset($_FILES["file"]["name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
		 
				for($row=2; $row<=$highestRow; $row++)
				{
					$excel1 = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$excel2 = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$excel3 = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$excel4 = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$excel5 = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$excel6 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$excel7 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$excel8 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					
					$data[] = array(
 				
					 	'id_pduii_dosen' 		=> $this->uuid->v4(),				
						'id_dosen'          =>$excel1, 
						'nama_dosen'          =>$excel2,
						'nidn'         =>$excel3, 
						'nip'        =>$excel4, 
						'jenis_kelamin'      =>$excel5, 
						'id_agama'       =>$excel6, 
						'nama_agama'       =>$excel7, 
						'tanggal_lahir'       =>$excel8, 						
					);
				}
			}
			$this->dosen_model->insert($data);
		}	
	}
//

//
////////////////////////////////////////////////////////MYSQL KE FEEDER ///////////////////////////////////////////////////////
public function feeder()
	{
		$token = $this->tkn->token_feeder();	
		$manual = array();
		$query = $this->db->get('dosen');
		if( $query->num_rows() > 0) 
			{
			$result = $query->result(); //or $query->result_array() to get an array
			foreach( $result as $rows )
			{									
			$id_pduii_dosen = $rows->id_pduii_dosen;
			$manual = array('act'=>'InsertDosenPengajarKelasKuliah', 'token'=>$token,
					'record' => array(
						'id_dosen' => $rows->id_dosen,
						'nama_dosen' => $rows->nama_dosen,
						'nidn' => $rows->nidn,
						'nip' => $rows->nip,
						'jenis_kelamin' => $rows->jenis_kelamin,
						'id_agama' => $rows->id_agama,
						'nama_agama' => $rows->nama_agama,						
						'tanggal_lahir' => $rows->tanggal_lahir,						
				));
	
					//echo json_encode($manual,true);
					$data_manual = json_encode($manual,true);
					$ch = curl_init('http://192.168.30.99:8082/ws/live2.php');
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLINFO_HEADER_OUT, true);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data_manual);   
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_manual)));
						$result_profil = curl_exec($ch);
				
				echo $result_profil;
	
	
				//MENDAPATKAN KEMBALIANA ID MATAKLIAH
					$coba = json_decode($result_profil);
					$data_error = json_encode($coba->error_code,true);
					
					//echo $data_error;
					
					if ($data_error == '"0"')
							{
									$id_dosen= str_replace('"','',json_encode($coba->data->id_dosen,true));
									$this->load->model('dosen/dosen_model_datatable','sk');
									
									$data = array('id_dosen' => $id_dosen);
									$this->sk->update(array
											(
											'id_pduii_dosen' => $rows->id_pduii_dosen	
											), $data);											
							} 
					else if ($data_error== '400')
							{

 							}
	
				} 	
				curl_close($ch);				
			}	
 } 

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
///////////////////////////////////////////FUNGSI MENGHAPUS DATA YANG ADA DI FEEDER//////////////////////////////////////////////////
	
public function feeder_delete()
	{
		set_time_limit(0);
		$token = $this->tkn->token_feeder(); $query = $this->db->get('dosen');
		if( $query->num_rows() > 0) 
			{
			$manual = array(); $result = $query->result(); //or $query->result_array() to get an array
			foreach( $result as $rows )
			{			
			$manual = array('act'=>'DeletePengajarKelasKuliah', 'token'=>$token,
					'key' => array(
					'id_dosen' => $rows->id_dosen
					));
			$data_manual = json_encode($manual,true);
					$ch = curl_init('http://192.168.1.103:8082/ws/live2.php');
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLINFO_HEADER_OUT, true); curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data_manual); curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_manual))); $result_profil = curl_exec($ch);
					echo $result_profil;
				
					$coba = json_decode($result_profil);					
					$data_sync = json_encode($coba->error_code,true);

				curl_close($ch);
			} 	
				
		} 
	}  
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////DARI FEEDER KE MYSQL /////////////////////////////////////////
public function pduii()
{
		set_time_limit(0);
		$this->load->library('Uuid');
		$this->load->model('dosen/dosen_model_datatable','mk');
		$token = $this->tkn->token_feeder();
		$filter = '';
			$order = '';
			$limit = 2000;
			$offset = 0;
			$my_mk = array('act'=>'GetListDosen', 'token'=>$token, 'filter'=>$filter, 'order'=>$order, 'offset'=>$offset, 'limit'=>$limit);

			$ch = curl_init('http://192.168.30.99:8100/ws/live2.php'); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);curl_setopt($ch, CURLINFO_HEADER_OUT, true); curl_setopt($ch, CURLOPT_POST, true);

				$curl_mk = json_encode($my_mk);		
				curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_mk);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							'Content-Type: application/json',
							'Content-Length: ' . strlen($curl_mk))
							);				
				$result_profil = curl_exec($ch);	
					echo $result_profil;
				$coba = json_decode($result_profil);
				$data_sync = json_encode($coba->data,true);
				
		
		$array = json_decode($data_sync, true);
		foreach($array as $row) //Extract the Array Values by using Foreach Loop
          {
			$data = array(
				'id_pduii_dosen' => $this->uuid->v4(),

						'id_dosen'					=> $row["id_dosen"], 
						'nama_dosen'					=> $row["nama_dosen"], 
						'nidn'					=> $row["nidn"], 
						'nip'					=> $row["nip"], 
						'jenis_kelamin'					=> $row["jenis_kelamin"], 
						'id_agama'					=> $row["id_agama"], 
						'nama_agama'					=> $row["nama_agama"], 
						'tanggal_lahir'					=> $row["tanggal_lahir"], 
						'id_status_aktif'					=> $row["id_status_aktif"], 
						'nama_status_aktif'					=> $row["nama_status_aktif"], 						
				);
			
			$insert = $this->mk->replace($data);
			//echo json_encode(array("status" => TRUE));
			}
		curl_close($ch);
}
//


//			
}
?>
