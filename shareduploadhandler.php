<?php
session_start();

if (isset($_FILES['filesToUpload'])) {
    $user_dir = "/home/shared";

    foreach ($_FILES['filesToUpload']['name'] as $key => $name) {
        $temp_dir = $_FILES['filesToUpload']['tmp_name'][$key];
        $original_file_name = basename($_FILES['filesToUpload']['name'][$key]);
        sanitize($original_file_name);
        $file_no_ext = pathinfo($original_file_name, PATHINFO_FILENAME);
        $file_ext = pathinfo($original_file_name, PATHINFO_EXTENSION);
        $destination = $user_dir . '/' . $original_file_name;

        $counter = 1;
        while (file_exists($destination)) {
            $new_file = $file_no_ext . '_' . $counter;
            if ($file_ext) {
                $new_file .= '.' . $file_ext;
            }
            $destination = $user_dir . '/' . $new_file;
            $counter++;
        }

        if ($_FILES['filesToUpload']['error'][$key] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($temp_dir, $destination)) {
                // Log each successful file upload
                audit_log($_SESSION["user"] . " UPLOADED " . $new_file . " to " . $user_dir);
            } else {
                header("Location: main?error=file upload error");
                exit;
            }
        } else {
            header("Location: main?error=no file(s) uploaded");
            exit;
        }
    }
} else {
    header("Location: main?error=no file(s) uploaded");
    exit;

}
header("Location: main?error=file(s) uploaded successfully");
exit;

function sanitize(&$data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
}

function audit_log($message)
{
    $file = '/var/www/log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = $timestamp . ' - ' . $message . PHP_EOL;

    file_put_contents($file, $logMessage, FILE_APPEND | LOCK_EX);
}
?>