<?php

include "main.php";

set_time_limit(0);

$media = $_POST['media'];
$media_type = $_POST['media_type'];
$idimage = AntiInjectionSQL($_POST['idimage']);
$md5 = AntiInjectionSQL($_POST['md5']);

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
{
	// creating a cut resource
    $cut = imagecreatetruecolor($src_w, $src_h);

    // copying relevant section from background to the cut resource
	imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    
	// copying relevant section from watermark to the cut resource
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    
	// insert cut resource to destination image
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

function correctImageOrientation($filename) 
{
  if(function_exists('exif_read_data')) 
  {
    $exif = exif_read_data($filename);
    if($exif && isset($exif['Orientation'])) 
	{
      $orientation = $exif['Orientation'];
      if($orientation != 1)
	  {
		// On detecte ici l'extension
		$extension = explode(".",$filename);
		$extension = $extension[count($extension)-1];
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
		
        $deg = 0;
        switch ($orientation) 
		{
          case 3:
            $deg = 180;
            break;
          case 6:
            $deg = 270;
            break;
          case 8:
            $deg = 90;
            break;
        }
        if($deg) 
		{
			$img = imagerotate($img, $deg, 0);        
        }
        // then rewrite the rotated image back to the disk as $filename
		if($extension == 'jpg')
		{
			imagejpeg($img, $filename, 95);
		}
		else if($extension == 'jpeg')
		{
			imagejpeg($img, $filename, 95);
		}
		else if($extension == 'png')
		{
			imagepng($img,$filename);
		}
      } // if there is some rotation necessary
    } // if have the exif orientation info
  } // if function exists      
}

function generateThumb($filename)
{
	$widthThumb = 192;
	$heightThumb = 149;
	
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

$root = $_SERVER['DOCUMENT_ROOT'].$upload_path.'/images/annonce/';
$filename = md5(microtime());

if($media_type == 'image/jpeg')
{
	$extension = '.jpg';
	$media = str_replace('data:image/jpeg;base64,', '', $media);
	$media = str_replace(' ', '+', $media);
	$media = base64_decode($media);
	file_put_contents($root.$filename.$extension, $media);
	
	$filenamefull = $filename.$extension;
	/* On genere une miniature */
	correctImageOrientation($root.$filenamefull);

	$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5','$filenamefull')";
	$pdo->query($SQL);
		
	$urlfiligrane = getConfig("filigrane");
	generateThumb($root.$filenamefull);
	if(getConfig("activer_filigrane") == 'yes')
	{
		$class_imagehelper->addFiligrane($root.$filenamefull,"images/".$urlfiligrane,getConfig("pourcent_filigrane"),getConfig("position_filigrane"),false);
	}
}
if($media_type == 'image/png')
{
	$extension = '.png';
	$media = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $media));
	file_put_contents($root.$filename.$extension, $media);
	
	$filenamefull = $filename.$extension;
	
	/* On genere une miniature */
	correctImageOrientation($root.$filenamefull);

	$SQL = "INSERT INTO pas_image (md5annonce,image) VALUES ('$md5','$filenamefull')";
	$pdo->query($SQL);
		
	$urlfiligrane = getConfig("filigrane");
	generateThumb($root.$filenamefull);
	if(getConfig("activer_filigrane") == 'yes')
	{
		$class_imagehelper->addFiligrane($root.$filenamefull,"images/".$urlfiligrane,getConfig("pourcent_filigrane"),getConfig("position_filigrane"),false);
	}
}

$thumb = explode(".",$filenamefull);
$thumb = $thumb[0]."-thumb.".$thumb[1];

echo $idimage."#".$url_script."/images/annonce/".$thumb."#".$filenamefull;

?>