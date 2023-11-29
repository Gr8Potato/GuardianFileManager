<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css?foo">
    <link rel="icon" type="image/x-icon" href="Icons/lock.png">
    <script type="module" src="main.js"></script>
    <title>Guardian File Manager</title>
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
                <h1>Guardian File Manager</h1>
                <div id="status_container"><br></div>
            </div>

            <div class="logoutstuff">
                <form action="logouthandler" method="post">
                    <?php
                    echo '<input type="submit" class="btn" value="Logout ' . $_SESSION["user"] . '">';
                    ?>
                </form>
            </div>
        </div>
        </div>
    </header>

    <div class="container">
        <h1>Personal Files</h1>
        <div class="uploadstuff">
            <form id="upload_form" action="uploadhandler" method="post" enctype="multipart/form-data">
                <input class="btn" type="file" name="filesToUpload[]" id="fileToUpload" multiple>
                <input id="upload_button" type="submit" class="btn" value="Upload File" name="submit">
            </form>
            <br>
            <input type="button" id="sort_name_asc_p" class="btn" value="Sort by Name ↑">
                <input type="button" id="sort_name_desc_p" class="btn" value="Sort by Name ↓">
                <input type="button" id="sort_date_asc_p" class="btn" value="Sort by Date ↑">
                <input type="button" id="sort_date_desc_p" class="btn" value="Sort by Date ↓">
                <br>
                <br>
            <table id="personaltable">
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

                    $user = $_SESSION["user"];
                    $ldap_adr = "ldaps://test-vm";
                    $ldap_usr = "uid=$user,ou=People,dc=nodomain";
                    $ldap_con = ldap_connect("ldaps://test-vm");
                    ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
                    ldap_bind($ldap_con, $ldap_usr, $ldap_pass);

                    $search = ldap_search($ldap_con, "ou=People,dc=nodomain", "(uid=$user)");
                    $entries = ldap_get_entries($ldap_con, $search);

                    $jobTitle = $entries[0]["title"][0] ?? "";
                    $desc = $entries[0]["description"][0] ?? "";

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
                        echo '<td class="actions">';
                        echo "<button class='preview-button' data-filetype='personal' data-filename='" . htmlspecialchars($file['name']) . "'><img src='Icons/eye.png' alt='Preview'></button>";
                        echo "<button class='download-button' data-filetype='personal' data-filename='" . htmlspecialchars($file['name']) . "'><img src='Icons/download.png' alt='Download'></button>";
                        echo "<button class='delete-button' data-filetype='personal' data-filename='" . htmlspecialchars($file['name']) . "'><img src='Icons/trash.png' alt='Delete'></button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        if ($desc == "guardian") {
            echo "<h1>Shared Files</h1>";
            echo '
    <div class="container" id="shared">';

            if ($jobTitle !== 'nodel') {
                echo '
    <div class="uploadstuff">
    <form id="upload_form" action="shareduploadhandler" method="post" enctype="multipart/form-data">

        <input class="btn" type="file" name="filesToUpload[]" id="fileToUpload" multiple>
        <input id="upload_button" type="submit" class="btn" value="Upload File" name="submit">
    </form>
    <br>
        </div>';
            }

            echo '
            <input type="button" id="sort_name_asc_s" class="btn" value="Sort by Name ↑">
            <input type="button" id="sort_name_desc_s" class="btn" value="Sort by Name ↓">
            <input type="button" id="sort_date_asc_s" class="btn" value="Sort by Date ↑">
            <input type="button" id="sort_date_desc_s" class="btn" value="Sort by Date ↓">
            <br>
            <br>

        <table id="sharetable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Creation Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';
            $user_dir = "/home/" . "shared";
            foreach (new DirectoryIterator($user_dir) as $fileInfo) {
                if ($fileInfo->isDot() || in_array($fileInfo->getFilename(), $exclude)) { //excludes . and .. as well as the excluded files & folders
                    continue;
                }
                $share_files[] = [
                    'name' => $fileInfo->getFilename(),
                    'date' => date("m/d/Y", $fileInfo->getMTime()),
                    'id' => $fileInfo->getInode()
                ];
            }

            foreach ($share_files as $file) {
                echo "<tr id='file" . htmlspecialchars($file['id']) . "'>";
                echo "<td>" . htmlspecialchars($file['name']) . "</td>";
                echo "<td>" . $file['date'] . "</td>";
                echo '<td class="actions">';
                echo "<button class='preview-button' data-filetype='shared' data-filename='" . htmlspecialchars($file['name']) . "'><img src='Icons/eye.png' alt='Preview'></button>";
                echo "<button class='download-button' data-filetype='shared' data-filename='" . htmlspecialchars($file['name']) . "'><img src='Icons/download.png' alt='Download'></button>";
                if ($jobTitle !== "nodel") {
                    echo "<button class='delete-button' data-filetype='shared' data-filename='" . htmlspecialchars($file['name']) . "'><img src='Icons/trash.png' alt='Delete'></button>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo '
</tbody>
</table>
</div>
';
        }
        ?>
</body>

</html>