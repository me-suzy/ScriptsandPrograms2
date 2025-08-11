<?php
	/*
	** GIF Pie Chart
	*/
	/*
	** Convert degrees to radians
	*/
	function radians($degrees) 
	{
		return($degrees * (pi()/180.0));
	}
	/*
	** get x,y pair on cirle, 
	** assuming center is 0,0
	*/
  	function circle_point($degrees, $diameter) 
  	{
		$x = cos(radians($degrees)) * ($diameter/2);
		$y = sin(radians($degrees)) * ($diameter/2);
    	return (array($x, $y));
  	}
	//fill in chart parameters
	$ChartDiameter = 300;
	$ChartFont = 5;
	$ChartFontHeight = imagefontheight($ChartFont);
	$ChartData = array($d1, $d2,$d3,$d4,$d5);
	$ChartLabel = array($l1,$l2,$l3,$l4,$l5);
	//determine graphic size
	$ChartWidth = $ChartDiameter + 20;
	$ChartHeight = $ChartDiameter + 20 +
		(($ChartFontHeight + 2) * count($ChartData));
	//determine total of all values
	for($index = 0; $index < count($ChartData); $index++)
	{
		$ChartTotal += $ChartData[$index];
	}
	$ChartCenterX = $ChartDiameter/2 + 10;
	$ChartCenterY = $ChartDiameter/2 + 10;
	//create image
	$image = imagecreate($ChartWidth, $ChartHeight);
	//allocate colors
	$colorBody = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
	$colorBorder = imagecolorallocate($image, 0x00, 0x00, 0x00);
	$colorText = imagecolorallocate($image, 0x00, 0x00, 0x00);
	$colorSlice[] = imagecolorallocate($image, 0xFF, 0x00, 0x00);
	$colorSlice[] = imagecolorallocate($image, 0x00, 0xFF, 0x00);
	$colorSlice[] = imagecolorallocate($image, 0x00, 0x00, 0xFF);
	$colorSlice[] = imagecolorallocate($image, 0xFF, 0xFF, 0x00);
	$colorSlice[] = imagecolorallocate($image, 0xFF, 0x00, 0xFF);
	$colorSlice[] = imagecolorallocate($image, 0x00, 0xFF, 0xFF);
	$colorSlice[] = imagecolorallocate($image, 0x99, 0x00, 0x00);
	$colorSlice[] = imagecolorallocate($image, 0x00, 0x99, 0x00);
	$colorSlice[] = imagecolorallocate($image, 0x00, 0x00, 0x99);
	$colorSlice[] = imagecolorallocate($image, 0x99, 0x99, 0x00);
	$colorSlice[] = imagecolorallocate($image, 0x99, 0x00, 0x99);
	$colorSlice[] = imagecolorallocate($image, 0x00, 0x99, 0x99);
	//fill background
	imagefill($image, 0, 0, $colorBody);
	/*
	** draw each slice
	*/
	$Degrees = 0;
	for($index = 0; $index < count($ChartData); $index++)
	{
		$StartDegrees = round($Degrees);
		if ($ChartTotal) $Degrees += (($ChartData[$index]/$ChartTotal)*360);
		$EndDegrees = round($Degrees);
		$CurrentColor = $colorSlice[$index%(count($colorSlice))];
		//draw arc
		imagearc($image, 
			$ChartCenterX, 
			$ChartCenterY,
			$ChartDiameter, 
			$ChartDiameter, 
			$StartDegrees, 
			$EndDegrees, 
			$CurrentColor);
		//draw start line from center
		list($ArcX, $ArcY) = circle_point($StartDegrees, $ChartDiameter);
		imageline($image, 
	    	$ChartCenterX, 
	    	$ChartCenterY,
			floor($ChartCenterX + $ArcX),
			floor($ChartCenterY + $ArcY),
			$CurrentColor);
		//draw end line from center
		list($ArcX, $ArcY) = circle_point($EndDegrees, $ChartDiameter);
		imageline($image, 
	    	$ChartCenterX, 
	    	$ChartCenterY,
			ceil($ChartCenterX + $ArcX),
			ceil($ChartCenterY + $ArcY),
			$CurrentColor);
		//fill slice
		$MidPoint = round((($EndDegrees - $StartDegrees)/2) + $StartDegrees);
		list($ArcX, $ArcY) = circle_point($MidPoint, $ChartDiameter/2);
		imagefilltoborder($image, 
			floor($ChartCenterX + $ArcX), 
			floor($ChartCenterY + $ArcY), 
			$CurrentColor, 
			$CurrentColor);
	}
	//draw border
	imagearc($image, 
		$ChartCenterX, 
		$ChartCenterY,
		$ChartDiameter, 
		$ChartDiameter, 
		0, 
		180, 
		$colorBorder);
	imagearc($image, 
		$ChartCenterX, 
		$ChartCenterY,
		$ChartDiameter, 
		$ChartDiameter, 
		180, 
		360, 
		$colorBorder);
	imagearc($image, 
		$ChartCenterX, 
		$ChartCenterY,
		$ChartDiameter+7, 
		$ChartDiameter+7, 
		0, 
		180, 
		$colorBorder);
	imagearc($image, 
		$ChartCenterX, 
		$ChartCenterY,
		$ChartDiameter+7, 
		$ChartDiameter+7, 
		180, 
		360, 
		$colorBorder);
	imagefilltoborder($image, 
		floor($ChartCenterX + ($ChartDiameter/2) + 2), 
		$ChartCenterY, 
		$colorBorder, 
		$colorBorder);
	//draw legend
	for($index = 0; $index < count($ChartData); $index++)
	{
		$CurrentColor = $colorSlice[$index%(count($colorSlice))];
		$LineY = $ChartDiameter + 20 + ($index*($ChartFontHeight+2));
		//draw color box
		imagerectangle($image, 
			10, 
			$LineY, 
			10 + $ChartFontHeight, 
			$LineY+$ChartFontHeight, 
			$colorBorder);
		imagefilltoborder($image, 
			12,
			$LineY + 2, 
			$colorBorder,
			$CurrentColor);
		//draw label
		imagestring($image,
			$ChartFont,
			20 + $ChartFontHeight,
			$LineY, 
			"$ChartLabel[$index]: $ChartData[$index]",
			$colorText);
	}
	//output image
	header("Content-type: image/gif");
	imagegif($image);
?>
