<?
session_start();
session_register("DATA");
$DATA=$HTTP_SESSION_VARS["DATA"];

include "../_funct.php";

// Ðàçðåøåíèå
$W=$IMGW;
$H=$IMGH;

// Îòñòóïû
$MB=20; // bottom
$ML=8; // left
$M=5; // îñòàëüíûå

// Øèðèíà îäíîãî ñèìâîëà
$LW=imagefontwidth(2);

// Êîëè÷åñòâî ýëåìåíòîâ
$count=count($DATA[0]);
if (count($DATA[1])>$count) $count=count($DATA[1]);
if (count($DATA[2])>$count) $count=count($DATA[2]);

if ($count==0) $count=1;

// Ñãëàæèâàåì ãðàôèêè ##########################################################
if ($HTTP_GET_VARS["s"]==1) {
	for ($i=2;$i<$count-2;$i++) {
		for ($j=0;$j<$count;$j++) {
			$DATA[$j][$i]=($DATA[$j][$i-1]+$DATA[$j][$i-2]+$DATA[$j][$i]+$DATA[$j][$i+1]+$DATA[$j][$i+2])/5;
			}
		}
	}

// Ìàêñèìàëüíîå çíà÷åíèå
$max=0;
for ($i=0;$i<$count;$i++) {
	$max=$max<$DATA[0][$i]?$DATA[0][$i]:$max;
	$max=$max<$DATA[1][$i]?$DATA[1][$i]:$max;
	$max=$max<$DATA[2][$i]?$DATA[2][$i]:$max;
	}

include "shared.php";

$county=10;
$max=$nmax;

// Ïîäðàâíÿåì ëåâóþ ãðàíèöó
$text_width=strlen(cNumber($max))*$LW;
$ML+=$text_width;

// Ðåàëüíûå ðàçìåðû ãðàôèêà
$RW=$W-$ML-$M;
$RH=$H-$MB-$M;

// Êîîðäèíàòû íóëÿ
$X0=$ML;
$Y0=$H-$MB;

$step=$RH/$county;

imagefilledrectangle($im, $X0, $Y0-$RH, $X0+$RW, $Y0, $bg[1]);
imagerectangle($im, $X0, $Y0, $X0+$RW, $Y0-$RH, $c);

// Âûâîä ñåòêè ïî îñè Y
for ($i=1;$i<=$county;$i++) {
	$y=$Y0-$step*$i;
	imageline($im,$X0,$y,$X0+$RW,$y,$c);
	imageline($im,$X0,$y,$X0-($ML-$text_width)/4,$y,$text);
	}

// Âûâîä ñåòêè ïî îñè X
// Âûâîä èçìåíÿåìîé ñåòêè
for ($i=0;$i<$count;$i++) {
	imageline($im,$X0+$i*($RW/$count),$Y0,$X0+$i*($RW/$count),$Y0,$c);
	imageline($im,$X0+$i*($RW/$count),$Y0,$X0+$i*($RW/$count),$Y0-$RH,$c);
	}

// Âûâîä ñòîëáöåâ
for ($i=0;$i<$count;$i++) {
	$x=$X0+$i*($RW/$count)+2;
	$y1=$Y0-($RH/$max*$DATA[0][$i]);
	$x2=$x+intval($RW/$count)-2;
	imagefilledrectangle($im, $x, $y1, $x2, $Y0, $bar[0][0]);
	imagerectangle($im, $x, $y1, $x2, $Y0, $bar[0][2]);

	$y1=$Y0-($RH/$max*$DATA[1][$i]);
	imagefilledrectangle($im, $x, $y1, $x2, $Y0, $bar[1][0]);
	imagerectangle($im, $x, $y1, $x2, $Y0, $bar[1][2]);

	$y1=$Y0-($RH/$max*$DATA[2][$i]);
	imagefilledrectangle($im, $x, $y1, $x2, $Y0, $bar[2][0]);
	imagerectangle($im, $x, $y1, $x2, $Y0, $bar[2][2]);
	}

// Óìåíüøåíèå è ïåðåñ÷åò êîððäèíàò
$ML-=$text_width;

$text=imagecolorallocate($im,136,197,145);

// Âûâîä ïîäïèñåé ïî îñè Y
for ($i=1;$i<=$county;$i++) {
	$str=cNumber(($max/$county)*$i);
	imagestring($im,2, $X0-strlen($str)*$LW-$ML/4-2,$Y0-$step*$i-imagefontheight(2)/2,$str,$text);
	}

// Âûâîä ïîäïèñåé ïî îñè X
$prev=100000;
$twidth=$LW*strlen($DATA["x"][0])+6;
$i=$X0+$RW;

while ($i>$X0) {
	if ($prev-$twidth>$i) {
		$drawx=$i-($RW/$count)/2;
		if ($drawx>$X0) {
			$str=$DATA["x"][intval(($i-$X0)/($RW/$count))-1];
			imageline($im,$drawx,$Y0,$i-($RW/$count)/2,$Y0+5,$text);
			imagestring($im,2, $drawx-(strlen($str)*$LW)/2 ,$Y0+7,$str,$text);
			}
		$prev=$i;
		}
	$i-=$RW/$count;
	}

header("Content-Type: image/png");
imagepng($im);
imagedestroy($im);
?>