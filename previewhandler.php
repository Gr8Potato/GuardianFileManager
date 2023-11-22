<?php
session_start();

if (isset($_GET['filename'])) {

    $exclude = array('.bash_history', '.cache', '.bash_logout', '.config', '.local', '.bashrc', '.profile', 'snap');
    $file_name = $_GET['filename'];

    if (preg_match('/\.\.(\/|\\\\)/', $filename) || in_array($file_name, $exclude)) {
        echo 'Permission denied.';
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
                header('Content-Type: image/' . $file_extension);
                readfile($file_path);
                break;
            case 'pdf':
                header('Content-Type: application/pdf');
                readfile($file_path);
                break;
            case 'txt':
                header('Content-Type: text/plain');
                readfile($file_path);
                break;
            default:
                echo 'Unsupported file type for preview.';
        }
    } else {
        http_response_code(404);
        echo 'File not found.';
    }
} else {
    http_response_code(400);
    echo 'No filename provided.';
}
?>