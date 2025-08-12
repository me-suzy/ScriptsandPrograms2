<?
	include_once("3dlib.htp");

	$i = @ImageCreate(640, 480) or die("Can't create image");
	$white = ImageColorAllocate($i, 255, 255, 255);
	$black = ImageColorAllocate($i, 0, 0, 0);
	$C[sizeof($C)] = ImageColorAllocate($i, 255, 0, 0);
	$C[sizeof($C)] = ImageColorAllocate($i, 0, 255, 0);
	$C[sizeof($C)] = ImageColorAllocate($i, 0, 0, 255);
	$C[sizeof($C)] = ImageColorAllocate($i, 255, 255, 255);
	$C["axis"] = $black;
	$C["grid"] = $black;
	$C["border"] = $black;
	
	srand((double)microtime()*1000000);
	$bar_num = rand(2,40);
	$white = rand(0,20);
	$font = rand(1,3);
	for($j=0; $j<$bar_num; $j++) {
		$D[$j] = rand(0,1000);
		$Legend[$j] = $j;
	}

	$L = new C3DLib(100, 360);
	$L->chart_font = $font;
	$L->chart_white = $white;
	$L->mChart3d($i, $D, $Legend, 460, 100, 300, $C, 100, "$bar_num bars, $white% white space, font $font", "rand(0, 1000)");

	ImagePNG($i);
?>