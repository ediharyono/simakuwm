<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dosen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
		$this->load->helper('url');
		
		if (!$this->session->userdata('email_dosen')) {
				$this->session->set_flashdata('not-login', 'Gagal!');
				  redirect('welcome');
				}



    }



	
    public function index()
    {

			
			ini_set('memory_limit', '-1');
			set_time_limit(0);
			
	$this->load->helper('url');

	/////
			$this->load->model('dosen/customers_model','customers');

			
		/// membuat list form ///
		$countries = $this->customers->get_list_thsms_index();
 
		$opt = array('' => 'Pilih Semester');
					foreach ($countries as $country) {
						$opt[$country] = $country;
					}
		$data1['form_thsms'] = form_dropdown('',$opt,'','id="thsms" name="thsms" class="form-control"');
	
	/////
			$this->load->model('dosen/rmsmks_model');
			$data = array( 'title' => 'Classroom',
			'list_kelas' => $this->rmsmks_model->get_rmsmks(),
				'list_asisten' => $this->rmsmks_model->get_rmsmks_asisten());
        $this->load->view('dosen/dashboard/header');
        $this->load->view('dosen/dashboard/content',$data1);
        $this->load->view('dosen/dashboard/end');	 
			$this->load->view('dosen/content/classroom',$data);		 
        $this->load->view('dosen/dashboard/footer');
        $this->load->view('dosen/dashboard/quickpanel');		
		
				
    }



 public function portofolio()
    {
		$this->load->helper('url');
			$this->load->model('dosen/rmsdos_model');
			$data = array( 'title' => 'Data lulusan',
			'portofolio' => $this->rmsdos_model->get_rmsdos());
		$this->load->view('dosen/dashboard/header');
		$this->load->view('dosen/content/profile',$data);	
        $this->load->view('dosen/dashboard/footer');	
        $this->load->view('dosen/dashboard/end');	
	}	

 public function password()
    {
		$this->load->view('dosen/dashboard/header');
		$this->load->view('dosen/content/password');	
        $this->load->view('dosen/dashboard/footer');	
        $this->load->view('dosen/dashboard/end');	
	}		
	
public function update_passwordosen_simak()
{
	{
		$this->load->model('dosen/portofolio_model_datatable', 'sk');
					
		$data = array(
		'password' => md5(trim($this->input->post('password'))),
		//'password' => password_hash(trim($this->input->post('password')), PASSWORD_DEFAULT),

		);
		$this->sk->update(array('nip' => $this->session->userdata('id')), $data);
		echo json_encode(array("status" => TRUE));
		echo json_encode($data);
	}		
}

public function generate()
	{
	set_time_limit(0);	
	$this->load->model('users/users_model','usr');	
	$this->load->model('guru/guru_model_datatable','gurus');
	
	$list = $this->usr->get_users_dosen();
		foreach ($list as $usr) {
				$data = array(
						'nip' => trim($usr->usrid),				
						'nama_guru' => $usr->full_name,
						'full_name' => $usr->full_name,						
						'password' => password_hash(trim($usr->passwd), PASSWORD_DEFAULT),						
						'email' => trim($usr->usrid)."@widyamataram.ac.id",
						'passwd' => $usr->passwd,	
						'nidn' => trim($usr->usrid),							
						'type_user' => $usr->type_user,	
						
				);
	$insert = $this->gurus->replace($data);	
			//echo $usr->usrid."@widyamataram.ac.id";
		}			
	}
//////////////////////////////////////////
//////PRIVATE CHAT GROUP KEREN BRO!!!!!///
//////////////////////////////////////////
public function chat_private()
    {
	 date_default_timezone_set('UTC');
			$this->load->model('dosen/chat_model');
			$this->load->model('dosen/chat_group_model');
			$this->load->model('dosen/rtrkrs_model');	
				$data = array( 'title' => 'Chat E-Learning',
				'departments' => $this->chat_model->get_chat(),
				'listpartner' => $this->rtrkrs_model->get_rtrkrs_chat(),	
				'chat_groups' => $this->chat_group_model->get_chat_group()
				);				
		$this->load->view('dosen/dashboard/header');
		$this->load->view('dosen/content/chat_private',$data);	
        $this->load->view('dosen/dashboard/footer');	
        $this->load->view('dosen/dashboard/end');
	}	
/* public function chat_groupxxxx()
    {
	 date_default_timezone_set('UTC');
			$this->load->model('dosen/chat_model');
			$this->load->model('dosen/chat_group_model');
			$this->load->model('dosen/rtrkrs_model');
	
				$data = array( 'title' => 'Chat E-Learning',
				'departments' => $this->chat_model->get_chat(),
				'listpartner' => $this->rtrkrs_model->get_rtrkrs_chat(),	
				'chat_groups' => $this->chat_group_model->get_chat_group() 
				);		 
		$this->load->view('dosen/content/chat_group',$data);	
	}		 */
/////////////////////////////////////	
//PRIVATE CHAT GROUP KEREN BRO!!!!!//	
/////////////////////////////////////
public function chat()
    {
	$this->load->helper('url');
	$this->load->model('dosen/chat_model');
	$this->load->model('dosen/chat_group_model');
	$this->load->model('dosen/rtrkrs_model');
	$data = array(  
	'listpartner' => $this->rtrkrs_model->get_rtrkrs_chat(),
	'departments' => $this->chat_model->get_chat(),
	'chat_groups' => $this->chat_group_model->get_chat_group(),	
	);
        $this->load->view('dosen/dashboard/header');
	    $this->load->view('dosen/content/chat_view',$data);	
        $this->load->view('dosen/dashboard/footer');
        $this->load->view('dosen/dashboard/quickpanel');		
        $this->load->view('dosen/dashboard/end');						
    }
public function chat_ajax_add()
	{
	date_default_timezone_set('UTC');		
	$this->load->library('Uuid');	
	$this->load->model('dosen/chat_model_datatable','sk');	
	$data = array(
//============================================================================================//		
	'chat_id' => $this->uuid->v4(),	
	'partner_id_student' =>rawurldecode($this->session->userdata('nim_partner_chat')),
	'partner_id_teacher' => $this->session->userdata('id'),
	'sender_id' =>  $this->session->userdata('id'),
	'sender_name' =>  $this->session->userdata('id'),
	'receiver_id' => $this->input->post('message_chat'),
	'message' => $this->input->post('message_chat'),
	'created_time' =>  date('Y-m-d H:i:s'),
	'show_sender' => '1',
	'show_receiver' => '0',
	'files' => '',
//============================================================================================//	
	);
	$insert = $this->sk->save($data);
	echo json_encode(array("status" => TRUE));
	}
public function change_session_partner_chat($nim_partner_chat,$nama_partner_chat)
    {
		$data = [				'nim_partner_chat' 	=> rawurldecode($nim_partner_chat),
								'nama_partner_chat' 	=> rawurldecode($nama_partner_chat),
				];
		$this->session->set_userdata($data);				
	echo json_encode($data);
	}
public function ajax_edit($chat_group_id)
	{
	$this->load->model('dosen/chat_group_model_datatable','sk');
	$data = $this->sk->get_by_id($chat_group_id);
	echo json_encode($data);
	}	
public function ajax_add()
	{
	$thsmstrabd = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');	
	$this->load->library('Uuid');	
	$this->load->model('dosen/chat_group_model_datatable','sk');	
	$data = array(
	'chat_group_id' => $this->uuid->v4(),	
	'materi_id' => $this->input->post('materi_id'),
	'sender_id' => $this->session->userdata('id'),
	'thsms_chat_group' => $thsmstrabd,
	'kdpst_chat_group' => $this->session->userdata('kdpstmsmks'),
	'kdkmk_chat_group' => $this->session->userdata('kdkmkmsmks'),
	'kelas_chat_group' => $this->session->userdata('kelasmsmks'),
	'ttmke_chat_group' => $this->session->userdata('ttmketrabd'),
	'message' => $this->input->post('message'),
	'created_time' => date('Y-m-d H:i:s'),
	'show_sender' => '1',
	'show_receiver' => '1',
	'files' => '', 
	);
	$insert = $this->sk->save($data);
	echo json_encode(array("status" => TRUE));
	}
public function ajax_update()
	{
	$thsmstrabd = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');	
	$this->load->model('dosen/chat_group_model_datatable','sk');	
	$data = array(
	'materi_id' => $this->input->post('materi_id'),
	'sender_id' => $this->session->userdata('id'),
	'thsms_chat_group' => $thsmstrabd,
	'kdpst_chat_group' => $this->session->userdata('kdpstmsmks'),
	'kdkmk_chat_group' => $this->session->userdata('kdkmkmsmks'),
	'kelas_chat_group' => $this->session->userdata('kelasmsmks'),
	'ttmke_chat_group' => $this->session->userdata('ttmketrabd'),
	'message' => $this->input->post('message'),
	'created_time' => $this->input->post('created_time'),
	'show_sender' => '1',
	'show_receiver' => '1',
	'files' => '', 
	);
	$this->sk->update(array('chat_group_id' => $this->input->post('chat_group_id')), $data);
	echo json_encode(array("status" => TRUE));
	}

public function ajax_delete($chat_group_id)
	{
	$this->load->model('dosen/chat_group_model_datatable','sk');		
	$this->sk->delete_by_id($chat_group_id);
	echo json_encode(array("status" => TRUE));
	}
///////////////////////////////////END_CHAT///////////////////////////////





///disqus
public function ajax_delete_disqus($chat)
	{
	$this->load->model('dosen/chat_diqus_model_datatable','sk');		
	$this->sk->delete_by_id($chat);
	echo json_encode(array("status" => TRUE));
	}

//
function ajax_disqus($chat_disqus_id)
	{
	$this->load->model('dosen/chat_diqus_model_datatable','sk');
	$data = $this->sk->get_by_id($chat_disqus_id);
	echo json_encode($data);
	}

function ajax_disqus_add()
	{
  										
	$this->load->library('Uuid');	
	$this->load->model('dosen/chat_diqus_model_datatable','sk');	
	$data = array(
	'chat_disqus_id' => $this->uuid->v4(),		
	'chat_group_id' => $this->input->post('chat_disqus_id'),
	'sender_name' => 'Dosen',
	'sender_id' => $this->session->userdata('id'),
	'message' => $this->input->post('message'),
	'created_time' => date('Y-m-d H:i:s'),
	'show_sender' => '1',
	'show_receiver' => '1',
	'files' => '', 
	);
	$insert = $this->sk->save($data);
	echo json_encode(array("status" => TRUE));
	}
///////////////////
	
//JADWAL///
public function jadwal()
    {
		$this->load->helper('url');
		$this->load->model('dosen/rmsmks_model');
		$data = array( 'title' => 'Jadwal Kuliah',
		'departments' => $this->rmsmks_model->get_rmsmks()); 
		$this->load->view('dosen/content/rmsmks_excel_view',$data);	
    }
public function change_session($thakdmsmks,$smakdmsmks,$kdpstmsmks,$kdkmkmsmks,$kelasmsmks)
    {
		$data = [				'thakdmsmks' 	=> $thakdmsmks,
								'smakdmsmks'   	=> $smakdmsmks,
								'kdpstmsmks'    => $kdpstmsmks,
								'kdkmkmsmks'    => $kdkmkmsmks,						 
								'kelasmsmks'    => $kelasmsmks,								
				];
		$this->session->set_userdata($data);				
		echo json_encode($data);
	}	
public function change_session_thsms($thsms)
    {
		$data = ['thsms'=> $thsms,								
				];
		$this->session->set_userdata($data);				
			echo json_encode($data);
		
			//echo json_encode(array("status" => TRUE));
	}		
public function pertemuan_kelas()
    {
		ini_set('display_errors','off');
		$this->load->helper('url');
		$this->load->model('dosen/rtrabd_model');
///grafik progress///		
		$this->load->model('dosen/rtrkrs_model_datatable');
///grafik progress///		

		$this->load->view('dosen/dashboard/header');									
		$this->load->view('dosen/dashboard/header_classroom');						
		$data = array( 
			'pertemuan_kelas' => $this->rtrabd_model->get_rtrabd(),
 
			);
//'jumlah_peserta' => $this->rtrkrs_model_datatable->count_all()			
	
		$this->load->view('dosen/content/pertemuan_kelas',$data);						


	    $this->load->view('dosen/dashboard/end');	
	    $this->load->view('dosen/dashboard/footer');			
	}
public function change_session_pertemuan($ttmketrabd)
    {
		$data = [				'ttmketrabd' 	=> $ttmketrabd,
				];
		$this->session->set_userdata($data);				
		echo json_encode($data);
	}

public function peserta_kelas()
    {
		
		
		$this->load->model('dosen/rtrkrs_model_datatable'); 		
		$this->load->view('dosen/dashboard/header');		
		$this->load->view('dosen/dashboard/header_classroom');			
		$this->load->view('dosen/dashboard/end');
		$data['center']='dosen/content/rtrkrs_view_datatable';
		$this->load->view('dosen/dashboard/v_dashboard_dosen',$data);
		    			
		$this->load->view('dosen/dashboard/footer');			
	}
public function peserta_ajax_list()
	{
	$this->load->model('dosen/rtrkrs_model_datatable','sk');		
	$list = $this->sk->get_datatables();
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $sk) {
	$no++;
	$row = array();
	
	$row[] = $no;
	$row[] = $sk->nimhstrkrs;	
	$row[] = $sk->nmmhstrkrs;	
	
	$row[] = $sk->kdkmktrkrs;	
	$row[] = $sk->nmkmktrkrs;
	$row[] = $sk->kelastrkrs;		
	
	$row[] = '<a href="javascript: void(0)" onclick="add_chat()">Chat</a>';	
	  
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
function kuesioner()
{
	///kuesioner///
			$this->load->model('dosen/kuesioner_model');
			$this->load->model('dosen/kuesioner_isian_model');
			
			$data1 = array( 'title' => 'Kuesioner',
		 
	        'departments'=>$this->kuesioner_isian_model->get_chart_kuesioner_isian_dosen(),
			'jumlah' => $this->kuesioner_model->count_filtered(),
			
			'pertanyaan_dosen_rel' => $this->kuesioner_model->get_kuesioner_dosen_rel(),
			'pertanyaan_dosen_res' => $this->kuesioner_model->get_kuesioner_dosen_res(),	 		
			'pertanyaan_dosen_emp' => $this->kuesioner_model->get_kuesioner_dosen_emp(),
			'pertanyaan_dosen_asr' => $this->kuesioner_model->get_kuesioner_dosen_asr(),
			'pertanyaan_dosen_tan' => $this->kuesioner_model->get_kuesioner_dosen_tan(),
			
			);	

//echo json_encode($data1);
		$this->load->view('dosen/dashboard/header');									
		$this->load->view('dosen/dashboard/header_classroom');			
        $this->load->view('dosen/content/kuesioner_mahasiswa_kelas',$data1);	
	    $this->load->view('dosen/dashboard/end');	
	    $this->load->view('dosen/dashboard/footer');			
			
}	
public function e_learning()
    {
	$this->load->helper('url');
	$this->load->model('dosen/chat_model');
	$this->load->model('dosen/chat_group_model');
	$this->load->model('dosen/rtrabd_model');
	$this->load->model('dosen/download_model');	
	$this->load->model('dosen/rtrkrs_model');
		
	$data = array( 'title' => 'Chat E-Learning',
	'departments' => $this->chat_model->get_chat(),
	'listpartner' => $this->rtrkrs_model->get_rtrkrs_chat(),	
	'chat_groups' => $this->chat_group_model->get_chat_group(),
	'pertemuan_ke' => $this->rtrabd_model->get_rtrabd_ttmke(),
	'materies' => $this->download_model->get_download()
	);

        $this->load->view('dosen/dashboard/header');
			$this->load->view('dosen/content/materi_pertemuan',$data);	
	        $this->load->view('dosen/content/materi',$data);				
	        $this->load->view('dosen/content/chat_view',$data);	

        $this->load->view('dosen/dashboard/footer');
        $this->load->view('dosen/dashboard/end');						
    }	
		

public function do_upload()
        {
                $config['upload_path']          = './files/';
                $config['allowed_types']        = 'pdf|docx|doc|pptx';
                $config['max_size']             = 20000;

                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('userfile'))
                {
                        $error = array('error' => $this->upload->display_errors());
                        echo "ERROR DOSEN DO UPLOAD";
                }
                else
                {
                        //$data_1 = array('upload_data' => $this->upload->data());
                        
						$filee = $this->upload->data('file_name');
						
						
					$data = [
			
				//'id_download' => $this->uuid->v4(),
					//'id_download' => $this->session->userdata('thsms'),
					'judul' => $this->input->post('judul'),
					'nama_file' => $filee,
					'tgl_posting' => date('Y-m-d H:i:s'),
					'txt_tglposting' => date('Y-m-d H:i:s'),
					'id_session' => $this->session->userdata('id'),
					'id_level' => 'dosen',
					'id_kdpti' => '051008',
					'id_kdpst' => $this->session->userdata('kdpstmsmks'),
					'id_kdjen' => '',
					'id_thsms' => $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks'),
					'id_kdkmk' => $this->session->userdata('kdkmkmsmks'),
					'id_kelas' => $this->session->userdata('kelasmsmks'),
					'id_file' => '',
					];

					$this->db->insert('download', $data);
					$this->session->set_flashdata('success-reg', 'Berhasil!');
					redirect(base_url('dosen/pertemuan_kelas'));
			
						//$this->load->view('upload_success', $data_1);
						
						
						
                }
        }
		

public function add_materi()
    {
			$this->load->view('dosen/dashboard/header');					
			$this->load->view('dosen/dashboard/header_classroom');			
            $this->load->view('dosen/content/add_materi',array('error' => ' ' ));
			$this->load->view('dosen/dashboard/footer');
			$this->load->view('dosen/dashboard/quickpanel');		
			$this->load->view('dosen/dashboard/end');				
    }

public function download() 
	{
	$this->load->helper('url');
	$this->load->model('dosen/download_model');
	$data = array('departments' => $this->download_model->get_download());
	$this->load->view('dosen/content/download_excel_view',$data);
    }	

public function add_pertemuan()
    {
			
			$this->load->view('dosen/dashboard/header');			
            $this->load->view('dosen/content/add_pertemuan');
			$this->load->view('dosen/dashboard/footer');
			$this->load->view('dosen/dashboard/quickpanel');		
			$this->load->view('dosen/dashboard/end');				
    } 
		
	
//============================================================================================//	
	
	function add_pertemuanxxx()
	{
		$this->session->set_userdata('judul_aplikasi','rtrabd');
		$this->load->helper('url');
		$this->load->model('dosen/pertemuan_model_datatable','sk');	
		$data['center']='dosen/content/pertemuan_view_datatable';
		$this->load->view('dashboard/v_dashboard_uii',$data);
	}
	
function ajax_list_pertemuan()
	{
	$this->load->model('dosen/pertemuan_model_datatable','sk');		
	$list = $this->sk->get_datatables();
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $sk) {
	$no++;
	$row = array();
	$row[] = $no;

	$row[] = $sk->kdpsttrabd;
	$row[] = $sk->kdkmktrabd;	
	$row[] = $sk->kelastrabd;
	$row[] = $sk->dosajtrabd;
	$row[] = $sk->ttmketrabd;
	$row[] = $sk->mate1trabd;	
	$row[] = $sk->mate2trabd;	
	
	$row[] = '<center><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_rtrabd('."'".$sk->kdjentrabd."'".')"><i class="glyphicon glyphicon-pencil"></i>  </a>
	  <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="PDF" onclick="preview_sdm('."'".$sk->kdjentrabd."'".')"><i class="glyphicon glyphicon-file"></i>  </a> 
	  <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="CETAK" onclick="cetak_sk('."'".$sk->kdjentrabd."'".')"><i class="glyphicon glyphicon-print"></i>  </a> 
	  
	  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_rtrabd('."'".$sk->kdjentrabd."'".')"><i class="glyphicon glyphicon-trash"></i> </a>

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
	

	
	
	function ajax_add_pertemuan()
	
		{
		
		$thsmstrabm = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');	
		
		$this->load->model('dosen/Pertemuan_model_datatable','sd');	
		$data = array(	
			'kdptitrabd' => '051008',	
			'kdpsttrabd' => $this->session->userdata('kdpstmsmks'),
			'kdjentrabd' => 'C',
			'thsmstrabd' => $thsmstrabm,
			'ttmketrabd' => $this->input->post('ttmketrabd'),
			'kdkmktrabd' => $this->session->userdata('kdkmkmsmks'),
			'nodostrabd' => $this->session->userdata('id'),
			'noasdtrabd' => $this->session->userdata('id'),
			'kelastrabd' => $this->session->userdata('kelasmsmks'),
			'tgkultrabd' => $this->input->post('tgkultrabd'),
			'time1trabd' => $this->input->post('time1trabd'),
			'time2trabd' => $this->input->post('time2trabd'),
			'waktutrabd' => '',
			'matertrabd' => '',
			'mate1trabd' => $this->input->post('mate1trabd'),
			'mate2trabd' => $this->input->post('mate2trabd'),
			'mhshdtrabd' => '',
			'dosajtrabd' => $this->session->userdata('id'),
		);
		$insert = $this->sd->save($data);
		//echo json_encode(array("status" => TRUE));
		
		$this->load->model('dosen/Pertemuan_model_datatable','sm');
		$data1 = array('krs_peserta' => $this->sm->get_peserta_krs());
		
		$thsmstrkrs = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');
		
		foreach($data1['krs_peserta'] as $krs) {
			
				$datax = array('kdptitrabm' => '051008',	
						'kdpsttrabm' => $this->session->userdata('kdpstmsmks'),
						'kdjentrabm' => 'C',
						'thsmstrabm' => $thsmstrkrs,
						'ttmketrabm' => $this->input->post('ttmketrabd'),
						'nimhstrabm' =>  $krs->nimhstrkrs,
						'nmmhstrabm' =>  $krs->nmmhstrkrs,
						'kdkmktrabm' => $this->session->userdata('kdkmkmsmks'),
						'nodostrabm' => $this->session->userdata('id'),
						'noasdtrabm' => $this->session->userdata('id'),
						'kelastrabm' => $this->session->userdata('kelasmsmks'),
						'tgkultrabm' => $this->input->post('tgkultrabd'),
						'abhdrtrabm' => '0',
						'abskttrabm' => '0',
						'abijntrabm' => '0',
						'abalptrabm' => '0',
						'dosajtrabm' => '0',
						'presntrabm' => '0');		
				$this->db->insert('rtrabm', $datax); 		
			}
		 
			
		//$this->db->insert_batch('rtrabm', $datax); 
		echo json_encode(array("status" => TRUE));
	}
	
//////
/* function ajax_add_krs_listx()
    {
		$this->load->model('dosen/Pertemuan_model_datatable','sm');

		$list = $this->sm->get_peserta_krs();
				$data1 = array();
				foreach ($list as $sm) 
				{
					$row = array();
					$row[] = $sm->nimhstrkrs;
					$row[] = $sm->nmmhstrkrs;		
				}
				
				$data1[] = $row;	 
						$output = array(
						"data" => $data1,
						);
				//output to json format
		echo json_encode($output);
	} */
//////

/* public function dull()
	{
	$this->load->helper('url');
		$this->load->model('dosen/Pertemuan_model_datatable','sm');
		$data = array('krs_peserta' => $this->sm->get_peserta_krs());
		
		$thsmstrkrs = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');
		
		$datax = array();
		foreach($data['krs_peserta'] as $krs) {
			
			$datax = 	
				array('kdptitrabm' => '051008',	
						'kdpsttrabm' => $this->session->userdata('kdpstmsmks'),
						'kdjentrabm' => 'C',
						'thsmstrabm' => $thsmstrkrs,
						'ttmketrabm' => '14',
						'nimhstrabm' =>  $krs->nimhstrkrs,
						'nmmhstrabm' =>  $krs->nmmhstrkrs,
						'kdkmktrabm' => $this->session->userdata('kdkmkmsmks'),
						'nodostrabm' => $this->session->userdata('id'),
						'noasdtrabm' => $this->session->userdata('id'),
						'kelastrabm' => $this->session->userdata('kelasmsmks'),
						'tgkultrabm' => '2021-00-00',
						'abhdrtrabm' => '0',
						'abskttrabm' => '0',
						'abijntrabm' => '0',
						'abalptrabm' => '0',
						'dosajtrabm' => '0',
						'presntrabm' => '0');	
						
			}
			
			$this->db->insert('rtrabm', $datax);
		echo json_encode($datax);	
    } */
	
//////
 
    private function _uploadImage()
    {
        $config['upload_path'] = './assets/materi_video';
        $config['allowed_types'] = 'mp4|mkv';
        $config['file_name'] = $this->product_id;
        $config['overwrite'] = true;
        $config['max_size'] = 0; // 1MB
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('image')) {
            return $this->upload->data("file_name");
        }

        return "default.mp4";
    }
	
	public function pertemuan()
	{
		$this->load->model('dosen/customers_model','customers');
			ini_set('memory_limit', '-1');
			set_time_limit(0);
			
		/// membuat list form ///
		$countries = $this->customers->get_list_thsms();
 
		$opt = array('' => 'Semua Tahun');
					foreach ($countries as $country) {
						$opt[$country] = $country;
					}
		$data['form_thsms'] = form_dropdown('',$opt,'','id="thsmstrabd" name="thsmstrabd" class="form-control"');


		
		/////////////////////////
		$this->load->view('dosen/dashboard/header');						
	    $this->load->view('dosen/dashboard/end');		
		//$this->load->view('customers_view_datatable', $data);		
		$data['center']='dosen/content/customers_view_datatable';
		$this->load->view('dosen/dashboard/v_dashboard_dosen',$data);
				$this->load->view('dosen/dashboard/footer');	
	}
	//////////////membuat list kdkmk ///////////////////////////////////////////// belum beres ///////////////
	function get_kdpst($thsmstrabd)
    {	
		$this->load->model('dosen/model_list_bertingkat');
        $data=$this->model_list_bertingkat->Kode_program_studi($thsmstrabd);
        echo json_encode($data);
    }

	function get_kdkmk()
    {
		$this->load->model('dosen/model_list_bertingkat');
        $kdpsttrabd=$this->input->post('kdpsttrabd');
        $data=$this->model_list_bertingkat->Kode_matakuliah($kdpsttrabd);
        echo json_encode($data);
    }
	//////////////membuat list kdkmk ///////////////////////////////////////////// belum beres ///////////////
	
	function classroom()
    {
		$this->load->helper('url');
			$this->load->model('dosen/rmsmks_model');
			$data = array( 'title' => 'Data lulusan',
			'list_kelas' => $this->rmsmks_model->get_rmsmks());
    	
		$this->load->view('dosen/dashboard/header');	
		$this->load->view('dosen/content/classroom',$data);			
	    $this->load->view('dosen/dashboard/end');
		$this->load->view('dosen/dashboard/footer');
	}
	public function ajax_list()
	{
		$this->load->model('dosen/customers_model','customers');
		$list = $this->customers->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $customers) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $customers->thsmstrabd;
			$row[] = $customers->kdpsttrabd;
			$row[] = $customers->kdkmktrabd;
			$row[] = $customers->kelastrabd;
			$row[] = $customers->ttmketrabd;			
			$row[] = $customers->mate1trabd;
			$row[] = $customers->mate2trabd;
			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->customers->count_all(),
						"recordsFiltered" => $this->customers->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

//menambahkan prosentase//
public function presensi_prosentase()
	{
		$this->load->helper('url');		
		$this->load->view('dosen/dashboard/presensi_header');
		$this->load->view('dosen/dashboard/presensi_end');

			$this->load->model('dosen/rtrkrs_model','trkrs');		
			$this->load->model('dosen/rtrabd_model','trabd');
			$this->load->model('dosen/rtrabm_model','trabm');

			$data['mahasiswa_peserta'] = $this->trkrs->get_rtrkrs_chat();
			$data['mahasiswa_pertemuan'] = $this->trabd->get_rtrabd_kelas();			
			$data['mahasiswa_kehadiran'] = $this->trabm->get_rtrabm_mahasiswa();

			
			$data['center']='dosen/content/prosentase_kehadiran_mahasiswa_kelas';			
		$this->load->view('dosen/dashboard/presensi_v_dashboard_dosen',$data);

	}
////

/////////////////////PRESENSIBARU/////////
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
        'abhdrtrabm' => 1,
        'abskttrabm' => 0,
        'abijntrabm' => 0,
        'abijntrabm' => 0,		
        'abalptrabm' => 0		        
	);

					$this->db->where('thsmstrabm',$this->session->userdata('thsms'));	
					
					$this->db->like('kdpsttrabm',$this->session->userdata('kdpstmsmks'));
					$this->db->like('kdkmktrabm',$this->session->userdata('kdkmkmsmks'));
					$this->db->where('kelastrabm',$this->session->userdata('kelasmsmks'));
					$this->db->where('ttmketrabm',$this->session->userdata('ttmketrabd')); 

			$this->db->update('rtrabm', $data);
	}  
	
//////////////////////////////////////////
////
	
public function presensi_ajax_list()
	{
	$this->load->model('dosen/rtrabm_model_datatable','sk');		
	$list = $this->sk->get_datatables();
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $sk) {
	$no++;
	$row = array();
	$row[] = $no;

	$row[] = $sk->nimhstrabm;
	$row[] = $sk->nmmhstrabm;


	if ($sk->abhdrtrabm==1)
	{
	$row[] = 
	'	
	<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Hadir"><i class="flaticon2-checkmark"></i> </a>';		
	}else{
	$row[] = 
	'	
	<a class="btn btn-sm btn-default" href="javascript:void(0)" title="Tidak Hadir" onclick="presensi_abhdr('."'".$sk->nimhstrabm."'".')"><i class="glyphicon glyphicon-check"></i> </a>';				
	}
	
	if ($sk->abskttrabm==1)
	{
	$row[] = 
	'	
	<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Hadir"><i class="flaticon2-plus"></i> </a>';	
	}else{
	$row[] = 
	'	
	<a class="btn btn-sm btn-default" href="javascript:void(0)" title="Tidak Sakit" onclick="presensi_abskt('."'".$sk->nimhstrabm."'".')"><i class="glyphicon glyphicon-check"></i> </a>';				
	}

	if ($sk->abijntrabm==1)
	{
	$row[] = 
	'	
	<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Hadir"><i class="flaticon-danger"></i> </a>';	
	}else{
	$row[] = 
	'	
	<a class="btn btn-sm btn-default" href="javascript:void(0)" title="Tidak Ijin" onclick="presensi_abijn('."'".$sk->nimhstrabm."'".')"><i class="glyphicon glyphicon-check"></i> </a>';				
	}

	if ($sk->abalptrabm==1)
	{
	$row[] = 
	'	
	<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hadir"><i class="flaticon-close"></i> </a>';
	}else{
	$row[] = 
	'	
	<a class="btn btn-sm btn-default" href="javascript:void(0)" title="Tidak Alpha" onclick="presensi_abalp('."'".$sk->nimhstrabm."'".')"><i class="glyphicon glyphicon-check"></i> </a>';				
	}

	
	  
	$data[] = $row;
	}

	$output = array(
	"draw" => $_POST['draw'],
	"recordsTotal" => $this->sk->count_filtered(),
	"recordsFiltered" => $this->sk->count_filtered(),
	"data" => $data,
	);
	//output to json format
	echo json_encode($output);
	}

 
			
function ajax_presensi_hadir($nimhs)
	{
		$this->load->model('dosen/rtrabm_model_datatable','sk');	
	  	$thsmstrabm = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');			
	
		$data = 	
			array(
				'abhdrtrabm' => '1',
				'abskttrabm' => '0',				
				'abijntrabm' => '0',
				'abalptrabm' => '0',
				'presntrabm' => 'HADIR',				
				);
			$this->sk->update(array(
					'nimhstrabm' => $nimhs,
					'thsmstrabm' => $thsmstrabm,
					'kdpsttrabm' => $this->session->userdata('kdpstmsmks'),					
					'kdkmktrabm' => $this->session->userdata('kdkmkmsmks'),
					'kelastrabm' => $this->session->userdata('kelasmsmks'),
					'ttmketrabm' => $this->session->userdata('ttmketrabd'),		
					), 
					$data);			
		/* $where =
			array(
					'nimhstrabm' => $nimhs,
 					'thsmstrabm' => $thsmstrabm,
					'kdpsttrabm' => $this->session->userdata('kdpstmsmks'),					
					'kdkmktrabm' => $this->session->userdata('kdkmkmsmks'),
					'kelastrabm' => $this->session->userdata('kelasmsmks'),
					'ttmketrabm' => $this->session->userdata('ttmketrabd'),	 
					); */
					
		//$this->sk->update($data, $where);  
	
		//echo json_encode($data);
		//
		
		//$this->db->update('rtrabm', $data, $where);
		//echo json_encode(array("status" => TRUE));			
		//echo json_encode($data);
	}
function ajax_presensi_ijin($nimhs)
	{
		$this->load->model('dosen/rtrabm_model_datatable','sk');	
		$thsmstrabm = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');			

		$data = 	
			array(
				'abhdrtrabm' => '0',
				'abskttrabm' => '0',				
				'abijntrabm' => '1',
				'abalptrabm' => '0',
				'presntrabm' => 'IJIN',				
				);
					$this->sk->update(array(
					'nimhstrabm' => $nimhs,
					'thsmstrabm' => $thsmstrabm,
					'kdpsttrabm' => $this->session->userdata('kdpstmsmks'),					
					'kdkmktrabm' => $this->session->userdata('kdkmkmsmks'),
					'kelastrabm' => $this->session->userdata('kelasmsmks'),
					'ttmketrabm' => $this->session->userdata('ttmketrabd'),		
					), 
					$data);  

		//$where= array('nimhstrabm' => $nimhs);			

		//$this->db->update('rtrabm', $data, $where);
		//echo json_encode(array("status" => TRUE));
	}	
function ajax_presensi_sakit($nimhs)
	{
			$this->load->model('dosen/rtrabm_model_datatable','sk');	
		$thsmstrabm = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');			
		
		$data = 	
			array(
				'abhdrtrabm' => 0,
				'abskttrabm' => 1,				
				'abijntrabm' => 0,
				'abalptrabm' => 0,
				'presntrabm' => 'SAKIT',				
				);
					$this->sk->update(array(
					'nimhstrabm' => $nimhs,
					'thsmstrabm' => $thsmstrabm,
					'kdpsttrabm' => $this->session->userdata('kdpstmsmks'),					
					'kdkmktrabm' => $this->session->userdata('kdkmkmsmks'),
					'kelastrabm' => $this->session->userdata('kelasmsmks'),
					'ttmketrabm' => $this->session->userdata('ttmketrabd'),	
					), 
					$data);
		//echo json_encode($data);			
	}
function ajax_presensi_alpa($nimhs)
	{
			$this->load->model('dosen/rtrabm_model_datatable','sk');	
		$thsmstrabm = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');			
		
		$data = 	
			array(
				'abhdrtrabm' => 0,
				'abskttrabm' => 0,				
				'abijntrabm' => 0,
				'abalptrabm' => 1,
				'presntrabm' => 'ALPHA',				
				);
					$this->sk->update(array(
					'nimhstrabm' => $nimhs,
					'thsmstrabm' => $thsmstrabm,
					'kdpsttrabm' => $this->session->userdata('kdpstmsmks'),					
					'kdkmktrabm' => $this->session->userdata('kdkmkmsmks'),
					'kelastrabm' => $this->session->userdata('kelasmsmks'),
					'ttmketrabm' => $this->session->userdata('ttmketrabd'),		
					), 
					$data);
		//echo json_encode($data);			
	}		
//NILAI

public function nilai()
    {
		$this->load->model('dosen/rtrkrs_model_datatable'); 		
		$this->load->view('dosen/dashboard/header');		
		$this->load->view('dosen/dashboard/header_classroom');			
		$this->load->view('dosen/dashboard/end');
		$data['center']='dosen/content/nilai_view_datatable';
		$this->load->view('dosen/dashboard/v_dashboard_dosen',$data);
		    			
		$this->load->view('dosen/dashboard/footer');			
	}
public function peserta_ajax_list_nilai()
	{
			ini_set('display_errors','off');	
			
	$this->load->model('dosen/rtrkrs_model_datatable','sk');		
	$list = $this->sk->get_datatables();
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $sk) {
	$no++;
	$row = array();
	
	$row[] = $no;
	$row[] = $sk->nimhstrkrs;	
	$row[] = $sk->nmmhstrkrs;	
	
	$row[] = $sk->kdkmktrkrs;	
	$row[] = $sk->nmkmktrkrs;
/* 	$row[] = '<input id="nilaiku" value="'.$sk->nlakhtrkrs.'" id="inputDatabaseName" autocomplete="off" onchange="check('.$sk->nimhstrkrs.');"
			   onkeyup="this.onchange('.$sk->nimhstrkrs.');" onpaste="this.onchange('.$sk->nimhstrkrs.');" oninput="this.onchange('.$sk->nimhstrkrs.');" />';	 */	
	
	$row[] = $sk->nlakhtrkrs;	
			   
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

/////
public function peserta_ajax_list_nilai_uts_uas_nilai_akhir()
	{
			ini_set('display_errors','off');				
	$this->load->model('dosen/rtrkrs_model_datatable','sk');		
	$list = $this->sk->get_datatables();
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $sk) {
	$no++;
	$row = array();
	
	$row[] = $no;
	$row[] = $sk->nimhstrkrs;	
	$row[] = $sk->nmmhstrkrs;	
	
	$row[] = $sk->kdkmktrkrs;	
	$row[] = $sk->nmkmktrkrs;
/* 	$row[] = '<input id="nilaiku" value="'.$sk->nlakhtrkrs.'" id="inputDatabaseName" autocomplete="off" onchange="check('.$sk->nimhstrkrs.');"
			   onkeyup="this.onchange('.$sk->nimhstrkrs.');" onpaste="this.onchange('.$sk->nimhstrkrs.');" oninput="this.onchange('.$sk->nimhstrkrs.');" />';	 */	
			   
	$row[] = '<center>' . $sk->nlutstrkrs . '</center>';		
	$row[] = '<center>' .$sk->nluastrkrs. '</center>';
	$row[] = '<center>' .$sk->nlakhtrkrs. '</center>';	
			   
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

/////
function ajax_update_nilai($nimhs,$nilai)
	{
		$this->load->model('dosen/rtrkrs_model_datatable','tr');	
		$thsmstrabm = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');			
		
		$data = 	
			array(
				'nlakhtrkrs' => $nilai,				
				);
					$this->tr->update(array(
					'nimhstrkrs' => $nimhs,
					'thsmstrkrs' => $thsmstrabm,
					'kdpsttrkrs' => $this->session->userdata('kdpstmsmks'),					
					'kdkmktrkrs' => $this->session->userdata('kdkmkmsmks'),
					'kelastrkrs' => $this->session->userdata('kelasmsmks'),
 	
					), 
			$data);
		//echo json_encode($data);			
	}

		
public function edit_nilai()
    {
		$this->load->helper('url');
	
		$this->load->model('dosen/rtrkrs_model'); 		
		$this->load->view('dosen/dashboard/header');		
		$this->load->view('dosen/dashboard/header_classroom');			
		$this->load->view('dosen/dashboard/end');


			$data1 = array( 'title' => 'Data lulusan',
			'listnilai' => $this->rtrkrs_model->get_rtrkrs_nilai());
			$this->load->view('dosen/content/nilai_table',$data1);
		    			
		$this->load->view('dosen/dashboard/footer');			
	}

public function updatenilai(){
     // POST values
	$thsmstrabm = $this->session->userdata('thakdmsmks').$this->session->userdata('smakdmsmks');			
	
	$this->load->model('dosen/rtrkrs_model_datatable','tr');
	$this->load->model('dosen/rtrkrs_model'); 
		
     $id = $this->input->post('id');
     $field = $this->input->post('field');
     $value = $this->input->post('value');

     // Update records
     //$this->rtrkrs_model->updateUser($id,$field,$value);


		$data = 	
			array(
				'nlakhtrkrs' => $value,				
				);
					$this->tr->update(array(
					'nimhstrkrs' => $id,
					'thsmstrkrs' => $thsmstrabm,
					'kdpsttrkrs' => $this->session->userdata('kdpstmsmks'),					
					'kdkmktrkrs' => $this->session->userdata('kdkmkmsmks'),
					'kelastrkrs' => $this->session->userdata('kelasmsmks'),
 	
					), 
			$data);
			

/*      echo 1;
     exit; */
   }




public function kalender_akademik()
    {
		$this->load->helper('url');
			$this->load->model('dosen/rmsmks_model');
			$data = 
			array(
			
			'list_kelas_senin' => $this->rmsmks_model->get_rmsmks_kalender_senin(),
			'list_kelas_selasa' => $this->rmsmks_model->get_rmsmks_kalender_selasa(),
			'list_kelas_rabu' => $this->rmsmks_model->get_rmsmks_kalender_rabu(),
			'list_kelas_kamis' => $this->rmsmks_model->get_rmsmks_kalender_kamis(),
			'list_kelas_jumat' => $this->rmsmks_model->get_rmsmks_kalender_jumat(),
			'list_kelas_sabtu' => $this->rmsmks_model->get_rmsmks_kalender_sabtu(),

			'list_kelas_kosong' => $this->rmsmks_model->get_rmsmks_kalender_kosong(),			
			
			);
			
		$this->load->view('dosen/dashboard/header');
				
        $this->load->view('jadwal/kalender_akademik',$data);
		$this->load->view('dosen/dashboard/footer');
    }

	
}
