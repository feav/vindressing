<?php

/* Class Footer Shua-Creation.com 2018 */

class Footer
{
	function __construct()
	{		
	}
	
	/* Fonction globale de renvoie de config */
	function getConfig($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	/* Renvoie le Footer */
	function getFooter()
	{
		global $pdo;
		
		$SQL = "SELECT COUNT(*) FROM pas_footer";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$count = $req[0];
		$data = "";		
		
		$SQL = "SELECT * FROM pas_footer";
		$reponse = $pdo->query($SQL);
		while($req = $reponse->fetch())
		{
			$data .= '<div class="col'.$count.'">';
			$data .= '<H3>'.$req['titre'].'</H3>';
			
			/* Contenue supplémentaire */
			$data .= $req['contenue'];
			
			$idfooter = $req['id'];
			$SQL = "SELECT * FROM pas_footer_colonne WHERE idfooter = $idfooter";
			$r = $pdo->query($SQL);
			
			$data .= '<ul>';
			
			while($rr = $r->fetch())
			{
				$idpage = $rr['idpage'];
				$SQL = "SELECT * FROM pas_page WHERE id = $idpage";
				$u = $pdo->query($SQL);
				$uu = $u->fetch();
				$url_rewriting = $this->getConfig("url_rewriting");
				if($url_rewriting == 'yes')
				{
					$data .= '<li><a href="page-'.$uu['slug'].'.html">'.$uu['titre'].'</a></li>';
				}
				else
				{
					$data .= '<li><a href="page.php?slug='.$uu['slug'].'">'.$uu['titre'].'</a></li>';
				}
			}
				
			$data .= '</ul>';
			$data .= '</div>';
		}
		
		return $data;
	}
	
	/* Renvoie le Copyright */
	function getCopyright()
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_copyright";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['text'];
	}
}

?>