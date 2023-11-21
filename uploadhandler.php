<?php
session_start();

if (isset($_FILES['filesToUpload'])) {
    $user_dir = "/home/" . $_SESSION["user"];

    foreach ($_FILES['filesToUpload']['name'] as $key => $name) {
        $tmpName = $_FILES['filesToUpload']['tmp_name'][$key];
        $originalFileName = basename($_FILES['filesToUpload']['name'][$key]);
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $fileNameWithoutExtension = pathinfo($originalFileName, PATHINFO_FILENAME);
        $destination = $user_dir . '/' . $originalFileName;

        $counter = 1;
        while (file_exists($destination)) {
            $newFileName = $fileNameWithoutExtension . '_' . $counter;
            if ($fileExtension) {
                $newFileName .= '.' . $fileExtension;
            }
            $destination = $user_dir . '/' . $newFileName;
            $counter++;
        }

        if ($_FILES['filesToUpload']['error'][$key] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($tmpName, $destination)) {
                // Log each successful file upload
                audit_log($_SESSION["user"] . " UPLOADED " . $newFileName . " to /home/" . $_SESSION["user"]);
                header("Location: main?error=file(s) uploaded successfully");
            } else {
                header("Location: main?error=file upload error");
            }
        } else {
            header("Location: main?error=no file(s) uploaded");
        }
    }
} else {
    header("Location: main?error=no file(s) uploaded");
}

function audit_log($message)
{
    $file = '/var/www/log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = $timestamp . ' - ' . $message . PHP_EOL;

    file_put_contents($file, $logMessage, FILE_APPEND | LOCK_EX);
}
?>