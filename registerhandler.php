<?php

$user = $_POST["name"];
$ldap_pass = $_POST["pass"];

sanitize($user);
$user = strtolower($user);
sanitize($ldap_pass);

$ldap_adr = "ldaps://test-vm";
$ldap_usr = "uid=$user,ou=People,dc=nodomain";

//connect to ldap
$ldap_con = ldap_connect($ldap_adr);
if ($ldap_con) {
    ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
    $r = ldap_bind($ldap_con, "cn=admin,dc=nodomain", "admin");

    //checks for used names
    $search = ldap_search($ldap_con, "ou=People,dc=nodomain", "(uid=$user)");
    $entries = ldap_get_entries($ldap_con, $search);
    if ($entries["count"] > 0) {
        audit_log("FAIL CREATE " . $_POST["name"]);
        redirect("login", "User already exists");
    } else {

        //fill out mandatory forms
        $ldaprecord['cn'] = $user;
        $ldaprecord['uid'] = $user;
        $ldaprecord['sn'] = "AUTOMATED";
        $ldaprecord['objectclass'] = "inetOrgPerson";

        $r = ldap_add($ldap_con, $ldap_usr, $ldaprecord);
        if (!$r) {
            audit_log("FAIL CREATE " . $_POST["name"]);
            redirect("login", "Failed to make account for " . $_POST["name"]);

        }

        //have to handle password seperately
        ldap_exop_passwd($ldap_con, $ldap_usr, null, $ldap_pass);
        mkdir("/home/" . $user);
        audit_log("CREATED $user");
        redirect("login", "Successfully created account");
    }
} else {
    audit_log("FAIL CREATE " . $_POST["name"]);
    redirect("login", "LDAP connection failed");
}


function sanitize(&$data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
}

function redirect($site, $error)
{
    $error_msg = urlencode($error);
    header("Location: $site?error=$error_msg");
}

function audit_log($message)
{
    $file = '/var/www/log.txt';
    $time_stamp = date('Y-m-d H:i:s');
    $log_message = $time_stamp . ' - ' . $message . PHP_EOL;

    file_put_contents($file, $log_message, FILE_APPEND | LOCK_EX);
}
?>