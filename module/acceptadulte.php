<?php

$url = $_REQUEST['url'];
session_start();
$_SESSION['adulte'] = 1;
header("Location: $url");
exit;

?>