<?php
session_start();

$_SESSION['language'] = $_GET['language'];

header("Location: index.php");
