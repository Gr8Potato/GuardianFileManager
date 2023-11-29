<?php
session_start();

if (isset($_GET['filename'], $_GET['filetype'])) {
    $filename = $_GET['filename'];
    $filetype = $_GET['filetype'];

    $exclude = array('.bash_history', '.cache', '.bash_logout', '.config', '.local', '.bashrc', '.profile', 'snap');

    if (preg_match('/\.\.(\/|\\\\)/', $filename) || in_array($file_name, $exclude)) {
        echo 'Permission denied.';
        audit_log($_SESSION["user"] . " FAILED to PREVIEW " . $_GET['filename']);
        exit;
    }
    
    sanitize($filename);
    sanitize($filetype);

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