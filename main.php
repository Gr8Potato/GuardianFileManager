<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Login form design</title>
</head>
<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login");
}
?>
<header>
    <div class="header">
        <div class="titleHeader">

            <input type="submit" class="btn" value="Upload">
            <h1>File Management System</h1>
            <input type="submit" class="btn" value="Logout">


        </div>

    </div>

</header>

<body>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            session_start();
            ?>
        </tbody>
    </table>
</body>

</html>

<body>

</body>

</html>