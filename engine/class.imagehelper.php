<?php

/* Classe Image Helper Shua-Creation 2018 */

class ImageHelper
{
	function __construct()
	{
	}
	
	/* Generation d'un favicon */
	function generateFavicon($filename)
	{
		$f = explode(".",$filename);
		$extension = $f[count($f)-1];
		
		if($extension == 'png')
		{
			$img = imagecreatefrompng($filename);
			$width = imagesx($img);
			$height = imagesy($img);
			
			if($width < 192)
			{
				$array['error'] = "L'image doit faire au minimum 192x192 pixels";
				return $array;
			}
			if($height < 192)
			{
				$array['error'] = "L'image doit faire au minimum 192x192 pixels";
				return $array;
			}
			
			$this->createFavicon($img,16,$width,$height,'favicon-16x16.png');
			$this->createFavicon($img,32,$width,$height,'favicon-32x32.png');
			$this->createFavicon($img,57,$width,$height,'apple-icon-57x57.png');
			$this->createFavicon($img,60,$width,$height,'apple-icon-60x60.png');
			$this->createFavicon($img,72,$width,$height,'apple-icon-72x72.png');
			$this->createFavicon($img,76,$width,$height,'apple-icon-76x76.png');
			$this->createFavicon($img,96,$width,$height,'favicon-96x96.png');
			$this->createFavicon($img,114,$width,$height,'apple-icon-114x114.png');
			$this->createFavicon($img,120,$width,$height,'apple-icon-120x120.png');
			$this->createFavicon($img,144,$width,$height,'apple-icon-144x144.png');
			$this->createFavicon($img,152,$width,$height,'apple-icon-152x152.png');
			$this->createFavicon($img,180,$width,$height,'apple-icon-180x180.png');
			$this->createFavicon($img,192,$width,$height,'android-icon-192x192.png');
			
			$array['error'] = "";
			$array['retour'] = "ok";
		}
		else
		{
			$array['error'] = "L'image doit être au format PNG";
		}
		
		return $array;
	}
	
	/* Création d'un Favicon */
	function createFavicon($image,$size,$width,$height,$filename)
	{
		$chemin = "../favicon/";
		
		$img2 = imagecreatetruecolor($size,$size);
		imagealphablending($img2, false);
		imagesavealpha($img2, true);
		imagecopyresampled($img2,$image,0,0,0,0,$size,$size,$width,$height);
			
		imagepng($img2,$chemin.$filename);
		imagedestroy($img2);
	}
	
	/* Ajout d'un filigrane sur une image, le filigrane doit être au format PNG */
	/* Choix du pourcentage du filigrane par rapport à l'image */
	/* Définition de sa position sur l'image */
	/* center, topleft, topright, bottomleft, bottomright */
	function addFiligrane($imagesource,$imagefiligrane,$pourcentage,$position,$live = false)
	{
		$extension = explode(".",$imagefiligrane);
		$extension = $extension[count($extension)-1];
		$extension = strtolower($extension);
		
		$array = NULL;
		
		if($extension == 'jpg')
		{
			$array['error'] = true;
			$array['error_msg'] = 'Le filigrane doit être au format PNG';
			return $array;
			exit;
		}
		else if($extension == 'jpeg')
		{
			$array['error'] = true;
			$array['error_msg'] = 'Le filigrane doit être au format PNG';
			return $array;
			exit;
		}
		else if($extension == 'png')
		{
			$filigrane = imagecreatefrompng($imagefiligrane);
		}
		
		$width_filigrane = imagesx($filigrane);
		$height_filigrane = imagesy($filigrane);
		
		$extension = explode(".",$imagesource);
		$extension = $extension[count($extension)-1];
		$extension = strtolower($extension);
		
		if($extension == 'jpg')
		{
			$source = imagecreatefromjpeg($imagesource);
		}
		else if($extension == 'jpeg')
		{
			$source = imagecreatefromjpeg($imagesource);
		}
		else if($extension == 'png')
		{
			$source = imagecreatefrompng($imagesource);
		}
		
		$width_source = imagesx($source);
		$height_source = imagesy($source);
		
		$result_pourcent = ($width_source / 100) * $pourcentage;
		$ratio = ($result_pourcent / $width_filigrane);
		
		$ratio_width = $width_filigrane * $ratio;
		$ratio_height = $height_filigrane * $ratio;
		
		$x = 0;
		$y = 0;
		
		if($position == 'center')
		{
			$x = ($width_source / 2) - ($ratio_width / 2);
			$y = ($height_source / 2) - ($ratio_height / 2);
		}
		else if($position == 'topleft')
		{
			$x = ($width_source / 100) * 1;
			$y = ($height_source / 100) * 1;
		}
		else if($position == 'bottomleft')
		{
			$x = ($width_source / 100) * 1;
			$y = $height_source - $ratio_height - (($height_source / 100) * 1);
		}
		else if($position == 'topright')
		{
			$x = $width_source - $ratio_width - (($width_source / 100) * 1);
			$y = ($height_source / 100) * 1;
		}
		else if($position == 'bottomright')
		{
			$x = $width_source - $ratio_width - (($width_source / 100) * 1);
			$y = $height_source - $ratio_height - (($height_source / 100) * 1);
		}

		imagecopyresampled($source,$filigrane,$x,$y,0,0,$ratio_width,$ratio_height,$width_filigrane,$height_filigrane);
		if($live)
		{
			header('Content-Type: image/jpeg');
			imagejpeg($source);
		}
		else
		{
			if($extension == 'jpg')
			{
				imagejpeg($source,$imagesource);
			}
			else if($extension == 'jpeg')
			{
				imagejpeg($source,$imagesource);
			}
			else if($extension == 'png')
			{
				imagepng($source,$imagesource);
			}
		}
		imagedestroy($source);
		imagedestroy($filigrane);
	}
	
	/* Generation d'une miniature */
	function generateThumb($filename,$width,$height)
	{
		$widthThumb = $width;
		$heightThumb = $height;
		
		// On detecte ici l'extension
		$extension = explode(".",$filename);
		$extension = $extension[count($extension)-1];
		$filenamecut = str_replace(".".$extension,"",$filename);
		$extension = strtolower($extension);
		
		if($extension == 'jpg')
		{
			$img = imagecreatefromjpeg($filename);
		}
		else if($extension == 'jpeg')
		{
			$img = imagecreatefromjpeg($filename);
		}
		else if($extension == 'png')
		{
			$img = imagecreatefrompng($filename);
		}
		else
		{
			return "0";
		}
			
		$width = imagesx($img);
		$height = imagesy($img);

		if($width > $height)
		{
			$ratio = $widthThumb / $width;
		}
		else
		{
			$ratio = $heightThumb / $height;
		}

		$thumb = imagecreatetruecolor($widthThumb,$heightThumb);
		$color = imagecolorallocate($thumb,180,180,180);
		imagefill($thumb,0,0,$color);
		$ratioWidth = $width * $ratio;
		$ratioHeight = $height * $ratio;
		$ratioPosHeight = 0;
		$ratioPosWidth = 0;

		if($ratioHeight < $heightThumb)
		{
			$ratioPosHeight = ($heightThumb/2)-($ratioHeight/2);
		}

		if($ratioWidth < $widthThumb)
		{
			$ratioPosWidth = ($widthThumb/2)-($ratioWidth/2);
		}

		imagecopyresampled($thumb,$img,$ratioPosWidth,$ratioPosHeight,0,0,$ratioWidth,$ratioHeight,$width,$height);
		imagejpeg($thumb,$filenamecut."-thumb.".$extension);
		
		return "1";
	}
}

?>