<?php 
//Set error to show
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Asia/Jakarta"); // Set default time zone
/*
==================
parameter upload
* action = "upload"
* path = "hrd/ppbj"
* filename = "file.pdf"
------------------
parameter download
* action = "download"
* path = "hrd/ppbj"
* filename = "file.pdf"
------------------
parameter direct download
* action = "download"
* filename = "hrd/ppbj/file.pdf"
------------------
parameter delete
* action = "delete"
* path = "hrd/ppbj"
* filename = "file.pdf"
------------------
* action = "rename"
* new_filename = "hrd/ppbj.pdf"
* old_filename = "hrd/file.pdf"
------------------
* action = "cekupdate"
*/

$basepath = 'uploads';

function uploadFile($scope, $dir, $fileName){

    if($_FILES['file']){
        $uFile = $_FILES['file'];
        global $basepath;
        //create folder if exists
        if(!file_exists($basepath . '/' . $scope . '/' . $dir)){
            $oldmask = umask(0);
            mkdir($basepath . '/' . $scope . '/' . $dir, 0777, true);
            umask($oldmask);
        }
    
        $ext = pathinfo($uFile['name'], PATHINFO_EXTENSION);
        $fileName = $fileName == '' ? $uFile['name'] : $fileName . '.' . $ext; 
        $uploadFile = $basepath . '/' . $scope . '/' . $dir . '/' . $fileName;
    
        if(file_exists($uploadFile)){
            $cnt = 1; //iteration of file
            $x_file = pathinfo($uploadFile);
            $x_dirname = $x_file['dirname'];
            $x_filename = $x_file['filename'];
            $x_ext = $x_file['extension'];
    
            $fileName = $x_filename . '(' . $cnt . ')' . '.' . $x_ext;
            while(file_exists($x_dirname . '/' . $x_filename . '(' . $cnt . ')' . '.' . $x_ext)){
                $cnt = $cnt + 1;
                $fileName = $x_filename . '(' . $cnt . ')' . '.' . $x_ext;
            }
    
            $uploadFile = $basepath . '/' . $scope . '/' . $dir . '/' .  $fileName;
        }
    
         //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile);
        if(!move_uploaded_file($uFile['tmp_name'], $uploadFile)){
            $out = [
                'message' => 'Gagal ' . $uFile['error']
            ];
        } else {
            chmod($uploadFile, 0777);
            getBaseUrl() . $uploadFile;
            $out = [
                'message' => 'Ok',
                'file_name' => $fileName,
                'url' => getBaseUrl() . $uploadFile
            ];
        }
    } else {
        $out = [
            'message' => 'Tidak ada file yang dipilih'
        ];
    }

    return $out;

}

function downloadFile($dir, $fileName) {

    global $basepath;

    if (!file_exists($basepath."/". $dir . '/' . $fileName)){

		$out = array(
            "status" => "error",
            "message" => "File tidak ditemukan",
            "file" => $basepath."/".$fileName
        );	
        
        return $out;

	} else {
		header("Content-Type: application/octet-stream");
		header('Accept-Ranges: bytes');
		header("Content-length: ".filesize($basepath."/".$fileName));
        readfile($basepath."/".$fileName);	
    }
}

function getBaseUrl() 
{
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF']; 
    // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
    $pathInfo = pathinfo($currentPath); 
    // output: localhost
    $hostName = $_SERVER['HTTP_HOST']; 
    // output: http://
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
    // return: http://localhost/myproject/
    return $protocol.'://'.$hostName.$pathInfo['dirname']."/";
}
