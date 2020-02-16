<?php

include "main.php";

session_start();

$md5 = $_REQUEST['md5'];
$url = $_REQUEST['url'];

$class_favoris->removeFavoris($md5);
$uri = $_SERVER['HTTP_REFERER'];

header("Location: $uri");
exit;

?>