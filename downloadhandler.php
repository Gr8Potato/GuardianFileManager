<?php
session_start();

if (isset($_GET['filename'])) {

    $exclude = array('.bash_history', '.cache', '.bash_logout', '.config', '.local', '.bashrc', '.profile', 'snap');

    $file_name = $_GET['filename'];
    $filetype = $_GET['filetype'];

    sanitize($file_name);
    sanitize($filetype);
    
    $file_type = isset($_GET['type']) ? $_GET['type'] : '';

    $user_dir;
    if ($filetype === 'personal') {
        $user_dir = "/home/" . $_SESSION["user"];
    } elseif ($filetype === 'shared') {
        $user_dir = "/home/shared";
    }
    else{
        echo"Improper request format\n";
        exit;
    }

    if (preg_match('/\.\.(\/|\\\\)/', $filename) || in_array($file_name, $exclude)) {
        audit_log($_SESSION["user"] . " FAIL DOWNLOAD " . $file_name . " from " . $user_dir);
        header("Location: main");
        exit;
    }

    $file_path = $user_dir . '/' . $file_name;

    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        audit_log($_SESSION["user"] . " DOWNLOAD " . $file_name . " from " . $user_dir);
    } else {
        audit_log($_SESSION["user"] . " FAIL DOWNLOAD " . $file_name . " from " . $user_dir);
        echo "File not found";
    }
}

function sanitize(&$data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
}

function audit_log($message)
{
    $file = '/var/www/log.txt';
    $time_stamp = date('Y-m-d H:i:s');
    $log_message = $time_stamp . ' - ' . $message . PHP_EOL;

    file_put_contents($file, $log_message, FILE_APPEND | LOCK_EX);
}
?>