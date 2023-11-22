<?php
session_start();

if (isset($_GET['filename'])) {

    $exclude = array('.bash_history', '.cache', '.bash_logout', '.config', '.local', '.bashrc', '.profile', 'snap');
    $file_name = $_GET['filename'];

    if (preg_match('/\.\.(\/|\\\\)/', $filename) || in_array($file_name, $exclude)) {
        echo 'Permission denied.';
        audit_log($_SESSION["user"] . " FAILED to PREVIEW " . $_GET['filename']);
        exit;
    }

    $filename = $_GET['filename'];
    $user_dir = "/home/" . $_SESSION["user"];
    $file_path = $user_dir . '/' . $filename;

    if (file_exists($file_path)) {
        $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

        switch ($file_extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                audit_log($_SESSION["user"] . " PREVIEW " . $_GET['filename']);
                header('Content-Type: image/' . $file_extension);
                readfile($file_path);
                break;
            case 'pdf':
                audit_log($_SESSION["user"] . " PREVIEW " . $_GET['filename']);
                header('Content-Type: application/pdf');
                readfile($file_path);
                break;
            case 'txt':
                audit_log($_SESSION["user"] . " PREVIEW " . $_GET['filename']);
                header('Content-Type: text/plain');
                readfile($file_path);
                break;
            default:
                audit_log($_SESSION["user"] . " FAILED PREVIEW " . $_GET['filename']);
                echo 'Unsupported file type for preview.';
        }
    } else {
        audit_log($_SESSION["user"] . " FAILED PREVIEW " . $_GET['filename']);
        echo 'File not found.';
    }
} else {
    audit_log($_SESSION["user"] . " FAILED PREVIEW " . $_GET['filename']);
    echo 'No filename provided.';
}

function audit_log($message)
{
    $file = '/var/www/log.txt';
    $time_stamp = date('Y-m-d H:i:s');
    $log_message = $time_stamp . ' - ' . $message . PHP_EOL;

    file_put_contents($file, $log_message, FILE_APPEND | LOCK_EX);
}
?>