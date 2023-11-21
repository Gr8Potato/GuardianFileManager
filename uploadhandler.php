<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login");
    exit;
}

$user_dir = "/home/" . $_SESSION["user"];

if (!file_exists($user_dir)) {
    if (!mkdir($user_dir, 0775, true)) {
        header("Location: main?error=directory does not exist");
        exit;
    }
}

// check if the file has been uploaded via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $file_temp_path = $_FILES['fileToUpload']['tmp_name'];
    $file_name = $_FILES['fileToUpload']['name'];
    $file_size = $_FILES['fileToUpload']['size'];
    $file_type = $_FILES['fileToUpload']['type'];
    $error = $_FILES['fileToUpload']['error'];

    // check if there's any error with the file
    if ($error !== UPLOAD_ERR_OK) {
        header("Location: main?error=no file submitted");
        exit;
    }

    // sanitize the file name to prevent directory traversal or other potential hazards
    $file_nameCmps = explode(".", $file_name);
    $file_extension = strtolower(end($file_nameCmps));

    // define the path to save the uploaded file
    $dest_path = $user_dir . '/' . $file_name;

    // if the file already exists, add a number to the file name
    $counter = 1;
    while (file_exists($dest_path)) {
        $newfile_name = $file_nameCmps[0] . '_' . $counter . '.' . $file_extension;
        $dest_path = $user_dir . '/' . $newfile_name;
        $counter++;
    }

    // move the file from the temporary directory to the user's directory
    if (move_uploaded_file($file_temp_path, $dest_path)) {
        audit_log($_SESSION["user"] . " UPLOAD " . $file_name . " to /home/" . $_SESSION["user"]);
        header("Location: main?status=upload success"); //we're refreshing the browser here. appending the entry with js is out of reach currently        
    } else {
        header("Location: main?error=issue moving file to directory");
        ;
    }
} else {
    header("Location: main?error=test");
}

function audit_log($message)
{
    $file = '/var/www/log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = $timestamp . ' - ' . $message . PHP_EOL;

    file_put_contents($file, $logMessage, FILE_APPEND | LOCK_EX);
}
?>