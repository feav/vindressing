<?php

/* Etape 3 - Execution du fichier d'update */
include "version.php";
include "class.update.php";

$update = new Update();
$update->executeUpdate();

?>