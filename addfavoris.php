<?php

include "main.php";

session_start();

$md5 = $_REQUEST['md5'];
$url = $_REQUEST['url'];

$class_favoris->addFavoris($md5);
$uri = $_SERVER['HTTP_REFERER'];

header("Location: $uri");
exit;

?>