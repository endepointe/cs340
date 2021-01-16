<?php
/* Change for your username and password for phpMyAdmin*/
define('DB_SERVER', '<your_info>');
define('DB_USERNAME', '<your_info>');
define('DB_PASSWORD', '<your_info>');
define('DB_NAME', '<your_info>');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
