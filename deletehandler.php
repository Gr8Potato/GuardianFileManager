<?php
session_start();

if (isset($_POST['filename'])) {
    $file_name = $_POST['filename'];
    $exclude = array('.bash_history', '.cache', '.bash_logout', '.config', '.local', '.bashrc', '.profile', 'snap');

    if (preg_match('/\.\.(\/|\\\\)/', $filename) || in_array($file_name, $exclude)) {
        exit;
    }


    $user_dir = "/home/" . $_SESSION["user"];
    $file_path = $user_dir . '/' . $file_name;

    if (file_exists($file_path)) {
        audit_log($_SESSION["user"] . " DELETED " . $file_name . " from /home/" . $_SESSION["user"]);
        unlink($file_path);
        echo "File deleted";
    } else {
        echo "File not found";
    }
}

function audit_log($message)
{
    $file = '/var/www/log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = $timestamp . ' - ' . $message . PHP_EOL;

    file_put_contents($file, $logMessage, FILE_APPEND | LOCK_EX);
}
?>