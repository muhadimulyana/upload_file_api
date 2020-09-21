<?php 

//Set error to show
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('functions.php');

if(isset($_FILES['file']['name'])){

    $response = uploadFile($_POST['scope'], $_POST['dir'], $_POST['file_name'], $_FILES['file']);

    header('Content-Type: application/json');
    echo json_encode($response);
}