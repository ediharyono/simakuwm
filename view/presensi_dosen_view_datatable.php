 
<div class="col-md-12 bg-white p-4 container" style="border-radius:3px;box-shadow:rgba(0, 0, 0, 0.03) 0px 4px 8px 0px">


									<div class="card-header border-0 py-5">
										<center>
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label font-weight-bolder text-dark">Presensi Hadir Mahasiswa</span>
										</h3>
										</center>


<table class="table">
    <tbody>
        <tr>
            <th scope="row">Tahun Akademik</th>
            <td>: <?=$this->session->userdata('thsms');?></td>
			<th scope="row">Program Studi</th>
            <td>: <?=$this->session->userdata('kdpstmsmks');?></td>			
        </tr>		
        <tr>
            <th scope="row">Kode Matakuliah</th>
            <td>: <?php 
			
			
			echo $this->session->userdata('kdkmkmsmks');
			
			
			?></td>
            <th scope="row">Kelas</th>
            <td>: <?=$this->session->userdata('kelasmsmks');?></td>
        </tr>				
        <tr>
            <th scope="row">Pertemuan ke-</th>
            <td>: <?=$this->session->userdata('ttmketrabd');?></td>
        </tr>						
		
    </tbody>
</table>
						
 
 	
		<div class="card-toolbar">
			<ul class="nav nav-tabs nav-bold nav-tabs-line">

				 <li class="nav-item">
				
					<a class="nav-link active" href="<?=base_url('dosen/e_learning');?>">
					<span class="nav-icon"><i class="flaticon2-paper"></i></span>
					<span class="nav-text">Home</span>
					</a>
					
				</li>
				
				
				<li class="nav-item">
				
					<a class="nav-link active" data-toggle="tab" href="#" onclick="window.print()">
					<span class="nav-icon"><i class="flaticon2-paper"></i></span>
					<span class="nav-text">Cetak</span>
					</a>
					
				</li>
				
 				<li class="nav-item">
				
					<a class="nav-link active" data-toggle="tab" href="#" onclick="window.print()">
					<span class="nav-icon"><i class="flaticon2-paper"></i></span>
					<span class="nav-text">PDF</span>
					</a>
					
				</li>
				

				
				<li class="nav-item">
				
					<a class="nav-link active right" data-toggle="tab"  href="javascript:void(0)" onclick="dosen_presensi_hadir_semua()">
					<span class="nav-icon"><i class="flaticon2-checkmark"></i></span>
					<span class="nav-text">Set Hadir Semua..</span>
					</a>
					
				</li> 
			</ul>
		</div>
 

		
									</div>
									
	<div class="card-body">
										
 <!---link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"--->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>									
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/jquery.dataTables.min.css')?>" rel="stylesheet">

<div  class="table-wrapper">
<div class="datatable">
<div class="datatable-data server-mode"> 
<div class="visible"> 
<table id="table" class="table table-striped datatable-header-cell-template-wrap" cellspacing="0" style="width:100%">
<thead>
<tr width="1">
	<th><span class="ng-star-inserted">NO</span></th>
	<th><span class="ng-star-inserted">NIM</span></th>
	<th><span class="ng-star-inserted">NAMA</span></th>
	<th><span class="ng-star-inserted">HADIR</span></th>
	<th><span class="ng-star-inserted">SAKIT</span></th>
	<th><span class="ng-star-inserted">IJIN</span></th>
	<th><span class="ng-star-inserted">ALPA</span></th>
</tr>
</thead>
<tbody>
</tbody>
</table>
</div></div></div>
</div>

</div>

    <div class="card-footer d-flex justify-content-between">
<table>
<tr>
<td>
<center>Yogyakarta, <?php echo (new \DateTime())->format('d M Y');?></center>
<center>Mengetahui :</center>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
<center>...................</center>
<center>Dosen Pengajar</center>
</td>
</tr>
</table>

	</div>


</div>
 
 
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>	
<script type="text/javascript">
var save_method;
var table;
$(document).ready(function() {
table = $('#table').DataTable({
"processing": true,
"serverSide": true,
"order": [],

// Load data for the table's content from an Ajax source
"ajax": {
"url": "<?php echo site_url('dosen/presensi_ajax_list')?>",
"type": "POST"
},

//Set column definition initialisation properties.
"columnDefs": [
{
"targets": [ -1 ], //last column
"orderable": false, //set not orderable
},
],

});



});

function add_rtrabm()
{
save_method = 'add';
$('#form')[0].reset();
$('.form-group').removeClass('has-error');
$('.help-block').empty();
$('#modal_form').modal('show');
$('.modal-title').text('Add rtrabm');
}


function reload_table()
{
table.ajax.reload(null,false);
}

function save()
{
$('#btnSave').text('saving...');
$('#btnSave').attr('disabled',true);
var url;

if(save_method == 'add') {
url = "<?php echo site_url('rtrabm/ajax_add')?>";
} else {
url = "<?php echo site_url('rtrabm/ajax_update')?>";
}


$.ajax({
url : url,
type: "POST",
data: $('#form').serialize(),
dataType: "JSON",
success: function(data)
{

if(data.status) //if success close modal and reload ajax table
{
$('#modal_form').modal('hide');
reload_table();
}

$('#btnSave').text('Simpan'); //change button text
$('#btnSave').attr('disabled',false); //set button enable
			$('#ditambahkan').modal('show').modal('hide');
			reload_table();

},
error: function (jqXHR, textStatus, errorThrown)
{
alert('Error adding / update data');
$('#btnSave').text('Update');
$('#btnSave').attr('disabled',false);

}
});
}


//==============================================================================
function reload_table()
{
table.ajax.reload(null,false);
}


function presensi_abhdr(no)
{
	$.ajax({
url : "<?php echo site_url('dosen/ajax_presensi_hadir')?>/" + no,
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				alert('Error deleting data');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				reload_table();
			}
});
}
///////////////////////////////

////////////////////////////////
function presensi_abijn(no)
{
	$.ajax({
url : "<?php echo site_url('dosen/ajax_presensi_ijin')?>/" + no,
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				alert('Error deleting data');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				reload_table();
			}
});
}
///////////////////////////////

////////////////////////////////
function presensi_abskt(no)
{
	$.ajax({
url : "<?php echo site_url('dosen/ajax_presensi_sakit')?>/" + no,
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				alert('Error deleting data');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				reload_table();
			}
});
}
///////////////////////////////

////////////////////////////////
function presensi_abalp(no)
{
	$.ajax({
url : "<?php echo site_url('dosen/ajax_presensi_alpa')?>/" + no,
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				alert('Error deleting data');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				reload_table();
			}
});
}
///////////////////////////////


function dosen_presensi_hadir_semua()
{
	alert('Hadir Semua Pertemuan ini?'); 
{

$.ajax({
url : "<?php echo site_url('dosen_presensi/dosen_presensi_hadir_semua')?>/",
			type: "POST",
			dataType: "JSON",
			success: function(data)
			{
				alert('Error deleting data');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				reload_table();
			}
});
}

}

////

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="konfirmasi_hapus" role="dialog">
<div role="document" class="modal-dialog modal-alert modal-danger">
<div class="modal-content">
<div class="modal-header"></div>
<div class="modal-body">
<div class="modal-icon">
<span class="icon icon-trash"></span>
<h3 class="modal-title">Apakah Anda yakin akan menghapus data?</h3><div class="modal-message">
<p>Data yang telah dihapus tidak bisa diubah kembali</p></div></div>
<div class="modal-footer">
<button class="btn btn-default" type="button" data-dismiss="modal">Tidak, batalkan</button>
<button class="btn btn-danger" type="button" id="btnDelteYes">Ya, hapus</button>
</div></div></div></div></div>

