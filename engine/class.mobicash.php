<?php

/* Class Mobicash par Shua-Creation.com 2019 */

class Mobicash
{
	function __construct()
	{
	}
	
	function paidStep($pays,$montant,$adminphone,$url_retour)
	{
		global $url_script;
		
		?>
		<style>
		.mobicash-background
		{
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0,0,0,0.6);
			z-index:1000;
		}
		
		.mobicash-modal
		{
			width: 500px;
			background-color: #fff;
			padding: 20px;
			box-sizing: border-box;
			margin-left: auto;
			margin-right: auto;
			margin-top: 50px;
			border-radius: 5px;
			text-align: center;
		}
		
		.mobicash-input
		{
			height: 35px;
			width: 100%;
			padding-left: 10px;
			border: 1px solid #dadada;
			margin-bottom: 10px;
		}
		
		.mobicash-btn
		{
			padding-top: 10px;
			padding-bottom: 10px;
			padding-left: 40px;
			padding-right: 40px;
			border: none;
			background-color: #c5c5c5;
			color: #fff;
			font-weight: bold;
			border-radius: 5px;
			font-size: 14px;
			cursor: pointer;
		}
		
		.mobicash-btn:hover
		{
			background-color:#818181;
		}
		</style>
		<?php
		if(isset($_REQUEST['maction']))
		{
			$maction = $_REQUEST['maction'];
			if($maction == 11)
			{
				/* On affiche les instructions */
				if($pays == 'BF')
				{
					$phone_mobicash = $_REQUEST['phone_mobicash'];
					?>
					<div class="mobicash-background">
						<div class="mobicash-modal">
							<img src="<?php echo $url_script; ?>/images/mobicash-bf.png">
							<H3>Effectuer votre paiement depuis votre mobile en tapant</H3>
							<H4>*555*2*1*<?php echo $adminphone; ?>*<?php echo $montant; ?>#OK<br><br>Puis cliquez sur le bouton "Terminée"</H4>
							<form method="POST">
								<input type="hidden" name="maction" value="12">
								<input type="hidden" name="phone_mobicash" value="<?php echo $phone_mobicash; ?>">
								<input type="submit" value="Terminée" class="mobicash-btn">
							</form>
						</div>
					</div>
					<?php
					exit;
				}
				if($pays == 'BN_MOOV_MONEY')
				{
					$phone_mobicash = $_REQUEST['phone_mobicash'];
					?>
					<div class="mobicash-background">
						<div class="mobicash-modal">
							<img src="<?php echo $url_script; ?>/images/moov-money-bn.png">
							<H3>Effectuer votre paiement depuis votre mobile en tapant</H3>
							<H4>*155*1*1*1*<?php echo $adminphone; ?>*<?php echo $montant; ?>*CODE SECRET#<br><br>Puis cliquez sur le bouton "Appeler"</H4>
							<form method="POST">
								<input type="hidden" name="maction" value="12">
								<input type="hidden" name="phone_mobicash" value="<?php echo $phone_mobicash; ?>">
								<input type="submit" value="Terminée" class="mobicash-btn">
							</form>
						</div>
					</div>
					<?php
					exit;
				}
				if($pays == 'TG_MOOV_MONEY')
				{
					$phone_mobicash = $_REQUEST['phone_mobicash'];
					?>
					<div class="mobicash-background">
						<div class="mobicash-modal">
							<img src="<?php echo $url_script; ?>/images/moov-money-tg.png">
							<H3>Effectuer votre paiement depuis votre mobile en tapant</H3>
							<H4>*155*1*<?php echo $adminphone; ?>*<?php echo $montant; ?>*CODE SECURITE#<br><br>Puis cliquez sur le bouton "Appeler"</H4>
							<form method="POST">
								<input type="hidden" name="maction" value="12">
								<input type="hidden" name="phone_mobicash" value="<?php echo $phone_mobicash; ?>">
								<input type="submit" value="Terminée" class="mobicash-btn">
							</form>
						</div>
					</div>
					<?php
					exit;
				}
				if($pays == 'ML')
				{
					$phone_mobicash = $_REQUEST['phone_mobicash'];
					?>
					<div class="mobicash-background">
						<div class="mobicash-modal">
							<img src="<?php echo $url_script; ?>/images/mobicash-ml.png">
							<H3>Effectuer votre paiement en suivant les instructions à effectuer sur votre mobile</H3>
							<H4>Tapez *166# puis OK<br>Entrez votre code secret puis OK<br>Tapez 2 puis OK<br>Tapez <?php echo $adminphone; ?> puis OK<br>Tapez <?php echo $montant; ?> puis OK<br>Tapez votre code secret puis OK<br>Finisez par le bouton terminée du formulaire</H4>
							<form method="POST">
								<input type="hidden" name="maction" value="12">
								<input type="hidden" name="phone_mobicash" value="<?php echo $phone_mobicash; ?>">
								<input type="submit" value="Terminée" class="mobicash-btn">
							</form>
						</div>
					</div>
					<?php
					exit;
				}
				if($pays == 'GA')
				{
					$phone_mobicash = $_REQUEST['phone_mobicash'];
					?>
					<div class="mobicash-background">
						<div class="mobicash-modal">
							<img src="<?php echo $url_script; ?>/images/mobicash-ga.png">
							<H3>Effectuer votre paiement en suivant les instructions à effectuer sur votre mobile</H3>
							<H4>Tapez *555#<br>Tapez 2 puis Valider<br>Tapez 0 puis Valider<br>Tapez <?php echo $adminphone; ?> puis Valider<br>Tapez <?php echo $montant; ?> puis Valider<br>Entrer le code PIN puis Valider<br>Finisez par le bouton terminée du formulaire</H4>
							<form method="POST">
								<input type="hidden" name="maction" value="12">
								<input type="hidden" name="phone_mobicash" value="<?php echo $phone_mobicash; ?>">
								<input type="submit" value="Terminée" class="mobicash-btn">
							</form>
						</div>
					</div>
					<?php
					exit;
				}
			}
			
			/* Finalisation */
			if($maction == 12)
			{
				$phone_mobicash = $_REQUEST['phone_mobicash'];
				?>
				<script>
					window.location.href = "<?php echo $url_retour; ?>&mobicash_phone=<?php echo $phone_mobicash; ?>";
				</script>
				<?php
				exit;
			}
		}
		?>
		<div class="mobicash-background">
			<div class="mobicash-modal">
				<form method="POST">
					<?php
					if($pays == 'BF')
					{
						$title_emoney = 'Mobicash';
						?>
						<img src="<?php echo $url_script; ?>/images/mobicash-bf.png">
						<?php
					}
					else if($pays == 'ML')
					{
						$title_emoney = 'Mobicash';
						?>
						<img src="<?php echo $url_script; ?>/images/mobicash-ml.png">
						<?php
					}
					else if($pays == 'GA')
					{
						$title_emoney = 'Mobicash';
						?>
						<img src="<?php echo $url_script; ?>/images/mobicash-ga.png">
						<?php
					}
					else if($pays == 'BN_MOOV_MONEY')
					{
						$title_emoney = 'Moov Money';
						?>
						<img src="<?php echo $url_script; ?>/images/moov-money-bn.png">
						<?php
					}
					else if($pays == 'TG_MOOV_MONEY')
					{
						$title_emoney = 'Moov Flooz';
						?>
						<img src="<?php echo $url_script; ?>/images/moov-money-tg.png">
						<?php
					}
					?>
					<H3>Entrer votre numéro de mobile <?php echo $title_emoney; ?></H3>
					<input type="text" name="phone_mobicash" class="mobicash-input" placeholder="Entrer votre numéro de téléphone">
					<input type="hidden" name="maction" value="11">
					<input type="submit" value="Suivant" class="mobicash-btn">
				</form>
			</div>
		</div>
		<?php
	}
}

?>