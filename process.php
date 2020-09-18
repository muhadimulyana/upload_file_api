<?php
//Set error to show
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Asia/Jakarta"); // Set default time zone

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

//Check if post file is exists
if(isset($_POST['dir'])){

    $path = 'uploads';
    $directories = $_POST['dir'];

    //create folder if exists
    if(!file_exists($path . '/' . $directories)){
        $oldmask = umask(0);
        mkdir($path . '/' . $directories, 0777, true);
        umask($oldmask);
    }

    $date = date('y-m-d H:i:s');
    $filename = '[' . $date . ']' . $_POST['file_name'];
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $uploadFile = $path . '/' . $directories . '/' . $filename . '.' . $ext;

    try {

        //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile);
        if(!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)){
            throw new Exception('File tidak dapat diunggah');
        } else {
            chmod($uploadFile, 0777);
            $url = getBaseUrl() . $uploadFile;
            $out = [
                'message' => 'Upload success',
                'file_name' => $filename . '.' . $ext,
                'url' => $url
            ];
        }

    } catch (Exception $e) {
        $out = [
            'message' => 'Upload failed ' . $e->getMessage
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($out);


    // Looping all files & do upload

    // $jumlah = count($_FILES['file']['name']);
    // for($i=0; $i<$jumlah; $i++){
    //     $filename = $_FILES['file']['name'][$i];
    //     $uploadFile = $path . '/' . $directories . '/' . $filename;
    //     $upload = move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadFile);
    //     chmod($uploadFile, 0777);
    // }

   

}