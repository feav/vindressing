<?php

/* Image uploader Shua-Creation.com 2019 */

class ImageUploader
{
	function __construct()
	{
	}
	
	function addPhoto($nbr,$start)
	{
		global $url_script;
		
		for($x=0;$x<$nbr;$x++)
		{
			?>
			<div class="image-uploader" id="imageupload-<?php echo $start; ?>">
				<div class="image-uploader-info" id="image-uploader-info-<?php echo $start; ?>"><img src="<?php echo $url_script; ?>/images/default.gif"></div>
				<div class="image-uploader-temp-delete-btn" id="image-uploader-temp-delete-btn-<?php echo $x; ?>" onclick="deleteImage('<?php echo $x; ?>')" title="Supprimer cette image">X</div>
				<div class="image-uploader-temp" id="image-uploader-temp-<?php echo $start; ?>"><img src="" id="image-temp-<?php echo $start; ?>"></div>
				<div class="image-uploader-text" id="image-uploader-text-<?php echo $start; ?>"><img src="<?php echo $url_script; ?>/images/photo-icon.png"><br>Ajouter ou glisser une image</div>
				<input type="file" class="image-uploader-btn" id="imageloader-<?php echo $start; ?>" name="image" onchange="previewChange('<?php echo $start; ?>');">
			</div>
			<?php
			$start++;
		}
	}
	
	function showUploader($nbrimage,$width,$height,$md5)
	{
		global $url_script;
		global $pdo;
		
		$SQL = "SELECT COUNT(*) FROM pas_image WHERE md5annonce = '$md5'";
		$reponse = $pdo->query($SQL);
		$req = $reponse->fetch();
		
		$countImageBDD = $req[0];
		$arrayImage = NULL;
		if($countImageBDD != 0)
		{
			$SQL = "SELECT * FROM pas_image WHERE md5annonce = '$md5'";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				$c = count($arrayImage);
				$arrayImage[$c]['image'] = $req['image'];
				$arrayImage[$c]['id'] = $req['id'];
			}
		}
		
		?>
		<style>
		.image-uploader
		{
			float: left;
			margin-right: 8px;
			width:<?php echo $width; ?>px;
			height:<?php echo $height; ?>px;
			border: 2px dashed #000000;
			position: relative;
			z-index: 1;
			margin-bottom: 8px;
		}
		
		.image-uploader-btn
		{
			width: 100%;
			height: 100%;
			opacity: 0;
		}
		
		.image-uploader-text
		{
			font-size: 12px;
			position: absolute;
			z-index: -1;
			width: 100%;
			text-align: center;
			margin-top: 30px;
		}
		
		.image-uploader-text img
		{
			pointer-events: none;
			width: 65px;
		}
		
		.image-uploader-info
		{
			width: 100%;
			height: 100%;
			position: absolute;
			background-color: rgba(0,0,0,0.5);
			display:none;
			text-align: center;
			box-sizing: border-box;
			padding-top: <?php echo ($height/2)-(57/2); ?>px;
		}
		
		.image-uploader-temp
		{
			display:none;
			width:100%;
			height:100%;
		}
		
		.image-uploader-temp img
		{
			width:100%;
			height:100%;
		}
		
		.image-uploader-temp-delete-btn
		{
			display:none;
			position: absolute;
			width: 25px;
			height: 23px;
			text-align: center;
			background-color: #f00;
			font-weight: bold;
			color: #fff;
			padding-top: 3px;
			cursor: pointer;
			z-index: 1;
		}
		
		.image-uploader-temp-delete-btn:hover
		{
			background-color: #ac0202;
		}
		</style>
		<?php
		for($x=0;$x<$nbrimage;$x++)
		{
			if($arrayImage[$x] != NULL)
			{
				?>
				<div class="image-uploader" id="imageupload-<?php echo $x; ?>">
					<div class="image-uploader-info" id="image-uploader-info-<?php echo $x; ?>"><img src="<?php echo $url_script; ?>/images/default.gif"></div>
					<div class="image-uploader-temp-delete-btn" id="image-uploader-temp-delete-btn-<?php echo $x; ?>" onclick="deleteImage('<?php echo $x; ?>')" title="Supprimer cette image" style="display:block;">X</div>
					<div class="image-uploader-temp" id="image-uploader-temp-<?php echo $x; ?>" style="display:block;"><img src="<?php echo $url_script.'/images/annonce/'.$arrayImage[$x]['image']; ?>" id="image-temp-<?php echo $x; ?>"></div>
					<div class="image-uploader-text" id="image-uploader-text-<?php echo $x; ?>" style="display:none;"><img src="<?php echo $url_script; ?>/images/photo-icon.png"><br>Ajouter ou glisser une image</div>
					<input type="file" class="image-uploader-btn" id="imageloader-<?php echo $x; ?>" name="image" onchange="previewChange('<?php echo $x; ?>');">
				</div>
				<?php
			}
			else
			{
				?>
				<div class="image-uploader" id="imageupload-<?php echo $x; ?>">
					<div class="image-uploader-info" id="image-uploader-info-<?php echo $x; ?>"><img src="<?php echo $url_script; ?>/images/default.gif"></div>
					<div class="image-uploader-temp-delete-btn" id="image-uploader-temp-delete-btn-<?php echo $x; ?>" onclick="deleteImage('<?php echo $x; ?>')" title="Supprimer cette image">X</div>
					<div class="image-uploader-temp" id="image-uploader-temp-<?php echo $x; ?>"><img src="" id="image-temp-<?php echo $x; ?>"></div>
					<div class="image-uploader-text" id="image-uploader-text-<?php echo $x; ?>"><img src="<?php echo $url_script; ?>/images/photo-icon.png"><br>Ajouter ou glisser une image</div>
					<input type="file" class="image-uploader-btn" id="imageloader-<?php echo $x; ?>" name="image" onchange="previewChange('<?php echo $x; ?>');">
				</div>
				<?php
			}
		}
		?>
		<script>
		var actual_media = '';
		var actual_media_type = '';
		
		function previewChange(id)
		{
			var oFReader = new FileReader();
			var typeFile = document.getElementById("imageloader-"+id).files[0].type;
			
			console.log('Type : '+typeFile);
			
			if(typeFile == 'image/png')
			{
				oFReader.readAsDataURL(document.getElementById("imageloader-"+id).files[0]);
				oFReader.onload = function (oFREvent) 
				{
					$('#image-uploader-text-'+id).html('0 %');
					$('#image-uploader-info-'+id).css('display','block');
					$('#image-uploader-temp-'+id).css('display','block');
					$('#image-uploader-temp-delete-btn-'+id).css('display','block');
					$('#image-temp-'+id).attr('src',oFREvent.target.result);
					actual_media = oFREvent.target.result;
					actual_media_type = typeFile;
					finalizeUpload(id);
				};
			}
			else if(typeFile == 'image/jpeg')
			{
				oFReader.readAsDataURL(document.getElementById("imageloader-"+id).files[0]);
				oFReader.onload = function (oFREvent) 
				{
					$('#image-uploader-text-'+id).html('0 %');
					$('#image-uploader-text-'+id).css('z-index','1000');
					$('#image-uploader-text-'+id).css('margin-top','-47px');
					$('#image-uploader-text-'+id).css('font-size','17px');
					$('#image-uploader-text-'+id).css('font-weight','bold');
					$('#image-uploader-text-'+id).css('color','#ffffff');
					$('#image-uploader-info-'+id).css('display','block');
					$('#image-uploader-temp-'+id).css('display','block');
					$('#image-temp-'+id).attr('src',oFREvent.target.result);
					actual_media = oFREvent.target.result;
					actual_media_type = typeFile;
					finalizeUpload(id);
				};
			}
			else
			{
				$('#image-uploader-text-'+id).html('<img src="<?php echo $url_script; ?>/images/photo-icon.png"><br>Ajouter une image ou glisser<br><font color=red><b>Fichier non supporter</b></font>');
			}
		}
		
		function deleteImage(id)
		{
			var imageurl = $('#image-temp-'+id).attr('src');
			imageurl = imageurl.replace("-thumb","");
			$.post("<?php echo $url_script; ?>/deleteImage.php?url="+imageurl, function( data ) 
			{
				$('#image-temp-'+id).attr('src','');
				$('#image-uploader-temp-'+id).css('display','none');
				$('#image-uploader-info-'+id).css('display','none');
				$('#image-uploader-text-'+id).html('<img src="<?php echo $url_script; ?>/images/photo-icon.png"><br>Ajouter une image ou glisser');
				$('#image-uploader-text-'+id).css('display','block');
				$('#image-uploader-text-'+id).css('margin-top','30px');
				$('#image-uploader-text-'+id).css('font-size','12px');
				$('#image-uploader-text-'+id).css('color','#000000');
				$('#image-uploader-text-'+id).css('font-weight','normal');
				$('#image-uploader-temp-delete-btn-'+id).css('display','none');
			});
		}
		
		function finalizeUpload(id)
		{
			$.ajax({
				method: "POST",
				timeout: 300000,
				url: "<?php echo $url_script; ?>/finalUpload.php",
				data: {	 
						media: actual_media,
						media_type: actual_media_type,
						idimage: id,
						md5: '<?php echo $md5; ?>'
				},
				xhr: function() 
				{
					var xhr = $.ajaxSettings.xhr();
					xhr.upload.onprogress = function(e) 
					{
						$('#image-uploader-text-'+id).html(Math.floor(e.loaded / e.total *100) + ' %');
					};
					return xhr;
				}
			})
			.done(function(msg) 
			{
				$('#image-uploader-text-'+id).css('display','none');
				$('#image-uploader-temp-delete-btn-'+id).css('display','block');
				var myarray = msg.split("#");
				$('#image-temp-'+id).attr('src',myarray[1]);
				$('#image-uploader-info-'+id).css('display','none');
			});
		}
		</script>
		<?php
	}
}

?>