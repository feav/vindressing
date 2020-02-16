<?php

include "main.php";

$url = AntiInjectionSQL($_REQUEST['url']);

$array = explode("/",$url);
$url = $array[count($array)-1];

$SQL = "DELETE FROM pas_image WHERE image = '$url'";
$pdo->query($SQL);

?>