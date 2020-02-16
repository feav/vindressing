<div id="content">
	
</div>
<div class="container">
	<div class="ads728">
		{publicite_top}
	</div>
</div>
<div class="container topMargin">
	<div class="englobehomebox">
		<div class="homeboxleft">
			Намерете подходящата сделка между рекламите на<br>{count_annonce} в PAS Script.
			<form method="POST" action="offre.php">
				<input class="inputClass" type="text" name="searchtext" value="" placeholder="Какво търсите?">
				<select name="categorie" class="selectClass">
				{all_categorie}
				</select>
				{all_departement}
				<input type="submit" value="търсене" class="blueBtn cent searchIcon">
			</form>
			<div class="deposer-annonce-box">
				<a href="deposer-une-annonce.php" class="blueBtn cent orange-btn">Депозирайте реклама</a>
			</div>
		</div>
		<div class="homeboxcenter">
			{map}
		</div>
		<div class="homeboxrigth">
			{region}
		</div>
	</div>
</div>
<div class="categorieshow">
{categorie}
</div>