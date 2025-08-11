<?php
$GraphWidth = 400;
	$GraphHeight = 200;
    if (!isset($sum)) $GraphScale = 2;
    if ($sum <= 50)$GraphScale = 4;
    else $GraphScale =150/ $sum;
	$GraphFont = 5;
	$GraphData = array($d1, $d2,$d3,$d4,$d5,$d6,$d7);
	$GraphLabel = array($l1,$l2,$l3,$l4,$l5,$l6,$l7);
	//create image
	$image = imagecreate($GraphWidth, $GraphHeight);
	//allocate colors
	$colorBody = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
	$colorGrid = imagecolorallocate($image, 0xCC, 0xCC, 0xCC);
	$colorBar = imagecolorallocate($image, 0x5B, 0x69, 0xA6);
	$colorText = imagecolorallocate($image, 0x00, 0x00, 0x00);
	//fill background
	imagefill($image, 0, 0, $colorBody);
	//draw vertical grid line
	$GridLabelWidth = imagefontwidth($GraphFont)*3 + 1;
	imageline($image, 
		$GridLabelWidth, 0, 
		$GridLabelWidth, $GraphHeight-1, 
		$colorGrid);
	//draw horizontal grid lines
	for($index = 0; $index < $GraphHeight; $index += $GraphHeight/10)
	{
		imagedashedline($image, 
			0, $index, 
			$GraphWidth-1, $index, 
			$colorGrid);
		//draw label
		imagestring($image,
			$GraphFont,
			0,
			$index,
			round(($GraphHeight - $index)/$GraphScale),
			$colorText);
	}
	//add bottom line
	imageline($image, 
		0, $GraphHeight-1, 
		$GraphWidth-1, $GraphHeight-1, 
		$colorGrid);
	//draw each bar
	$BarWidth = (($GraphWidth-$GridLabelWidth)/count($GraphData)) - 10;
	for($index = 0; $index < count($GraphData); $index++)
	{
		//draw bar
		$BarTopX = $GridLabelWidth + (($index+1) * 10) + ($index * $BarWidth);
		$BarBottomX = $BarTopX + $BarWidth;
		$BarBottomY = $GraphHeight-1;
		$BarTopY = $BarBottomY - ($GraphData[$index] * $GraphScale);
		imagefilledrectangle($image, 
			$BarTopX, $BarTopY, 
			$BarBottomX, $BarBottomY, 
			$colorBar);
		//draw label
		$LabelX = $BarTopX + 
			(($BarBottomX - $BarTopX)/2) - 
			(imagefontheight($GraphFont)/2);
		$LabelY = $BarBottomY-10;
		imagestringup($image,
			$GraphFont,
			$LabelX,
			$LabelY,
			"$GraphLabel[$index]: $GraphData[$index]",
			$colorText);
	}
	//output image
	header("Content-type: image/gif");
	imagegif($image);
    ?>
