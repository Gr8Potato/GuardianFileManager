<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login");
    exit;
}

$user_dir = "/home/" . $_SESSION["user"];

if (!file_exists($user_dir)) {
    if (!mkdir($user_dir, 0775, true)) {
        echo json_encode(['error' => 'Failed to create directory.']);
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
        echo json_encode(['error' => 'File upload error code: ' . $error]);
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
        header("Location: main?status=uploadsuccess"); //we're refreshing the browser here. appending the entry with js is out of reach currently        
    } else {
        echo json_encode(['error' => 'There was some error moving the file to the upload directory.']);
    }
} else {
    echo json_encode(['error' => 'No file was uploaded.']);
}
?>