<?php

include "main.php";

$titleSEO = getTitleSEO('deposer_une_annonce');
$descriptionSEO = getDescriptionSEO('deposer_une_annonce');

$template = getConfig("template");

$class_templateloader->showHead('deposer_une_annonce');
$class_templateloader->openBody();

include "header.php";

$md5 = AntiInjectionSQL($_REQUEST['md5']);

$md5 = explode("?",$md5);
$md5 = $md5[0];

$SQL = "SELECT * FROM pas_annonce WHERE md5 = '$md5'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

$titre = $req['titre'];

$class_templateloader->loadTemplate("tpl/cancel_paiement.tpl");

$class_templateloader->assign("{annonce}",$titre);
$class_templateloader->assign("{md5}",$md5);
$class_templateloader->assign("{url_script}",$url_script);

$class_templateloader->show();

include "footer.php";

$class_templateloader->closeBody();
$class_templateloader->closeHTML();
exit;

?>