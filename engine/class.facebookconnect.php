<?php

/* Class Facebook Connect - Copyright Shua-Creation.com 2019 */
/* ========================================================= */
/* Classe permettant la connexion d'un utilisateur ou sont inscription à un service ou site internet */

class FacebookConnect
{
	var $appid;
	
	function __construct()
	{		
	}
	
	/* Indiquer le App Id de l'application facebook avant toute opération */
	function setAppId($appid)
	{
		$this->appid = $appid;
	}
	
	function getDataScript()
	{
		global $url_script;
		
		$data = '<script>'."\n";
		$data .= 'window.fbAsyncInit = function() {'."\n";
		$data .= 'FB.init({'."\n";
		$data .= "appId            : '".$this->appid."',"."\n";
		$data .= 'autoLogAppEvents : true,'."\n";
		$data .= 'xfbml            : true,'."\n";
		$data .= "version          : 'v3.2'"."\n";
		$data .= '});'."\n";
		$data .= 'FB.getLoginStatus(function(response)'."\n";
		$data .= '{'."\n";
		$data .= '})'."\n";
		$data .= '};'."\n";
		
		$data .= '(function(d, s, id){'."\n";
		$data .= 'var js, fjs = d.getElementsByTagName(s)[0];'."\n";
		$data .= 'if (d.getElementById(id)) {return;}'."\n";
		$data .= 'js = d.createElement(s); js.id = id;'."\n";
		$data .= 'js.src = "https://connect.facebook.net/en_US/sdk.js";'."\n";
		$data .= 'fjs.parentNode.insertBefore(js, fjs);'."\n";
		$data .= "}(document, 'script', 'facebook-jssdk'));"."\n";
		$data .= '</script>'."\n";
		
		$data .= '<script>'."\n";
		$data .= 'function connectFB()'."\n";
		$data .= '{'."\n";
		$data .= 'FB.login(function(response)'."\n";
		$data .= '{'."\n";
		$data .= 'if(response.authResponse)'."\n";
		$data .= '{'."\n";
		$data .= "console.log('Welcome!  Fetching your information.... ');"."\n";
		$data .= "FB.api('/me', {fields: 'id,name,first_name,last_name,email,picture'}, function(response)"."\n";
		$data .= '{'."\n";
		$data .= 'document.location.href="'.$url_script.'/fb-callback.php?email="+encodeURIComponent(response.email)+"&first_name="+encodeURIComponent(response.first_name)+"&last_name="+encodeURIComponent(response.last_name)+"&id="+encodeURIComponent(response.id);'."\n";
		$data .= '});'."\n";
		$data .= '}'."\n";
		$data .= 'else'."\n";
		$data .= '{'."\n";
		$data .= "console.log('User cancelled login or did not fully authorize.');"."\n";
		$data .= '}'."\n";
		$data .= "},{scope: 'public_profile,email'});"."\n";
		$data .= '}'."\n";
		$data .= '</script>'."\n";
		
		return $data;
	}		
	
	function showScript()
	{
		?>
		<script>
		  window.fbAsyncInit = function() {
			FB.init({
			  appId            : '<?php echo $this->appid; ?>',
			  autoLogAppEvents : true,
			  xfbml            : true,
			  version          : 'v3.2'
			});
			
			FB.getLoginStatus(function(response) 
			{
			  if(response.status === 'connected') 
			  {
				  console.log('connecté');
			  }
			  else
			  {
				  console.log('deconnecté');
			  }
			})
		  };

		  (function(d, s, id){
			 var js, fjs = d.getElementsByTagName(s)[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement(s); js.id = id;
			 js.src = "https://connect.facebook.net/en_US/sdk.js";
			 fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
		</script>
		<?php
	}
	
	/* Permet d'afficher le bouton Facebook de connection, si connection OK redirection sur fb-callback.php */
	/* Avec les informations nécessaire, email, first_name, last_name, id */
	function showConnectButton($class)
	{
		global $url_script;
		
		?>
		<a href="javascript:void(0);" class="<?php echo $class; ?>" onclick="connectFB();"><i class="fab fa-facebook-f"></i> Se connecter avec Facebook</a>
		<script>
		function connectFB()
		{
			FB.login(function(response) 
			{
				if (response.authResponse) 
				{
				 console.log('Welcome!  Fetching your information.... ');
				 FB.api('/me', {fields: 'id,name,first_name,last_name,email,picture'}, function(response) 
				 {
					 document.location.href="<?php echo $url_script; ?>/fb-callback.php?email="+encodeURIComponent(response.email)+"&first_name="+encodeURIComponent(response.first_name)+"&last_name="+encodeURIComponent(response.last_name)+"&id="+encodeURIComponent(response.id);
				 });
				} 
				else 
				{
				 console.log('User cancelled login or did not fully authorize.');
				}
			},{scope: 'public_profile,email'});
		}
		</script>
		<?php
	}
}

?>