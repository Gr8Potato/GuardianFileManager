<?php
session_start(); // we need to do this to access the session variables

audit_log($_SESSION["user"] . " LOGOUT");

session_destroy();

header("Location: login");

function audit_log($message)
{
    $file = '/var/www/log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = $timestamp . ' - ' . $message . PHP_EOL;

    file_put_contents($file, $logMessage, FILE_APPEND | LOCK_EX);
}
?>