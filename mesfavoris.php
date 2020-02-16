<?php

include "main.php";

$class_templateloader->showHead('mes_annonces');
$class_templateloader->openBody();

include "header.php";

$validation_annonce = "";
	
if(isset($_REQUEST['valid']))
{
	$validation_annonce = '<div class="validmsg">'."\n";
	$validation_annonce .= 'Votre annonce à bien été prise en compte elle sera modérée par notre équipe et valider entre 24 et 48h.'."\n";
	$validation_annonce .= '</div>'."\n";
}

$class_templateloader->loadTemplate("tpl/mesfavoris.tpl");
$class_templateloader->assign("{validation_annonce}",$validation_annonce);
if($isConnected)
{
	$SQL = "SELECT * FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
			
	$iduser = $req['id'];
	$r = "";
		
	$SQL = "SELECT COUNT(*) FROM pas_annonce WHERE iduser = $iduser";
	$reponse = $pdo->query($SQL);
	$req = $reponse->fetch();
	
	$final = '<table>'."\n";
	$final .= '<tr>'."\n";
	$final .= '<th>Poster le</th>'."\n";
	$final .= '<th>Photo</th>'."\n";
	$final .= '<th>Titre</th>'."\n";
	$final .= '<th>Valider</th>'."\n";
	$final .= '<th>Action</th>'."\n";
	$final .= '</tr>'."\n";
			
	if($req[0] == 0)
	{
		$final .= '</table>'."\n";
		$final .= '<br><br>'."\n";
		$final .= '<center><H1>Aucune annonce poster pour le moment</H1></center>';
		$final .= '<br><br>'."\n";
		$class_templateloader->assign("{block}",$final);
	}
	else
	{
		$block_row_message = new TemplateLoader();
		$block_row_message->loadTemplate("tpl/block_row_mesannonce.tpl");
		
		$SQL = "SELECT * FROM pas_annonce WHERE iduser = $iduser";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$md5annonce = $req['md5'];
			
			$block_row_message->reload();

			$date = $req['date_soumission'];
			$d = explode(" ",$date);
			$date_annonce = $d[0];
			$heure_annonce = $d[1];
						
			$d = explode("-",$date_annonce);
			$annee = $d[0];
			$mois = $d[1];
			$jour = $d[2];
						
			$date_soumission_annonce = "Le $jour/$mois/$annee à $heure_annonce";
			$block_row_message->assign("{date_annonce}",$date_soumission_annonce);
			
			$SQL = "SELECT COUNT(*) FROM pas_image WHERE md5annonce = '$md5annonce'";
			$u = $pdo->query($SQL);
			$_req = $u->fetch();
			
			$all_images = "";
						
			if($_req[0] == 0)
			{
				$all_images = '<img src="'.$url_script.'/images/noimage.jpg" width=110>';
			}
			else
			{
				$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5annonce'";
				$u = $pdo->query($SQL);
				while($_req = $u->fetch())
				{
					$all_images .= '<img src="'.$url_script.'/images/annonce/'.$_req['image'].'" width=110> ';
				}
			}
			
			$block_row_message->assign("{images}",$all_images);
			$block_row_message->assign("{titre}",$req['titre']);
			
			if($req['valider'] == 'yes')
			{
				$valider = '<b><font color=green>Oui</font></b>';
			}		
			else if($req['valider'] == 'no')
			{
				$valider = '<b><font color=red>Non</font></b>';
			}
			else if($req['valider'] == 'expired')
			{
				$valider = '<b><font color=red>Expirée</font></b>';
			}
			
			$deletelink = $url_script.'/mesannonces.php?action=2&md5='.$md5annonce;
			$editlink = 'editannonces.php?md5='.$req['md5'];
			
			$expired = "";
			if($req['valider'] == 'expired')
			{
				$expired = '<a href="deposer-une-annonce.php?step=2&md5'.$req['md5'].'" class="btnConfirm blue">Renouveller</a>';
			}
			
			$block_row_message->assign("{valider}",$valider);
			$block_row_message->assign("{delete_link}",$deletelink);
			$block_row_message->assign("{edit_link}",$editlink);
			$block_row_message->assign("{expired}",$expired);
			
			$r .= $block_row_message->getData();
		}
		
		$class_templateloader->assign("{block}",$final.$r.'</table>');
	}
}
else
{
	$connexion = new TemplateLoader();
	$connexion->loadTemplate("tpl/blockconnexion.tpl");
	$class_templateloader->assign("{block}",$connexion->getData());
}

$class_templateloader->show();

include "footer.php";

$class_templateloader->closeBody();
$class_templateloader->closeHTML();
?>