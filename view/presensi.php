<!DOCTYPE html>
<html>
<head>
	<title>warna</title>
</head>

<table border="1" width="100%">
<thead>
<tr>
<th>No</th>
<th>nimhstrabm</th>
<th>nmmhstrabm</th>

<th>abhdrtrabm</th>
<th>abskttrabm</th>
<th>abijntrabm</th>
<th>abalptrabm</th>

 

</tr>
</thead>
<tbody>

<?php $i=1; foreach($departments as $store) { ?>
           <tr>
<td><?php echo $i; ?></td>
<td><?php echo $store->nimhstrabm ?></td>
<td><?php echo $store->nmmhstrabm; ?></td>
<td><input 
<?php 
if($store->abhdrtrabm=="1")
{echo "checked";} 
else {};
?> 
name="<?=$store->nmmhstrabm;?>" id="<?=$store->nmmhstrabm."hdr";?>" type="radio" name="check_list[]" alt="Checkbox" value="merah"></td> 
<td><input
<?php 
if($store->abskttrabm=="1")
{echo "checked";} 
else {};
?> 
name="<?=$store->nmmhstrabm;?>" id="<?=$store->nmmhstrabm."sakit";?>" type="radio" name="check_list[]" alt="Checkbox" value="merah"></td> 
<td><input
<?php 
if($store->abijntrabm=="1")
{echo "checked";} 
else {};
?> 
name="<?=$store->nmmhstrabm;?>" id="<?=$store->nmmhstrabm."ijin";?>" type="radio" name="check_list[]" alt="Checkbox" value="merah"></td> 
<td><input 
<?php 
if($store->abalptrabm=="1")
{echo "checked";} 
else {};
?> 
name="<?=$store->nmmhstrabm;?>" id="<?=$store->nmmhstrabm."aplha";?>" type="radio" name="check_list[]" alt="Checkbox" value="merah"></td> 

           <?php $i++; } ?>
      </tbody>
 </table>

<body>
	<?php echo form_open('warna/insert') ?>


		<input type="checkbox" name="check_list[]" alt="Checkbox" value="merah"> merah
		<input type="checkbox" name="check_list[]" alt="Checkbox" value="kuning"> kuning
		<input type="checkbox" name="check_list[]" alt="Checkbox" value="hijau"> hijau
		<input type="checkbox" name="check_list[]" alt="Checkbox" value="biru"> biru
		<input type="submit"   name="tampil" value="Simpan">


	<?php echo form_close()?>

</body>


</html>
