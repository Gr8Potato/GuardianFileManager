<?php
session_start();

if (isset($_POST['filename'])) {
    $file_name = $_POST['filename'];
    $filetype = $_POST['filetype'];

    $exclude = array('.bash_history', '.cache', '.bash_logout', '.config', '.local', '.bashrc', '.profile', 'snap');

    sanitize($file_name);
    sanitize($filetype);

    $user_dir = $filetype === 'personal' ? "/home/" . $_SESSION["user"] : "/home/shared";
    $file_path = $user_dir . '/' . $file_name;

    if (preg_match('/\.\.(\/|\\\\)/', $filename) || in_array($file_name, $exclude)) {
        audit_log($_SESSION["user"] . " FAIL DELETE " . $file_name . " from " . $user_dir);
        exit;
    }

    if (file_exists($file_path)) {
        audit_log($_SESSION["user"] . " DELETED " . $file_name . " from " . $user_dir);
        unlink($file_path);
        echo "File deleted";
    } else {
        audit_log($_SESSION["user"] . " FAIL DELETE " . $file_name . " from " . $user_dir);
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

function sanitize(&$data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
}
?>