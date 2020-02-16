<?php

/* Classe sociale Shua-Creation.com 2018 */

class Social
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
	
	/* Renvoie la box de partage */
	function getSocialSharing($md5,$titre,$copyright)
	{
		global $pdo;
		global $url_script;
		
		$data = NULL;
		
		$data .= '<div class="block-social-share">';
		$data .= '<a target="_blank" title="Twitter" href="https://twitter.com/share?url='.$url_script.'/showannonce.php?md5='.$md5.'&text='.$titre.'&via='.$copyright.'" rel="nofollow" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700\');return false;"><img src="'.$url_script.'/images/twitter_icon.png" alt="Twitter" /></a>'."\n";
		$data .= '<a target="_blank" title="Facebook" href="https://www.facebook.com/sharer.php?u='.$url_script.'/showannonce.php?md5='.$md5.'&t='.$titre.'" rel="nofollow" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700\');return false;"><img src="'.$url_script.'/images/facebook_icon.png" alt="Facebook" /></a>'."\n";
		//$data .= '<a target="_blank" title="Google +" href="https://plus.google.com/share?url='.$url_script.'/showannonce.php?md5='.$md5.'&hl=fr" rel="nofollow" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=450,width=650\');return false;"><img src="'.$url_script.'/images/gplus_icon.png" alt="Google Plus" /></a>'."\n";
		$data .= '<a target="_blank" title="Envoyer par mail" href="mailto:?subject='.$titre.'&body='.$url_script.'/showannonce.php?md5='.$md5.'" rel="nofollow"><img src="'.$url_script.'/images/email_icon.png" alt="email" /></a>'."\n";
		$data .= '</div>';
		
		return $data;
	}
	
	function getSocialBoxNormal()
	{
		global $pdo;
		global $url_script;
		
		$count = 0;
		$data = NULL;
		$facebook_page_url = $this->getConfig("facebook_page_url");
		$twitter_page_url = $this->getConfig("twitter_page_url");
		$pinterest_page_url = $this->getConfig("pinterest_page_url");
		$linkedin_page_url = $this->getConfig("linkedin_page_url");
		$messenger_page_url = $this->getConfig("messenger_page_url");
		$whatsapp_page_url = $this->getConfig("whatsapp_page_url");
		$youtube_page_url = $this->getConfig("youtube_page_url");
		
		$data .= '<div class="social-box-normal">'."\n";
		if($facebook_page_url != '')
		{
			$data .= '<a href="'.$facebook_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">';
			$data .= '<img src="'.$url_script.'/images/facebook.png" alt="Visiter notre page Facebook">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($twitter_page_url != '')
		{
			$data .= '<a href="'.$twitter_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/twitter.png" alt="Visiter notre page Twitter">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($youtube_page_url != '')
		{
			$data .= '<a href="'.$youtube_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/youtube.png" alt="Visiter notre chaine Youtube">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($pinterest_page_url != '')
		{
			$data .= '<a href="'.$pinterest_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/pinterest.png" alt="Visiter notre page Pinterest">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($linkedin_page_url != '')
		{
			$data .= '<a href="'.$linkedin_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/linkedin.png" alt="Visiter notre page Linkedin">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($messenger_page_url != '')
		{
			$data .= '<a href="'.$messenger_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/messenger.png" alt="Contacter nous sur Messenger">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($whatsapp_page_url != '')
		{
			$data .= '<a href="'.$whatsapp_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/whatsapp.png" alt="Contacter nous sur Whatsapp">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		$data .= '</div>'."\n";
		
		return $data;
	}
	
	/* Renvoie la social box */
	function getSocialBox()
	{
		global $pdo;
		global $url_script;
		
		$count = 0;
		$facebook_page_url = $this->getConfig("facebook_page_url");
		$twitter_page_url = $this->getConfig("twitter_page_url");
		$youtube_page_url = $this->getConfig("youtube_page_url");
		$pinterest_page_url = $this->getConfig("pinterest_page_url");
		$linkedin_page_url = $this->getConfig("linkedin_page_url");
		$messenger_page_url = $this->getConfig("messenger_page_url");
		$whatsapp_page_url = $this->getConfig("whatsapp_page_url");
		if($facebook_page_url != '')
		{
			$count = $count + 1;
		}
		if($twitter_page_url != '')
		{
			$count = $count + 1;
		}
		if($youtube_page_url != '')
		{
			$count = $count + 1;
		}
		if($pinterest_page_url != '')
		{
			$count = $count + 1;
		}
		if($linkedin_page_url != '')
		{
			$count = $count + 1;
		}
		if($messenger_page_url != '')
		{
			$count = $count + 1;
		}
		if($whatsapp_page_url != '')
		{
			$count = $count + 1;
		}
		
		$width = $count * 30;
		
		$data = '<style>';
		$data .= '.social-box';
		$data .= '{';
		$data .= 'width:'.$width.'px;';
		$data .= '}';
		$data .= '</style>';
		$data .= '<div class="social-box">'."\n";
		if($facebook_page_url != '')
		{
			$data .= '<a href="'.$facebook_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">';
			$data .= '<img src="'.$url_script.'/images/facebook.png" alt="Visiter notre page Facebook">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($twitter_page_url != '')
		{
			$data .= '<a href="'.$twitter_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/twitter.png" alt="Visiter notre page Twitter">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($youtube_page_url != '')
		{
			$data .= '<a href="'.$youtube_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/youtube.png" alt="Visiter notre chaine Youtube">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($pinterest_page_url != '')
		{
			$data .= '<a href="'.$pinterest_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/pinterest.png" alt="Visiter notre page Pinterest">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($linkedin_page_url != '')
		{
			$data .= '<a href="'.$linkedin_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/linkedin.png" alt="Visiter notre page Linkedin">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($messenger_page_url != '')
		{
			$data .= '<a href="'.$messenger_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/messenger.png" alt="Contacter nous sur Messenger">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		if($whatsapp_page_url != '')
		{
			$data .= '<a href="'.$whatsapp_page_url.'" target="newpage">'."\n";
			$data .= '<div class="social-box-container">'."\n";
			$data .= '<img src="'.$url_script.'/images/whatsapp.png" alt="Contacter nous sur Whatsapp">'."\n";
			$data .= '</div>'."\n";
			$data .= '</a>'."\n";
		}
		$data .= '</div>'."\n";
		
		return $data;
	}
}

?>