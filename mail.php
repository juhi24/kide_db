<?php
$username = $_POST["name"];
$email = $_POST["email"];
$message = $_POST["message"];

$formcontent = "Lähettäjä: $name \n Viesti: $message";
$recipient = "jussi.tiira@helsinki.fi";
$subject = "[kide_db] Account request";
$mailheader = "From: $email \r\n";

mail($recipient, $subject, $formcontent, $mailheader) OR die("Error!");
echo "<h2>Thank you!</h2>";
echo "<p>We will handle your request as soon as possible. Your login credentials will be sent to the following email address: $email</p>";
?>
