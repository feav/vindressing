<?php

/* Etape 4 - Suppresion fichier update.php */
include "version.php";
include "class.update.php";

$update = new Update();
$update->deleteUpdate();

?>