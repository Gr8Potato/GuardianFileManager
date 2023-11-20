<?php
echo 'HI';

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login");
    exit;
}

$userDir = "/home/" . $_SESSION["user"];

if (!file_exists($userDir)) {
    if (!mkdir($userDir, 0775, true)) {
        echo json_encode(['error' => 'Failed to create directory.']);
        exit;
    }
}

// check if the file has been uploaded via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $fileTmpPath = $_FILES['fileToUpload']['tmp_name'];
    $fileName = $_FILES['fileToUpload']['name'];
    $fileSize = $_FILES['fileToUpload']['size'];
    $fileType = $_FILES['fileToUpload']['type'];
    $error = $_FILES['fileToUpload']['error'];

    // check if there's any error with the file
    if ($error !== UPLOAD_ERR_OK) {
        echo json_encode(['error' => 'File upload error code: ' . $error]);
        exit;
    }

    // sanitize the file name to prevent directory traversal or other potential hazards
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // define the path to save the uploaded file
    $dest_path = $userDir . '/' . $fileName;

    // if the file already exists, add a number to the file name
    $counter = 1;
    while (file_exists($dest_path)) {
        $newFileName = $fileNameCmps[0] . '_' . $counter . '.' . $fileExtension;
        $dest_path = $userDir . '/' . $newFileName;
        $counter++;
    }

    // Move the file from the temporary directory to the user's directory
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        echo json_encode(['success' => 'File uploaded successfully.']);
    } else {
        echo json_encode(['error' => 'There was some error moving the file to the upload directory.']);
    }
} else {
    echo json_encode(['error' => 'No file was uploaded.']);
}
?>
