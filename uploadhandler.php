<?php
session_start();

if (isset($_FILES['filesToUpload'])) {
    $user_dir = "/home/" . $_SESSION["user"];

    foreach ($_FILES['filesToUpload']['name'] as $key => $name) {

        $tmpName = $_FILES['filesToUpload']['tmp_name'][$key]; //just makes it easier to combine

        $fileName = basename($_FILES['filesToUpload']['name'][$key]);
        $destination = $user_dir . '/' . $fileName;

        if ($_FILES['filesToUpload']['error'][$key] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($tmpName, $destination)) {
                header("Location: main?error=file(s) uploaded successfully");
            } else {
                header("Location: main?error=file upload error");
            }
        } else {
            // Handle file upload error
            header("Location: main?error=no file(s) uploaded");
        }
    }
} else {
    header("Location: main?error=no file(s) uploaded");
}
?>