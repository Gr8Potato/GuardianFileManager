<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <script type="module" src="main.js"></script>
    <title>File Management System</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION["user"])) {
        header("Location: login");
        exit;
    }
    ?>
    <header>
        <div class="header">
            <div class="titleHeader">

                <form id="upload_form" action="uploadhandler" method="post" enctype="multipart/form-data">
                    Select file to upload:
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <input id="upload_button" type="submit" class="btn" value="Upload File" name="submit">
                </form>
                <div id="status_container"></div>

                <h1>File Management System</h1>
                <form action="logouthandler" method="post">
                    <input type="submit" class="btn" value="Logout">
                </form>
            </div>
        </div>
    </header>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Creation Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user_dir = "/home/" . $_SESSION["user"];

            if (!is_dir($user_dir)) {
                echo "<tr><td colspan='3'>Directory not found</td></tr>";
            }

            $files = [];
            $exclude = array('.bash_history', '.cache', '.bash_logout', '.config', '.local', '.bashrc', '.profile', 'snap'); //we dont want the user to see or change these directories
            
            foreach (new DirectoryIterator($user_dir) as $fileInfo) {
                if ($fileInfo->isDot() || in_array($fileInfo->getFilename(), $exclude)) { //excludes . and .. as well as the excluded files & folders
                    continue;
                }

                $files[] = [
                    'name' => $fileInfo->getFilename(),
                    'date' => date("m/d/Y", $fileInfo->getMTime()),
                    'id' => $fileInfo->getInode()
                ];
            }

            //creates a row for each file, with an ID for each row. the id design choice should not posses a security risk to the system
            foreach ($files as $file) {
                echo "<tr id='file" . htmlspecialchars($file['id']) . "'>";
                echo "<td>" . htmlspecialchars($file['name']) . "</td>";
                echo "<td>" . $file['date'] . "</td>";
                echo "<td>";
                echo "<button><img src='Icons/eye.png' alt='Preview'></button>";
                echo "<button><img src='Icons/download.png' alt='Download'></button>";
                echo "<button class='delete-button' data-filename='" . htmlspecialchars($file['name']) . "'><img src='Icons/trash.png' alt='Delete'></button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>