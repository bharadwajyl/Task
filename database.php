<?php
$servername = "localhost";
$username = "id16734150_auriclelevioosa";
$password = "TeamBboysdreamsfell@2016";
$dbname = "id16734150_auricle";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
echo'<i class="fa fa-exclamation-circle"></i> Please try after sometime';
return 1;
}
?>