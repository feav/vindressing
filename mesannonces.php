<?php

include "main.php";

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
{
	// creating a cut resource
    $cut = imagecreatetruecolor($src_w, $src_h);

    // copying relevant section from background to the cut resource
	imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    
	// copying relevant section from watermark to the cut resource
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    
	// insert cut resource to destination image
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
} 

function addFiligrame($filename,$logo)
{
	// On detecte ici l'extension
	$extension = explode(".",$filename);
	$extension = $extension[count($extension)-1];
	$extension = strtolower($extension);
	
	if($extension == 'jpg')
	{
		$img = imagecreatefromjpeg($filename);
	}
	else if($extension == 'jpeg')
	{
		$img = imagecreatefromjpeg($filename);
	}
	else if($extension == 'png')
	{
		$img = imagecreatefrompng($filename);
	}
	else
	{
		return "0";
	}
	
	$extensionlogo = explode(".",$logo);
	$extensionlogo = $extensionlogo[count($extensionlogo)-1];
	$extensionlogo = strtolower($extensionlogo);
	
	if($extensionlogo == 'jpg')
	{
		$imglogo = imagecreatefromjpeg($logo);
	}
	else if($extensionlogo == 'jpeg')
	{
		$imglogo = imagecreatefromjpeg($logo);
	}
	else if($extensionlogo == 'png')
	{
		$imglogo = imagecreatefrompng($logo);
	}
	else
	{
		return "0";
	}
	
	$widthlogo = imagesx($imglogo);
	$heightlogo = imagesy($imglogo);
	imagecopymerge_alpha($img, $imglogo, 10, 10, 0, 0, $widthlogo, $heightlogo, 100);
	
	if($extension == 'jpg')
	{
		imagejpeg($img,$filename);
	}
	else if($extension == 'jpeg')
	{
		imagejpeg($img,$filename);
	}
	else if($extension == 'png')
	{
		imagepng($img,$filename);
	}
}

function generateThumb($filename)
{
	$widthThumb = 192;
	$heightThumb = 149;
	
	// On detecte ici l'extension
	$extension = explode(".",$filename);
	$extension = $extension[count($extension)-1];
	$filenamecut = str_replace(".".$extension,"",$filename);
	$extension = strtolower($extension);
	
	if($extension == 'jpg')
	{
		$img = imagecreatefromjpeg($filename);
	}
	else if($extension == 'jpeg')
	{
		$img = imagecreatefromjpeg($filename);
	}
	else if($extension == 'png')
	{
		$img = imagecreatefrompng($filename);
	}
	else
	{
		return "0";
	}
		
	$width = imagesx($img);
	$height = imagesy($img);

	if($width > $height)
	{
		$ratio = $widthThumb / $width;
	}
	else
	{
		$ratio = $heightThumb / $height;
	}

	$thumb = imagecreatetruecolor($widthThumb,$heightThumb);
	$color = imagecolorallocate($thumb,180,180,180);
	imagefill($thumb,0,0,$color);
	$ratioWidth = $width * $ratio;
	$ratioHeight = $height * $ratio;
	$ratioPosHeight = 0;
	$ratioPosWidth = 0;

	if($ratioHeight < $heightThumb)
	{
		$ratioPosHeight = ($heightThumb/2)-($ratioHeight/2);
	}

	if($ratioWidth < $widthThumb)
	{
		$ratioPosWidth = ($widthThumb/2)-($ratioWidth/2);
	}

	imagecopyresampled($thumb,$img,$ratioPosWidth,$ratioPosHeight,0,0,$ratioWidth,$ratioHeight,$width,$height);
	imagejpeg($thumb,$filenamecut."-thumb.".$extension);
	
	return "1";
}

if(isset($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 1)
	{
		/* On recupere l'identifiant de l'utilisateur */
		$SQL = "SELECT * FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$iduser = $req['id'];
		
		$categorie = $_REQUEST['categorie'];
		$type_annonce = $_REQUEST['type_annonce'];
		$titre = $_REQUEST['titre'];
		$texte = $_REQUEST['texte'];
		$prix = $_REQUEST['prix'];
		$codepostal = $_REQUEST['codepostal'];
		$telephone = $_REQUEST['telephone'];
		$telephone = str_replace(" ","",$telephone);
		$telephone = str_replace(".","",$telephone);
		
		$texte = nl2br($texte);
		
		$titre = strip_tags($titre);
		$texte = strip_tags($texte);
		$titre = str_replace("'","''",$titre);
		$texte = str_replace("'","''",$texte);
		$codepostal = explode("-",$codepostal);
		$codepostal = trim($codepostal[0]);
		
		$md5annonce = md5(microtime());
		
		$SQL = "INSERT INTO pas_annonce VALUES ('',$iduser,'$md5annonce','$titre','$texte','$type_annonce',$categorie,'$prix','$codepostal','no',NOW(),0,'$telephone')";
		$pdo->query($SQL);
		
		// On envoie un email pour dire que l'annonce va être prise en compte sous 48h par exemple après modération
		
		$chemin = $_SERVER["DOCUMENT_ROOT"]."/clone/pas/images/annonce/";
		if($_FILES['image1']['tmp_name'] != '')
		{
			$tmp_file = $_FILES['image1']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['image1']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['image1']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$name_file1 = md5(microtime()).".".$extension;
			if( !move_uploaded_file($tmp_file, $chemin . $name_file1) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file1");
			}

			$SQL = "INSERT INTO pas_image VALUES ('','$md5annonce','$name_file1')";
			$pdo->query($SQL);
			
			generateThumb($chemin.$name_file1);
			addFiligrame($chemin.$name_file1,"images/mini-logo-pas.png");
		}
		
		if($_FILES['image2']['tmp_name'] != '')
		{
			$tmp_file = $_FILES['image2']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['image2']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['image2']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$name_file2 = md5(microtime()).".".$extension;
			if( !move_uploaded_file($tmp_file, $chemin . $name_file2) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file2");
			}						
			
			$SQL = "INSERT INTO pas_image VALUES ('','$md5annonce','$name_file2')";
			$pdo->query($SQL);
			
			generateThumb($chemin.$name_file2);
			addFiligrame($chemin.$name_file2,"images/mini-logo-pas.png");
		}
		
		if($_FILES['image3']['tmp_name'] != '')
		{
			$tmp_file = $_FILES['image3']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['image3']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['image3']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$name_file3 = md5(microtime()).".".$extension;
			if( !move_uploaded_file($tmp_file, $chemin . $name_file3) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file");
			}				

			$SQL = "INSERT INTO pas_image VALUES ('','$md5annonce','$name_file3')";
			$pdo->query($SQL);
			
			generateThumb($chemin.$name_file3);
			addFiligrame($chemin.$name_file3,"images/mini-logo-pas.png");
		}
		
		if($_FILES['image4']['tmp_name'] != '')
		{
			$tmp_file = $_FILES['image4']['tmp_name'];
			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['image4']['type'];
			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['image4']['name'];
			$extension = explode(".",$name_file);
			$extension = $extension[count($extension)-1];
			$name_file4 = md5(microtime()).".".$extension;
			if( !move_uploaded_file($tmp_file, $chemin . $name_file4) )
			{
				exit("Impossible de copier le fichier dans $chemin$name_file4");
			}		

			$SQL = "INSERT INTO pas_image VALUES ('','$md5annonce','$name_file4')";
			$pdo->query($SQL);
			
			generateThumb($chemin.$name_file4);
			addFiligrame($chemin.$name_file4,"images/mini-logo-pas.png");
		}

		header("Location: deposer-une-annonce.php?valid=1");
	}
	
	/* Supprimer une annonce */
	if($action == 2)
	{
		$md5 = $_REQUEST['md5'];
		$SQL = "DELETE FROM pas_annonce WHERE md5 = '$md5'";
		$pdo->query($SQL);
		
		header("Location: $url_script/mesannonces.php");
	}
}

$class_templateloader->showHead('mes_annonces');
$class_templateloader->openBody();

include "header.php";

$validation_annonce = "";
	
if(isset($_REQUEST['valid']))
{
	$validation_annonce = '<div class="validmsg">'."\n";
	$validation_annonce .= $message_update_annonce."\n";
	$validation_annonce .= '</div>'."\n";
}

$class_templateloader->loadTemplate("tpl/mesannonces.tpl");
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
	$final .= '<th>'.$tableau_mes_annonces_date_post.'</th>'."\n";
	$final .= '<th>'.$tableau_mes_annonces_photo.'</th>'."\n";
	$final .= '<th>'.$tableau_mes_annonces_titre.'</th>'."\n";
	$final .= '<th>'.$tableau_mes_annonces_valider.'</th>'."\n";
	$final .= '<th style="width: 190px;">'.$btn_action.'</th>'."\n";
	$final .= '</tr>'."\n";
			
	if($req[0] == 0)
	{
		$final .= '</table>'."\n";
		$final .= '<br><br>'."\n";
		$final .= '<center><H1>'.$titre_aucune_annonce_mes_annonces.'</H1></center>';
		$final .= '<br><br>'."\n";
		$class_templateloader->assign("{block}",$final);
	}
	else
	{
		$block_row_message = new TemplateLoader();
		$block_row_message->loadTemplate("tpl/block_row_mesannonce.tpl");
		
		$x=0;
		
		$SQL = "SELECT * FROM pas_annonce WHERE iduser = $iduser ORDER BY id DESC";
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
				$all_images = '<img src="'.$url_script.'/images/noimage.jpg" style="width:25%;">';
			}
			else
			{
				$count_image = $_req[0];
				$pourcent_image = ceil(100/$count_image);
				
				$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5annonce'";
				$u = $pdo->query($SQL);
				while($_req = $u->fetch())
				{
					$img = $_req['image'];
					$img = explode(".",$img);
					$img = $img[0]."-thumb.".$img[1];
					$all_images .= '<img src="'.$url_script.'/images/annonce/'.$img.'" style="width:20%;"> ';
				}
			}
			
			$block_row_message->assign("{images}",$all_images);
			$block_row_message->assign("{titre}",$req['titre']);
			
			if($req['status'] == 'WAIT_VIREMENT')
			{
				$valider = '<b><font color=red>'.$status_annonce_wait_virement.'</font></b>';
			}
			else if($req['status'] == 'WAIT_CHEQUE')
			{
				$valider = '<b><font color=red>'.$status_annonce_wait_cheque.'</font></b>';
			}
			else if($req['status'] == 'WAIT_MOBICASH')
			{
				$valider = '<b><font color=red>'.$status_annonce_wait_validation.'</font></b>';
			}
			else if($req['status'] == 'WAITING_PAID')
			{
				$valider = '<b><font color=red>'.$status_annonce_wait_paid.'</font></b>';
			}
			else
			{
				if($req['valider'] == 'yes')
				{
					$valider = '<b><font color=green>'.$etat_annonce_valider.'</font></b>';
				}		
				else if($req['valider'] == 'no')
				{
					$valider = '<b><font color=red>'.$etat_annonce_nonvalider.'</font></b>';
				}
				else if($req['valider'] == 'expired')
				{
					$valider = '<b><font color=red>'.$etat_annonce_expiree.'</font></b>';
				}
			}
			
			$deletelink = $url_script.'/mesannonces.php?action=2&md5='.$md5annonce;
			$editlink = 'editannonces.php?md5='.$req['md5'];
			
			/* Block button action optimisation */
			$block_btn = '<a href="javascript:void(0);" onclick="showOption(\''.$x.'\');" class="btnConfirm">';
			$block_btn .= $btn_mesannonce_plus_option;
			$block_btn .= '</a>';
			$block_btn .= '<div class="more_option_action" id="more_option_action-'.$x.'">';
			$block_btn .= '<div class="item_option"><a href="'.$deletelink.'">'.$btn_supprimer.'</a></div>';
			$block_btn .= '<div class="item_option"><a href="'.$editlink.'">'.$btn_modifier.'</a></div>';
			if($req['valider'] == 'yes')
			{
				if($req['status'] == 'WAIT_VIREMENT' || $req['status'] == 'WAIT_CHEQUE' || $req['status'] == 'WAIT_MOBICASH')
				{
					// Pas d'affichage
				}
				else if($req['status'] == 'WAITING_PAID')
				{
					$block_btn .= '<div class="item_option"><a href="'.$url_script.'/paiement.php?md5='.$req['md5'].'">'.$btn_mesannonce_payer.'</a></div>';
				}
				else
				{
					$block_btn .= '<div class="item_option"><a href="showannonce.php?md5='.$req['md5'].'">'.$btn_mesannonce_voir.'</a></div>';
				}
			}
			else
			{
				if($req['status'] == 'WAITING_PAID')
				{
					$block_btn .= '<div class="item_option"><a href="'.$url_script.'/paiement.php?md5='.$req['md5'].'">'.$btn_mesannonce_payer.'</a></div>';
				}
			}
			if($req['valider'] == 'expired')
			{
				$block_btn .= '<div class="item_option"><a href="'.$url_script.'/addoptionannonce.php?md5='.$req['md5'].'">'.$btn_mesannonce_renouveller.'</a></div>';
			}
			$block_btn .= '</div>';
			
			$block_row_message->assign("{btn_action}",$block_btn);
			
			$x++;
			
			$block_row_message->assign("{valider}",$valider);
			
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

/* Plugins */
$data = $class_plugin->useTemplate($class_templateloader->getData());
$class_templateloader->setData($data);

$class_templateloader->show();

include "footer.php";

$class_templateloader->closeBody();
$class_templateloader->closeHTML();
?>