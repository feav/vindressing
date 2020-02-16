<?php

/* Etape 1 - Download de la mise à jour */
include "version.php";
include "class.update.php";

$update = new Update();
$update->downloadUpdate($version);

?>