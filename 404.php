<?php

include "main.php";

$class_templateloader->showHead('404');	
$class_templateloader->openBody();

include "header.php";

$class_templateloader->loadTemplate("tpl/404.tpl");
$class_templateloader->assign("{url_script}",$url_script);
$class_templateloader->show();
	
include "footer.php";

$class_templateloader->closeBody();
$class_templateloader->closeHTML();
	
?>