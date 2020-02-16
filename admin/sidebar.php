<?php

$langue = getConfig("langue_administration");

function getAutorisationModerateur($option)
{
	$o = getConfig("option_moderateur");
	$o = explode(",",$o);
	
	for($x=0;$x<count($o);$x++)
	{
		if($o[$x] == $option)
		{
			return true;
		}
	}
	
	return false;
}

?>
<div class="sidebar-mobile">

</div>
<div class="sidebar">
	<a href="<?php echo $url_script; ?>/admin/home.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/dashboard-icon.png"> <?php echo $title_dashboard; ?>
		</div>
	</a>
	<?php
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("configuration"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/configuration.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/setting-icon.png">
					<?php echo $title_configuration; ?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
		?>
		<a href="<?php echo $url_script; ?>/admin/configuration.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/setting-icon.png">
				<?php echo $title_configuration; ?>
			</div>
		</a>
		<?php
	}	
	/* Plugin */
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("plugin"))
		{
			?>
			<a href="javascript:void(0);" onclick="showMenuTab(9);">
				<div class="blocksidebar">
							<img src="<?php echo $url_script; ?>/admin/images/plugin-icon.png">
							<?php echo $title_plugins; ?>
				</div>
			</a>
			<div id="menutab-9" class="menutab" style="display:none;">
				<a href="<?php echo $url_script; ?>/admin/plugin.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/plugin-gestion-icon.png">
						<?php echo $title_gestion_plugins; ?>
					</div>
				</a>
				<?php
			
				$SQL = "SELECT COUNT(*) FROM plugin";
				$reponse = $pdo->query($SQL);
				$req = $reponse->fetch();
				if($req[0] != 0)
				{
					$SQL = "SELECT * FROM plugin";
					$reponse = $pdo->query($SQL);
					while($req = $reponse->fetch())
					{
						?>
						<a href="<?php echo $url_script; ?>/admin/plugin/<?php echo $req['directory']; ?>/<?php echo $req['pluginadmin']; ?>">
								<div class="blocksidebar">
									<img src="<?php echo $url_script; ?>/admin/plugin/<?php echo $req['directory']; ?>/icon.png">
									<?php echo $req['nom']; ?>
								</div>
						</a>
						<?php
					}
				}
				?>
			</div>
			<?php
		}
	}
	else
	{		
		?>
		<a href="javascript:void(0);" onclick="showMenuTab(9);">
			<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/plugin-icon.png">
						<?php echo $title_plugins; ?>
			</div>
		</a>
		<div id="menutab-9" class="menutab" style="display:none;">
			<a href="<?php echo $url_script; ?>/admin/plugin.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/plugin-gestion-icon.png">
					<?php echo $title_gestion_plugins; ?>
				</div>
			</a>
			<?php
			
			$SQL = "SELECT COUNT(*) FROM plugin";
			$reponse = $pdo->query($SQL);
			$req = $reponse->fetch();
			if($req[0] != 0)
			{
				$SQL = "SELECT * FROM plugin";
				$reponse = $pdo->query($SQL);
				while($req = $reponse->fetch())
				{
					?>
					<a href="<?php echo $url_script; ?>/admin/plugin/<?php echo $req['directory']; ?>/<?php echo $req['pluginadmin']; ?>">
							<div class="blocksidebar">
								<img src="<?php echo $url_script; ?>/admin/plugin/<?php echo $req['directory']; ?>/icon.png">
								<?php echo $req['nom']; ?>
							</div>
					</a>
					<?php
				}
			}
			?>
		</div>
		<?php
	}	
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("design"))
		{
			?>
			<a href="javascript:void(0);" onclick="showMenuTab(4);">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/design-icon.png">
					<?php echo $title_design_apparence; ?>
				</div>
			</a>
			<div id="menutab-4" class="menutab" style="display:none;">
				<a href="<?php echo $url_script; ?>/admin/parametretemplate.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/setting-icon.png">
						<?php echo $title_parametre_template; ?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/edittemplate.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/template-icon.png">
						<?php echo $title_edition_template; ?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/template.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/template-icon.png">
						<?php echo $title_template; ?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/apparence.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/color-icon.png">
						<?php echo $title_couleur_site; ?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/menu.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/menu-icon.png">
						<?php echo $title_menu; ?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/footer.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/seo-icon.png">
						<?php echo $title_footer; ?>
					</div>
				</a>
			</div>
			<?php
		}
	}
	else
	{
	?>
	<a href="javascript:void(0);" onclick="showMenuTab(4);">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/design-icon.png">
			<?php echo $title_design_apparence; ?>
		</div>
	</a>
	<div id="menutab-4" class="menutab" style="display:none;">
		<a href="<?php echo $url_script; ?>/admin/parametretemplate.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/setting-icon.png">
				<?php echo $title_parametre_template; ?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/edittemplate.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/template-icon.png">
				<?php echo $title_edition_template; ?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/template.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/template-icon.png">
				<?php echo $title_template; ?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/apparence.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/color-icon.png">
				<?php echo $title_couleur_site; ?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/menu.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/menu-icon.png">
				<?php echo $title_menu; ?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/footer.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/seo-icon.png">
				<?php echo $title_footer; ?>
			</div>
		</a>
	</div>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("firewall"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/firewall.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/firewall-icon.png"> <?php echo $title_firewall; ?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
		?>
		<a href="<?php echo $url_script; ?>/admin/firewall.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/firewall-icon.png"> <?php echo $title_firewall; ?>
			</div>
		</a>
		<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("service"))
		{
			?>
			<a href="javascript:void(0);" onclick="showMenuTab(8);">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/design-icon.png"> Service
				</div>
			</a>
			<div id="menutab-8" class="menutab" style="display:none;">
				<a href="<?php echo $url_script; ?>/admin/googleanalytics.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/google-analytics-mini-icon.png"> Google Analytics
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/googlerecaptcha.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/google-recaptcha-icon.png"> Google reCAPTCHA
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/googlesignin.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/google-sign-in-icon.png"> Google Sign-in
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/facebookconnect.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/facebook-icon.png"> Facebook Connect
					</div>
				</a>
			</div>
			<?php
		}
	}
	else
	{
		?>
		<a href="javascript:void(0);" onclick="showMenuTab(8);">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/design-icon.png"> Service
			</div>
		</a>
		<div id="menutab-8" class="menutab" style="display:none;">
			<a href="<?php echo $url_script; ?>/admin/googleanalytics.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/google-analytics-mini-icon.png"> Google Analytics
				</div>
			</a>
			<a href="<?php echo $url_script; ?>/admin/googlerecaptcha.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/google-recaptcha-icon.png"> Google reCAPTCHA
				</div>
			</a>
			<a href="<?php echo $url_script; ?>/admin/googlesignin.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/google-sign-in-icon.png"> Google Sign-in
				</div>
			</a>
			<a href="<?php echo $url_script; ?>/admin/facebookconnect.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/facebook-icon.png"> Facebook Connect
				</div>
			</a>
		</div>
		<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("maintenance"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/maintenance.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/maintenance-icon.png">
					<?php echo $title_maintenance; ?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/maintenance.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/maintenance-icon.png">
			<?php echo $title_maintenance; ?>
		</div>
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("annonces"))
		{
			?>
			<a href="javascript:void(0);" onclick="showMenuTab(5);">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/annonce-icon.png">
					<?php echo $title_annonce; ?>
				</div>
			</a>
			<div id="menutab-5" class="menutab" style="display:none;">
			<?php
			if(getAutorisationModerateur("gestion_annonces"))
			{
				?>
				<a href="<?php echo $url_script; ?>/admin/annonce.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/annonce-icon.png">
						<?php echo $title_gestion_annonce; ?>
					</div>
				</a>
				<?php
			}
			if(getAutorisationModerateur("ajouter_annonces"))
			{
				?>
				<a href="<?php echo $url_script; ?>/admin/addannonce.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/annonce-icon.png">
						<?php echo $title_ajouter_annonce; ?>
					</div>
				</a>
				<?php
			}
			if(getAutorisationModerateur("parametre_annonces"))
			{
				?>
				<a href="<?php echo $url_script; ?>/admin/annonceparametre.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/setting-icon.png">
						<?php echo $title_parametre_annonce; ?>
					</div>
				</a>
				<?php
			}
			?>
			</div>
			<?php
		}
	}
	else
	{
	?>
	<a href="javascript:void(0);" onclick="showMenuTab(5);">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/annonce-icon.png">
			<?php echo $title_annonce; ?>
		</div>
	</a>
	<div id="menutab-5" class="menutab" style="display:none;">
		<a href="<?php echo $url_script; ?>/admin/annonce.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/annonce-icon.png">
				<?php echo $title_gestion_annonce; ?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/addannonce.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/annonce-icon.png">
				<?php echo $title_ajouter_annonce; ?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/annonceparametre.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/setting-icon.png">
				<?php echo $title_parametre_annonce; ?>
			</div>
		</a>
	</div>
	<?php
	}
	
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("boutique"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/gestionboutique.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/boutique-icon.png">
					<?php echo $title_boutique; ?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
		?>
		<a href="<?php echo $url_script; ?>/admin/gestionboutique.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/boutique-icon.png">
				<?php echo $title_boutique; ?>
			</div>
		</a>
		<?php
	}
	
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("signalement"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/signalement.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/signalement-icon.png">
					<?php echo $title_signalement; ?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/signalement.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/signalement-icon.png">
			<?php echo $title_signalement; ?>
		</div>
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("region"))
		{
			?>
			<a href="javascript:void(0);" onclick="showMenuTab(3);">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Région / Département / Ville
						<?php
					}
					if($langue == 'en')
					{
						?>
						Region / Department / City
						<?php
					}
					if($langue == 'it')
					{
						?>
						Regione / Dipartimento / Città
						<?php
					}
					if($langue == 'bg')
					{
						?>
						Регион / департамент / град
						<?php
					}
					?>
				</div>
			</a>
			<div id="menutab-3" class="menutab" style="display:none;">
				<a href="<?php echo $url_script; ?>/admin/carte.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Carte
							<?php
						}
						if($langue == 'en')
						{
							?>
							Map
							<?php
						}
						if($langue == 'it')
						{
							?>
							Mappa
							<?php
						}
						if($langue == 'bg')
						{
							?>
							карта
							<?php
						}
						?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/region.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Région
							<?php
						}
						if($langue == 'en')
						{
							?>
							Region
							<?php
						}
						if($langue == 'it')
						{
							?>
							Regione
							<?php
						}
						if($langue == 'bg')
						{
							?>
							област
							<?php
						}
						?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/departement.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Département
							<?php
						}
						if($langue == 'en')
						{
							?>
							Department
							<?php
						}
						if($langue == 'it')
						{
							?>
							Reparto
							<?php
						}
						if($langue == 'bg')
						{
							?>
							отдел
							<?php
						}
						?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/ville.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Ville
							<?php
						}
						if($langue == 'en')
						{
							?>
							City
							<?php
						}
						if($langue == 'it')
						{
							?>
							Città
							<?php
						}
						if($langue == 'bg')
						{
							?>
							град
							<?php
						}
						?>
					</div>
				</a>
			</div>
			<?php
		}
	}
	else
	{
	?>
	<a href="javascript:void(0);" onclick="showMenuTab(3);">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Région / Département / Ville
				<?php
			}
			if($langue == 'en')
			{
				?>
				Region / Department / City
				<?php
			}
			if($langue == 'it')
			{
				?>
				Regione / Dipartimento / Città
				<?php
			}
			if($langue == 'bg')
			{
				?>
				Регион / департамент / град
				<?php
			}
			?>
		</div>
	</a>
	<div id="menutab-3" class="menutab" style="display:none;">
		<a href="<?php echo $url_script; ?>/admin/carte.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Carte
					<?php
				}
				if($langue == 'en')
				{
					?>
					Map
					<?php
				}
				if($langue == 'it')
				{
					?>
					Mappa
					<?php
				}
				if($langue == 'bg')
				{
					?>
					карта
					<?php
				}
				?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/region.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Région
					<?php
				}
				if($langue == 'en')
				{
					?>
					Region
					<?php
				}
				if($langue == 'it')
				{
					?>
					Regione
					<?php
				}
				if($langue == 'bg')
				{
					?>
					област
					<?php
				}
				?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/departement.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Département
					<?php
				}
				if($langue == 'en')
				{
					?>
					Department
					<?php
				}
				if($langue == 'it')
				{
					?>
					Reparto
					<?php
				}
				if($langue == 'bg')
				{
					?>
					отдел
					<?php
				}
				?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/ville.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/map-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Ville
					<?php
				}
				if($langue == 'en')
				{
					?>
					City
					<?php
				}
				if($langue == 'it')
				{
					?>
					Città
					<?php
				}
				if($langue == 'bg')
				{
					?>
					град
					<?php
				}
				?>
			</div>
		</a>
	</div>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("utilisateur"))
		{
			?>
			<a href="javascript:void(0);" onclick="showMenuTab(2);">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/user-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Utilisateurs
						<?php
					}
					if($langue == 'en')
					{
						?>
						Users
						<?php
					}
					if($langue == 'it')
					{
						?>
						Utenti
						<?php
					}
					if($langue == 'bg')
					{
						?>
						потребители
						<?php
					}
					?>
				</div>
			</a>
			<div id="menutab-2" class="menutab" style="display:none;">
			<?php
			if(getAutorisationModerateur("gestion_utilisateur"))
			{
				?>
				<a href="<?php echo $url_script; ?>/admin/user.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/user-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Gestion des utilisateurs
							<?php
						}
						if($langue == 'en')
						{
							?>
							User Management
							<?php
						}
						if($langue == 'it')
						{
							?>
							Gestione degli utenti
							<?php
						}
						if($langue == 'bg')
						{
							?>
							Управление на потребителите
							<?php
						}
						?>
					</div>
				</a>
				<?php
			}
			if(getAutorisationModerateur("gestion_administrateur"))
			{
				?>
				<a href="<?php echo $url_script; ?>/admin/administrateur.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/user-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Gestion des administrateurs
							<?php
						}
						if($langue == 'en')
						{
							?>
							Administrators management
							<?php
						}
						if($langue == 'it')
						{
							?>
							Gestione degli amministratori
							<?php
						}
						if($langue == 'bg')
						{
							?>
							Управление на администраторите
							<?php
						}
						?>
					</div>
				</a>
				<?php
			}
			if(getAutorisationModerateur("gestion_moderateur"))
			{
				?>
				<a href="<?php echo $url_script; ?>/admin/moderateur.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/user-icon.png"> Gestion des modérateurs
					</div>
				</a>
				<?php
			}
			?>
			</div>
			<?php
		}
	}
	else
	{
	?>
	<a href="javascript:void(0);" onclick="showMenuTab(2);">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/user-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Utilisateurs
				<?php
			}
			if($langue == 'en')
			{
				?>
				Users
				<?php
			}
			if($langue == 'it')
			{
				?>
				Utenti
				<?php
			}
			if($langue == 'bg')
			{
				?>
				потребители
				<?php
			}
			?>
		</div>
	</a>
	<div id="menutab-2" class="menutab" style="display:none;">
		<a href="<?php echo $url_script; ?>/admin/user.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/user-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Gestion des utilisateurs
					<?php
				}
				if($langue == 'en')
				{
					?>
					User Management
					<?php
				}
				if($langue == 'it')
				{
					?>
					Gestione degli utenti
					<?php
				}
				if($langue == 'bg')
				{
					?>
					Управление на потребителите
					<?php
				}
				?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/administrateur.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/user-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Gestion des administrateurs
					<?php
				}
				if($langue == 'en')
				{
					?>
					Administrators management
					<?php
				}
				if($langue == 'it')
				{
					?>
					Gestione degli amministratori
					<?php
				}
				if($langue == 'bg')
				{
					?>
					Управление на администраторите
					<?php
				}
				?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/moderateur.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/user-icon.png"> Gestion des modérateurs
			</div>
		</a>
	</div>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("language"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/langue.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/language-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Languages
						<?php
					}
					if($langue == 'en')
					{
						?>
						Languages
						<?php
					}
					if($langue == 'it')
					{
						?>
						Lingue
						<?php
					}
					if($langue == 'bg')
					{
						?>
						езици
						<?php
					}
					?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/langue.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/language-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Languages
				<?php
			}
			if($langue == 'en')
			{
				?>
				Languages
				<?php
			}
			if($langue == 'it')
			{
				?>
				Lingue
				<?php
			}
			if($langue == 'bg')
			{
				?>
				езици
				<?php
			}
			?>
		</div>
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("hotline"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/hotline.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/phone-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Hotline
						<?php
					}
					if($langue == 'en')
					{
						?>
						Hotline
						<?php
					}
					if($langue == 'it')
					{
						?>
						Hotline
						<?php
					}
					if($langue == 'bg')
					{
						?>
						Гореща линия
						<?php
					}
					?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/hotline.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/phone-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Hotline
				<?php
			}
			if($langue == 'en')
			{
				?>
				Hotline
				<?php
			}
			if($langue == 'it')
			{
				?>
				Hotline
				<?php
			}
			if($langue == 'bg')
			{
				?>
				Гореща линия
				<?php
			}
			?>
		</div>
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("tchat"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/tchat.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/tchat-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Module Tchat
						<?php
					}
					if($langue == 'en')
					{
						?>
						Module Tchat
						<?php
					}
					if($langue == 'it')
					{
						?>
						Modulo Tchat
						<?php
					}
					if($langue == 'bg')
					{
						?>
						модул чат
						<?php
					}
					?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/tchat.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/tchat-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Module Tchat
				<?php
			}
			if($langue == 'en')
			{
				?>
				Module Tchat
				<?php
			}
			if($langue == 'it')
			{
				?>
				Modulo Tchat
				<?php
			}
			if($langue == 'bg')
			{
				?>
				модул чат
				<?php
			}
			?>
		</div>
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("paiement"))
		{
			?>
			<a href="javascript:void(0);" onclick="showMenuTab(1);">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Paiement
						<?php
					}
					if($langue == 'en')
					{
						?>
						Payment
						<?php
					}
					if($langue == 'it')
					{
						?>
						Pagamento
						<?php
					}
					if($langue == 'bg')
					{
						?>
						плащане
						<?php
					}
					?>
				</div>
			</a>
			<div id="menutab-1" class="menutab" style="display:none;">
				<a href="<?php echo $url_script; ?>/admin/paiement.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Transaction & Chiffre d'affaire
							<?php
						}
						if($langue == 'en')
						{
							?>
							Transaction & Turnover
							<?php
						}
						if($langue == 'it')
						{
							?>
							Transazione e fatturato
							<?php
						}
						if($langue == 'bg')
						{
							?>
							Транзакции и оборот
							<?php
						}
						?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/configpaiement.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Configuration des paiements
							<?php
						}
						if($langue == 'en')
						{
							?>
							Payment configuration
							<?php
						}
						if($langue == 'it')
						{
							?>
							Configurazione di pagamento
							<?php
						}
						if($langue == 'bg')
						{
							?>
							Конфигурация на плащане
							<?php
						}
						?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/parametreavancepaiement.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Paramètres avancés des paiements
							<?php
						}
						if($langue == 'en')
						{
							?>
							Advanced payment settings
							<?php
						}
						if($langue == 'it')
						{
							?>
							Impostazioni di pagamento avanzate
							<?php
						}
						if($langue == 'bg')
						{
							?>
							Настройки за разширено плащане
							<?php
						}
						?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/grilletarifaire.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Grille des tarifs
							<?php
						}
						if($langue == 'en')
						{
							?>
							Rates grid
							<?php
						}
						if($langue == 'it')
						{
							?>
							Griglia tariffaria
							<?php
						}
						if($langue == 'bg')
						{
							?>
							Тарифна мрежа
							<?php
						}
						?>
					</div>
				</a>
			</div>
			<?php
		}
	}
	else
	{
	?>
	<a href="javascript:void(0);" onclick="showMenuTab(1);">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Paiement
				<?php
			}
			if($langue == 'en')
			{
				?>
				Payment
				<?php
			}
			if($langue == 'it')
			{
				?>
				Pagamento
				<?php
			}
			if($langue == 'bg')
			{
				?>
				плащане
				<?php
			}
			?>
		</div>
	</a>
	<div id="menutab-1" class="menutab" style="display:none;">
		<a href="<?php echo $url_script; ?>/admin/paiement.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Transaction & Chiffre d'affaire
					<?php
				}
				if($langue == 'en')
				{
					?>
					Transaction & Turnover
					<?php
				}
				if($langue == 'it')
				{
					?>
					Transazione e fatturato
					<?php
				}
				if($langue == 'bg')
				{
					?>
					Транзакции и оборот
					<?php
				}
				?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/configpaiement.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Configuration des paiements
					<?php
				}
				if($langue == 'en')
				{
					?>
					Payment configuration
					<?php
				}
				if($langue == 'it')
				{
					?>
					Configurazione di pagamento
					<?php
				}
				if($langue == 'bg')
				{
					?>
					Конфигурация на плащане
					<?php
				}
				?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/parametreavancepaiement.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Paramètres avancés des paiements
					<?php
				}
				if($langue == 'en')
				{
					?>
					Advanced payment settings
					<?php
				}
				if($langue == 'it')
				{
					?>
					Impostazioni di pagamento avanzate
					<?php
				}
				if($langue == 'bg')
				{
					?>
					Настройки за разширено плащане
					<?php
				}
				?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/grilletarifaire.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/money-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Grille des tarifs
					<?php
				}
				if($langue == 'en')
				{
					?>
					Rates grid
					<?php
				}
				if($langue == 'it')
				{
					?>
					Griglia tariffaria
					<?php
				}
				if($langue == 'bg')
				{
					?>
					Тарифна мрежа
					<?php
				}
				?>
			</div>
		</a>
	</div>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("categorie"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/categorie.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/home-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Catégories
						<?php
					}
					if($langue == 'en')
					{
						?>
						Category
						<?php
					}
					if($langue == 'it')
					{
						?>
						Categoria
						<?php
					}
					if($langue == 'bg')
					{
						?>
						категория
						<?php
					}
					?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/categorie.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/home-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Catégories
				<?php
			}
			if($langue == 'en')
			{
				?>
				Category
				<?php
			}
			if($langue == 'it')
			{
				?>
				Categoria
				<?php
			}
			if($langue == 'bg')
			{
				?>
				категория
				<?php
			}
			?>
		</div>
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("seo"))
		{
			?>
			<a href="javascript:void(0);" onclick="showMenuTab(7);">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/seo-icon.png"> SEO
				</div>
			</a>
			<div id="menutab-7" class="menutab" style="display:none;">
				<a href="<?php echo $url_script; ?>/admin/seo.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/seo-icon.png"> SEO
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/seometa.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/seo-icon.png"> Bing/Google Verification
					</div>
				</a>
			</div>
			<?php
		}
	}
	else
	{
	?>
	<a href="javascript:void(0);" onclick="showMenuTab(7);">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/seo-icon.png"> SEO
		</div>
	</a>
	<div id="menutab-7" class="menutab" style="display:none;">
		<a href="<?php echo $url_script; ?>/admin/seo.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/seo-icon.png"> SEO
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/seometa.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/seo-icon.png"> Bing/Google Verification
			</div>
		</a>
	</div>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("pages"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/page.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/page-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Page(s)
						<?php
					}
					if($langue == 'en')
					{
						?>
						Page
						<?php
					}
					if($langue == 'it')
					{
						?>
						Pagina
						<?php
					}
					if($langue == 'bg')
					{
						?>
						страница
						<?php
					}
					?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/page.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/page-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Page(s)
				<?php
			}
			if($langue == 'en')
			{
				?>
				Page
				<?php
			}
			if($langue == 'it')
			{
				?>
				Pagina
				<?php
			}
			if($langue == 'bg')
			{
				?>
				страница
				<?php
			}
			?>
		</div>
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("social"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/social.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/social-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Réseaux Sociaux
						<?php
					}
					if($langue == 'en')
					{
						?>
						Social Networks
						<?php
					}
					if($langue == 'it')
					{
						?>
						Reti sociali
						<?php
					}
					if($langue == 'bg')
					{
						?>
						Социални мрежи
						<?php
					}
					?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/social.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/social-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Réseaux Sociaux
				<?php
			}
			if($langue == 'en')
			{
				?>
				Social Networks
				<?php
			}
			if($langue == 'it')
			{
				?>
				Reti sociali
				<?php
			}
			if($langue == 'bg')
			{
				?>
				Социални мрежи
				<?php
			}
			?>
		</div>
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("cookie"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/cookie.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/home-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Cookie
						<?php
					}
					if($langue == 'en')
					{
						?>
						Cookie
						<?php
					}
					if($langue == 'it')
					{
						?>
						Cookie
						<?php
					}
					if($langue == 'bg')
					{
						?>
						курабийка
						<?php
					}
					?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/cookie.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/home-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Cookie
				<?php
			}
			if($langue == 'en')
			{
				?>
				Cookie
				<?php
			}
			if($langue == 'it')
			{
				?>
				Cookie
				<?php
			}
			if($langue == 'bg')
			{
				?>
				курабийка
				<?php
			}
			?>
		</div>
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("email"))
		{
			?>
			<a href="javascript:void(0);" onclick="showMenuTab(6);">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/email-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Email
						<?php
					}
					if($langue == 'en')
					{
						?>
						E-mail
						<?php
					}
					if($langue == 'it')
					{
						?>
						E-mail
						<?php
					}
					if($langue == 'bg')
					{
						?>
						електронна поща
						<?php
					}
					?>
				</div>
			</a>
			<div id="menutab-6" class="menutab" style="display:none;">
				<a href="<?php echo $url_script; ?>/admin/email.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/email-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Contenue des emails
							<?php
						}
						if($langue == 'en')
						{
							?>
							Content of emails
							<?php
						}
						if($langue == 'it')
						{
							?>
							Contenuto di e-mail
							<?php
						}
						if($langue == 'bg')
						{
							?>
							Съдържание на имейли
							<?php
						}
						?>
					</div>
				</a>
				<a href="<?php echo $url_script; ?>/admin/template-email.php">
					<div class="blocksidebar">
						<img src="<?php echo $url_script; ?>/admin/images/design-icon.png">
						<?php
						if($langue == 'fr')
						{
							?>
							Template des emails
							<?php
						}
						if($langue == 'en')
						{
							?>
							Email template
							<?php
						}
						if($langue == 'it')
						{
							?>
							Modello di email
							<?php
						}
						if($langue == 'bg')
						{
							?>
							Шаблон за имейл
							<?php
						}
						?>
					</div>
				</a>
			</div>
			<?php
		}
	}
	else
	{
	?>
	<a href="javascript:void(0);" onclick="showMenuTab(6);">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/email-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Email
				<?php
			}
			if($langue == 'en')
			{
				?>
				E-mail
				<?php
			}
			if($langue == 'it')
			{
				?>
				E-mail
				<?php
			}
			if($langue == 'bg')
			{
				?>
				електронна поща
				<?php
			}
			?>
		</div>
	</a>
	<div id="menutab-6" class="menutab" style="display:none;">
		<a href="<?php echo $url_script; ?>/admin/email.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/email-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Contenue des emails
					<?php
				}
				if($langue == 'en')
				{
					?>
					Content of emails
					<?php
				}
				if($langue == 'it')
				{
					?>
					Contenuto di e-mail
					<?php
				}
				if($langue == 'bg')
				{
					?>
					Съдържание на имейли
					<?php
				}
				?>
			</div>
		</a>
		<a href="<?php echo $url_script; ?>/admin/template-email.php">
			<div class="blocksidebar">
				<img src="<?php echo $url_script; ?>/admin/images/design-icon.png">
				<?php
				if($langue == 'fr')
				{
					?>
					Template des emails
					<?php
				}
				if($langue == 'en')
				{
					?>
					Email template
					<?php
				}
				if($langue == 'it')
				{
					?>
					Modello di email
					<?php
				}
				if($langue == 'bg')
				{
					?>
					Шаблон за имейл
					<?php
				}
				?>
			</div>
		</a>
	</div>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("statistique"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/statistique.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/stat-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Statistique de visite
						<?php
					}
					if($langue == 'en')
					{
						?>
						Visit statistics
						<?php
					}
					if($langue == 'it')
					{
						?>
						Visita le statistiche
						<?php
					}
					if($langue == 'bg')
					{
						?>
						Статистика за посещенията
						<?php
					}
					?>
				</div>	
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/statistique.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/stat-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Statistique de visite
				<?php
			}
			if($langue == 'en')
			{
				?>
				Visit statistics
				<?php
			}
			if($langue == 'it')
			{
				?>
				Visita le statistiche
				<?php
			}
			if($langue == 'bg')
			{
				?>
				Статистика за посещенията
				<?php
			}
			?>
		</div>	
	</a>
	<?php
	}
	if($_SESSION['admin_type_compte'] == 'moderateur')
	{
		if(getAutorisationModerateur("publicite"))
		{
			?>
			<a href="<?php echo $url_script; ?>/admin/publicite.php">
				<div class="blocksidebar">
					<img src="<?php echo $url_script; ?>/admin/images/ads-icon.png">
					<?php
					if($langue == 'fr')
					{
						?>
						Publicité
						<?php
					}
					if($langue == 'en')
					{
						?>
						Ads
						<?php
					}
					if($langue == 'it')
					{
						?>
						Pubblicità
						<?php
					}
					if($langue == 'bg')
					{
						?>
						реклама
						<?php
					}
					?>
				</div>
			</a>
			<?php
		}
	}
	else
	{
	?>
	<a href="<?php echo $url_script; ?>/admin/publicite.php">
		<div class="blocksidebar">
			<img src="<?php echo $url_script; ?>/admin/images/ads-icon.png">
			<?php
			if($langue == 'fr')
			{
				?>
				Publicité
				<?php
			}
			if($langue == 'en')
			{
				?>
				Ads
				<?php
			}
			if($langue == 'it')
			{
				?>
				Pubblicità
				<?php
			}
			if($langue == 'bg')
			{
				?>
				реклама
				<?php
			}
			?>
		</div>
	</a>
	<?php
	}
	?>
</div>
<script src="<?php echo $url_script; ?>/admin/js/jquery.js"></script>
<script>
function showMenuTab(id)
{
	$('#menutab-'+id).toggle('slow');
}
</script>