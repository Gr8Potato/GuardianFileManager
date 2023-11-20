<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="module" src="main.js"></script>
    <script defer nomodule></script>
    <title>Login</title>


</head>

<body>

    <div class="container">
        <h1>File System Management</h1>
        <form action="loginhandler" method="post">
            <h1>Welcome!</h1>
            <div class="form-group">
                <label for="name">Username</label>
                <input id="username" name="name" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="pass">Password</label>
                <input id="password" name="pass" type="password" class="form-control" autocomplete="new-password"
                    required>
            </div>
            <input type="submit" class="btn" value="Login">
        </form>
    </div>
    <div id="errorMessageContainer">

    </div>

</body>

</html>