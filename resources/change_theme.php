<?php
session_start();

if($_SESSION['theme']=='light')
	$_SESSION['theme']='dark';
else
	$_SESSION['theme']='light';

header("Location: index.php");
