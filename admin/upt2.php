<?php

/* Etape 2 - Dezippe de la mise à jour */
include "version.php";
include "class.update.php";

$update = new Update();
$update->dezipUpdate($version);

?>