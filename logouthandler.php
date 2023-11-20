<?php
session_start(); // we need to do this to access the session variables
session_destroy();

header("Location: login");
?>