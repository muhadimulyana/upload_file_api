<?php
//Set error to show
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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
    $scope = $_POST['scope'];
    $directories = $_POST['dir'];

    //create folder if exists
    if(!file_exists($path . '/' . $scope . '/' . $directories)){
        $oldmask = umask(0);
        mkdir($path . '/' . $scope . '/' . $directories, 0777, true);
        umask($oldmask);
    }

    $date = date('y-m-d H:i:s');
    $filename = '[' . $date . ']' . $_POST['file_name'];
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $uploadFile = $path . '/' . $scope . '/' . $directories . '/' . $filename . '.' . $ext;

     //move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile);
    if(!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)){
        $out = [
            'message' => 'Upload failed '
        ];
    } else {
        chmod($uploadFile, 0777);
        $url = getBaseUrl() . $uploadFile;
        $out = [
            'message' => 'Upload success',
            'file_name' => $filename . '.' . $ext,
            'url' => $url
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($out);


}