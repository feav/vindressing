<?php

/* Classe menu Shua-Creation 2018 */

class Menu
{
	var $isConnected;
	
	function __construct()
	{
		
	}
	
	function getConfig($identifiant)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_configuration WHERE identifiant = '$identifiant'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['code'];
	}
	
	function getLangue($identifiant,$language)
	{
		global $pdo;
		
		$SQL = "SELECT * FROM pas_langue WHERE identifiant = '$identifiant' AND language = '$language'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		return $req['texte'];
	}
	
	function getMenuComputer()
	{
		global $pdo;
		global $url_script;
		global $language;
		
		$data = "";
		
		$url_rewriting = $this->getConfig("url_rewriting");
		if($url_rewriting == 'yes')
		{
			$data .= '<ul>';			
			$SQL = "SELECT * FROM pas_menu";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				if($req['method'] != '')
				{
					if(isset($_SESSION['email']))
					{
						if(isset($_SESSION['password']))
						{
							$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
							$r = $pdo->query($SQL);
							$rr = $r->fetch();

							if($rr[0] == 0)
							{
								$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
							}
							else
							{
								if($req['method'] == 'moncompte')
								{
									$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
								}
								if($req['method'] == 'logout')
								{
									$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
								}
							}
						}
					}
					else
					{
						if($req['method'] == 'inmenu')
						{
							$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
						}
					}
				}
				else
				{
					$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
				}
			}
			$data .= '</ul>';
		}
		else
		{			
			$data .= '<ul>';
			$SQL = "SELECT * FROM pas_menu";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				if($req['method'] != '')
				{
					if(isset($_SESSION['email']))
					{
						if(isset($_SESSION['password']))
						{
							$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
							$r = $pdo->query($SQL);
							$rr = $r->fetch();

							if($rr[0] == 0)
							{
								$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
							}
							else
							{
								if($req['method'] == 'moncompte')
								{
									$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
								}
								else if($req['method'] == 'logout')
								{
									$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
								}
							}
						}
					}
					else
					{
						if($req['method'] == 'inmenu')
						{
							$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
						}
					}
				}
				else
				{
					$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
				}
			}
			$data .= '</ul>';
		}
		
		return $data;
	}
	
	function getMenuMobile()
	{
		global $pdo;
		global $url_script;
		global $language;
		
		$data = "";
		$data .= '<div class="menuMobile">';
		$data .= '<a href="javascript:void(0);" onclick="openMobileMenu();">';
		$data .= '<img src="'.$url_script.'/images/menu-mobile-icon.png">';
		$data .= '</a>';
		$data .= '</div>';
		$data .= '<div class="menuMobileVolet" id="menuMobileVolet">';
		
		$url_rewriting = $this->getConfig("url_rewriting");
		if($url_rewriting == 'yes')
		{
			$data .= '<ul>';
			$SQL = "SELECT * FROM pas_menu";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				if($req['method'] != '')
				{
					if(isset($_SESSION['email']))
					{
						if(isset($_SESSION['password']))
						{
							$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
							$r = $pdo->query($SQL);
							$rr = $r->fetch();

							if($rr[0] == 0)
							{
								$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
							}
							else
							{
								if($req['method'] == 'onlymobile')
								{
									$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
								}
								if($req['method'] == 'logoutmobile')
								{
									$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
								}
								if($req['method'] == 'moncomptemobile')
								{
									$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
								}
								if($req['method'] == 'moncompte')
								{
									$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
								}
								if($req['method'] == 'logout')
								{
									$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
								}
							}
						}
					}
					else
					{
						if($req['method'] == 'onlymobile')
						{
							$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
						}
						if($req['method'] == 'inscriptionmobile')
						{
							$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
						}
						if($req['method'] == 'connexionmobile')
						{
							$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
						}
						if($req['method'] == 'inmenu')
						{
							$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
						}
					}
				}
				else
				{
					$data .= '<li><a href="'.$req['url_rewriting'].'">'.$req['title'].'</a></li>';
				}
			}
			$data .= '</ul>';
		}
		else
		{			
			$data .= '<ul>';
			$SQL = "SELECT * FROM pas_menu";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				if($req['method'] != '')
				{
					if(isset($_SESSION['email']))
					{
						if(isset($_SESSION['password']))
						{
							$SQL = "SELECT COUNT(*) FROM pas_user WHERE email = '".$_SESSION['email']."' AND password = '".$_SESSION['password']."'";
							$r = $pdo->query($SQL);
							$rr = $r->fetch();

							if($rr[0] == 0)
							{
								$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
							}
							else
							{
								if($req['method'] == 'onlymobile')
								{
									$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
								}
								if($req['method'] == 'logoutmobile')
								{
									$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
								}
								if($req['method'] == 'moncomptemobile')
								{
									$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
								}
								if($req['method'] == 'moncompte')
								{
									$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
								}
								if($req['method'] == 'logout')
								{
									$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
								}
							}
						}
					}
					else
					{
						if($req['method'] == 'onlymobile')
						{
							$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
						}
						if($req['method'] == 'inscriptionmobile')
						{
							$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
						}
						if($req['method'] == 'connexionmobile')
						{
							$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
						}
						if($req['method'] == 'inmenu')
						{
							$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
						}
					}
				}
				else
				{
					$data .= '<li><a href="'.$req['url'].'">'.$req['title'].'</a></li>';
				}
			}
			$data .= '</ul>';
		}
		
		$data .= '</div>';
		$data .= '<script>';
		$data .= 'var openmenumobile = false;';
		$data .= 'function openMobileMenu()';
		$data .= '{';
		$data .= "$('#menuMobileVolet').toggle('slow');";
		$data .= '}';
		$data .= '</script>';
		
		return $data;
	}
}

?>