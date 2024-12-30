<?php
require_once "functions.php";
require_once "config.php";

$email_title = 'Monthly Report for zaivodlogistics.com';
$email_body = "";

$db = new SQLite3('db.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
$db->enableExceptions(true);

$visits = get_visitor_data($db);
foreach ($visits as $visit) {
	$email_body = $email_body . $visit['COUNTRY'] . ': ' . $visit['USER_COUNT'] . "<br>";
}

$db->close();

$email_status = send_mail($smtp_server, $sender_email, $password, $sender_name, $recipient_email, $recipient_name, $email_title, $email_body);

header("Location: test_index.php?email_status=$email_status#contacts");
