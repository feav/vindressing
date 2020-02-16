<?php

include "../main.php";

$md5 = AntiInjectionSQL($_REQUEST['md5']);
$md5 = str_replace("-thumb","",$md5);

$SQL = "DELETE FROM pas_image WHERE image = '$md5'";
$pdo->query($SQL);

?>