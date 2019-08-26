<?php
ini_set('max_execution_time', 0); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if($_POST['upload']){
	$ekstensi_diperbolehkan	= array('pdf');
	$nama = $_FILES['file']['name'];
	$x = explode('.', $nama);
	$ekstensi = strtolower(end($x));
	$ukuran	= $_FILES['file']['size'];
	$file_tmp = $_FILES['file']['tmp_name'];	

	if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
		if($ukuran < 100440700){			
			move_uploaded_file($file_tmp, 'files/filePDFku.pdf')
			// if( move_uploaded_file($file_tmp, 'uploads/filePDFku.pdf') ){
				header("Location: pdfviewer.php");
			// }else{
				// echo 'GAGAL MENGUPLOAD GAMBAR';
			// }
		}else{
			echo 'UKURAN FILE TERLALU BESAR';
		}
	}else{
		echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
	}
}