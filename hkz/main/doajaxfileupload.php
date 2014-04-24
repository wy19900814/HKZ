<?php
	//ini_set('post_max_size', '2M');
	//ini_set('upload_max_filesize', '2M');
// make a note of the current working directory, relative to root.
	$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);

// make a note of the directory that will recieve the uploaded files
	$uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'question_img/';
	$fieldname = 'img';
	
	// Now let's deal with the upload

	// possible PHP upload errors
	$errors = array(1 => 'Php.ini max file size exceeded', 
                2 => 'Html form max file size exceeded', 
                3 => 'File upload was only partial', 
                4 => 'No file was attached');
	
	// check for standard uploading errors
	($_FILES[$fieldname]['error'] == 0)
	or error($errors[$_FILES[$fieldname]['error']]);
	
	// check that the file we are working on really was an HTTP upload
	//print "CDH $_FILES[$fieldname]['tmp_name'] ";

	@is_uploaded_file($_FILES[$fieldname]['tmp_name'])
	or error('Not an HTTP upload');

	// validation... since this is an image upload script we 
	// should run a check to make sure the upload is an image
	@getimagesize($_FILES[$fieldname]['tmp_name'])
	or error('Only image uploads are allowed');
	
	// make a unique filename for the uploaded file and check it is 
	// not taken... if it is keep trying until we find a vacant one
	/*$now = time();
	while(file_exists($uploadFilename = $uploadsDirectory.$now.'-'.$_FILES[$fieldname]['name']))
	{
	$now++;
	} */
	$uploadFilename = $uploadsDirectory.$_FILES[$fieldname]['name'];

	@move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename)
	or error('Receiving directory insuffiecient permission');

	echo 'success';
	
	function error($error)
	{
		echo $error;
		exit;
	}
?>