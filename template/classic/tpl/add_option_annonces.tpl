<div class="container topMargin" style="margin-bottom: 90px;">
	<H1>Ajouter des options à votre annonce</H1>
	<div class="info">
	Les options permet de vendre plus rapidement votre objet et d'augmenter la visibilité de votre annonces. Ces options ne sont pas obligatoire mais vivement conseiller pour augmenter vos ventes.
	</div>
	<form method="POST" action="paiement.php">
	{option}
	<div style="overflow:auto;">
		<div style="float:right;font-size: 22px;" id="totalCmd">
			Total : {total}
		</div>
	</div>
	<input type="hidden" name="md5" value="{md5}">
	<input type="hidden" name="option" id="optioncmd" value="">
	<input type="submit" value="Suivant" id="nextbtn" class="btnConfirm">
	</form>
</div>
<script>
var total = {total_code};
var catannonce = {cat_annonce};
var arrayid = [];
function updatePrice(id)
{
	$('#nextbtn').prop('disabled',true);
	var value = $('#option-'+id).val();
	if($('#option-'+id).is(':checked'))
	{
		arrayid.push(value);
	}
	else
	{
		arrayid.pop(value);
	}
	
	var option = '';
	for(var x=0;x<arrayid.length;x++)
	{
		if(x == arrayid.length-1)
		{
			option = option + arrayid[x];
		}
		else
		{
			option = option + arrayid[x] + ',';
		}
	}
	
	$('#optioncmd').val(option);
	
	$.post("updatepriceoption.php?catannonce="+catannonce+"&option="+option+"&initialprice="+total, function(data)
	{
		$('#totalCmd').html(data);
		$('#nextbtn').prop('disabled',false);
	});
}
</script>