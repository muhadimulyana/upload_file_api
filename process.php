<?php
//Set error to show
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Asia/Jakarta"); // Set default time zone

//Check if post file is exists
if(isset($_POST['file'])){

    $path = 'uploads/';
    $directories = $_POST['directory'];

    //create folder
    $oldmask = umask(0);
    mkdir($path . '/' . $directories, 0777, true);
    umask($oldmask);
    
    
    // $jumlah = count($_FILES['file']['name']);

    // // Looping all files & do upload
    // for($i=0; $i<$jumlah; $i++){
    //     $filename = $_FILES['file']['name'][$i];
    //     $uploadFile = $PathDef . $folder . '/' . $filename;
    //     $upload = move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadFile);
    //     chmod($uploadFile, 0777);
    // }

    // if($upload){
    //     exit;
    // } else {
    //     echo "error";
    //     exit;
    // }

}