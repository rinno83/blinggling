<?php 
	function image_resize($file, $saveFileName, $w, $h, $crop=FALSE)
	{
		ini_set('memory_limit', '-1');
		
		$size = getimagesize($file);
		$width = $size[0];
		$height = $size[1];
		$imageType = $size[2];
		
	    $r = $width / $height;
	    if ($crop) {
	        if ($width > $height) {
	            $width = ceil($width-($width*abs($r-$w/$h)));
	        } else {
	            $height = ceil($height-($height*abs($r-$w/$h)));
	        }
	        $newwidth = $w;
	        $newheight = $h;
	    } else {
	    	if($w == 0)
	    	{
		    	$newwidth = $h*$r;
	            $newheight = $h;
	    	}
	    	else if($h == 0)
	    	{
		    	$newheight = $w/$r;
	            $newwidth = $w;
	    	}
	        else if ($w/$h > $r) {
	            $newwidth = $h*$r;
	            $newheight = $h;
	        } else {
	            $newheight = $w/$r;
	            $newwidth = $w;
	        }
	    }
	    
	    //$src = $this->setImage($file, $imageType);
	    if($imageType == IMAGETYPE_GIF)
	    {
		    $src = imagecreatefromgif($file);
	    }
	    else if($imageType == IMAGETYPE_PNG)
	    {
		    $src = imagecreatefrompng($file);
	    }
	    else if($imageType == IMAGETYPE_BMP)
	    {
		    $src = imagecreatefromwbmp($file);
	    }
	    else // IMAGETYPE_JPEG or ETC..
	    {
		    $src = imagecreatefromjpeg($file);
	    }
	    $dst = imagecreatetruecolor($newwidth, $newheight);
	    
	    imagealphablending($dst, false);
	    imagesavealpha($dst,true);
	    $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
	    imagefilledrectangle($dst, 0, 0, $newwidth, $newheight, $transparent);
	    
	    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	    
	    //$temp = $this->saveImages($dst, $imageType, $saveFileName);
	    if($imageType == IMAGETYPE_GIF)
	    {
		    $result = imagegif($dst, $saveFileName);
	    }
	    else if($imageType == IMAGETYPE_PNG)
	    {
		    $result = imagepng($dst, $saveFileName);
	    }
	    else if($imageType == IMAGETYPE_BMP)
	    {
		    $result = imagewbmp($dst, $saveFileName);
	    }
	    else // IMAGETYPE_JPEG or ETC..
	    {
		    $result = imagejpeg($dst, $saveFileName);
	    }
	
	    return $dst;
	}
	
	
	
	function setImage($image, $imageType)
	{
		if($imageType == IMAGETYPE_GIF)
	    {
		    $src = imagecreatefromgif($image);
	    }
	    else if($imageType == IMAGETYPE_PNG)
	    {
		    $src = imagecreatefrompng($image);
	    }
	    else if($imageType == IMAGETYPE_BMP)
	    {
		    $src = imagecreatefromwbmp($image);
	    }
	    else // IMAGETYPE_JPEG or ETC..
	    {
		    $src = imagecreatefromjpeg($image);
	    }
	    
	    return $src;
	}
	
	function saveImages($image, $imageType, $fileName)
	{
		$result = NULL;
		if($imageType == IMAGETYPE_GIF)
	    {
		    $result = imagegif($image, $fileName);
	    }
	    else if($imageType == IMAGETYPE_PNG)
	    {
		    $result = imagepng($image, $fileName);
	    }
	    else if($imageType == IMAGETYPE_BMP)
	    {
		    $result = imagewbmp($image, $fileName);
	    }
	    else // IMAGETYPE_JPEG or ETC..
	    {
		    $result = imagejpeg($image, $fileName);
	    }
		
		return $result;
	}
?>