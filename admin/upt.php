<?php

include "version.php";
include "class.update.php";

$update = new Update();
$update->startUpdate($version);

?>