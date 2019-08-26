<?php
ini_set('max_execution_time', 0); 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if($_POST['upload']){
	$targetfolder = "file/";

	 $targetfolder = $targetfolder . basename( $_FILES['file']['name']) ;

	 $ok=1;

	$file_type=$_FILES['file']['type'];

	if ($file_type=="application/pdf") {

	 if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolder))

	 {
		header("Location: pdfviewer.php?f=".$_FILES['file']['name']);
	 // echo "The file ". basename( $_FILES['file']['name']). " is uploaded";

	 }

	 else {

	 echo "Problem uploading file";

	 }

	}

	else {

	 echo "You may only upload PDFs files.<br>".$file_type;

	}

}