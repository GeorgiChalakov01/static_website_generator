<?php
require_once "functions.php";
require_once "config.php";

$company = $_POST['company'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$client_email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

$email_title = 'Email From Customer';
$email_body = "Company: $company<br>Name: $first_name $last_name<br>Client's email: $client_email<br>Message:<br>$message";


$email_status = send_mail($smtp_server, $sender_email, $password, $sender_name, $recipient_email, $recipient_name, $email_title, $email_body);

header("Location: test_index.php?email_status=$email_status#contacts");
