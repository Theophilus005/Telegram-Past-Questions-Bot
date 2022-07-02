<?php
//Connection to database
$hostname = "localhost";
$username = "id13708660_pdf_user";
$password = "6i9d2PzLTr_9y])<";
$dbname = "id13708660_pdf";

$conn = new mysqli($hostname, $username, $password, $dbname);
if(mysqli_connect_error()) {
    die("Error Connecting to database: ".mysqli_connect_error());
} 
?>