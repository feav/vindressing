<?php

include "version.php";
include "class.update.php";

$update = new Update();
$reponse = $update->getUpdateAvaible($version);

if(isset($_REQUEST['changelangue']))
{
	$changelangue = $_REQUEST['changelangue'];
	updateConfig("langue_administration",$changelangue);
}

$langue = getConfig("langue_administration");

if($langue == 'fr')
{
	include "lang/fr.php";
}
else if($langue == 'en')
{
	include "lang/en.php";
}
else if($langue == 'it')
{
	include "lang/it.php";
}
else if($langue == 'es')
{
	include "lang/es.php";
}
else if($langue == 'de')
{
	include "lang/de.php";
}

if($reponse)
{
	$title = $title_update_available;
	$icon = '<div class="icon-update">1</div>';
}
else
{
	$title = $title_unknow_update;
	$icon = '';
}

?>
<header>
	<div class="logo">
		<img src="<?php echo $url_script; ?>/images/mini-logo-pas.png"> v.<?php echo $version; ?>
	</div>
	<div class="menu">
		<ul>
			<li>
				<?php
				if($langue == 'fr')
				{
					?>
					<a href="javascript:void(0);"><img src="<?php echo $url_script; ?>/admin/images/fr-flag-icon.png" title="Français"></a>
					<?php
				}
				if($langue == 'en')
				{
					?>
					<a href="javascript:void(0);"><img src="<?php echo $url_script; ?>/admin/images/en-flag-icon.png" title="English"></a>
					<?php
				}
				if($langue == 'it')
				{
					?>
					<a href="javascript:void(0);"><img src="<?php echo $url_script; ?>/admin/images/it-flag-icon.png" title="Italiano"></a>
					<?php
				}
				if($langue == 'bg')
				{
					?>
					<a href="javascript:void(0);"><img src="<?php echo $url_script; ?>/admin/images/bg-flag-icon.png" title="български"></a>
					<?php
				}
				?>
				<ul>
					<li><a href="?changelangue=fr"><img src="<?php echo $url_script; ?>/admin/images/fr-flag-icon.png" title="Français"></a></li>
					<li><a href="?changelangue=en"><img src="<?php echo $url_script; ?>/admin/images/en-flag-icon.png" title="English"></a></li>
					<li><a href="?changelangue=it"><img src="<?php echo $url_script; ?>/admin/images/it-flag-icon.png" title="Italiano"></a></li>
					<li><a href="?changelangue=bg"><img src="<?php echo $url_script; ?>/admin/images/bg-flag-icon.png" title="български"></a></li>
				</ul>
			</li>
			<li><a href="maj.php"><?php echo $icon; ?><img src="<?php echo $url_script; ?>/admin/images/update-icon.png" title="<?php echo $title; ?>"></a></li>
			<li><a href="<?php echo $url_script; ?>" target="websitepage"><img src="<?php echo $url_script; ?>/admin/images/loupe-icon.png" title="<?php echo $title_btn_show_website; ?>"></a></li>
			<li><a href="logout.php"><img src="<?php echo $url_script; ?>/admin/images/logout-icon.png" title="<?php echo $title_btn_logout; ?>"></a></li>
		</ul>
	</div>
</header>