<?php

// 192 x 149 - Faire un thumb


function generateThumb($filename,$finalfilename)
{
	$widthThumb = 192;
	$heightThumb = 149;
	
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
	imagejpeg($thumb,$finalfilename);
	
	return "1";
}

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

function addFiligrame($filename,$logo)
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
	else
	{
		return "0";
	}
	
	$extensionlogo = explode(".",$logo);
	$extensionlogo = $extensionlogo[count($extensionlogo)-1];
	$extensionlogo = strtolower($extensionlogo);
	
	if($extensionlogo == 'jpg')
	{
		$imglogo = imagecreatefromjpeg($logo);
	}
	else if($extensionlogo == 'jpeg')
	{
		$imglogo = imagecreatefromjpeg($logo);
	}
	else if($extensionlogo == 'png')
	{
		$imglogo = imagecreatefrompng($logo);
	}
	else
	{
		return "0";
	}
	
	$widthlogo = imagesx($imglogo);
	$heightlogo = imagesy($imglogo);
	imagecopymerge_alpha($img, $imglogo, 10, 10, 0, 0, $widthlogo, $heightlogo, 100);
	
	if($extension == 'jpg')
	{
		imagejpeg($img,$filename);
	}
	else if($extension == 'jpeg')
	{
		imagejpeg($img,$filename);
	}
	else if($extension == 'png')
	{
		imagepng($img,$filename);
	}
}

addFiligrame("clio.jpg","images/mini-logo-pas.png");
//generateThumb("logo_original_shua.png","shua.png");

?>