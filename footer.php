<?php

$class_templateloader->loadTemplate("tpl/footer.tpl");
$class_templateloader->assign("{socialbox}",$class_social->getSocialBox());
$class_templateloader->assign("{footer_colonne}",$class_footer->getFooter());
$class_templateloader->assign("{copyright}",$class_footer->getCopyright());
$class_templateloader->show();

/* Code Tawk.to */
$tawk = getConfig("code_tawk");
echo $tawk;

?>