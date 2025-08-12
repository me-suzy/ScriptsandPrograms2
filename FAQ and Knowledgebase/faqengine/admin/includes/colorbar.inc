<?php
function show_colorbar($pas, $w, $h, $helptext, $inputfield)
{
	$col[0] = array(255,0,255,0,255,-1);
	$col[1] = array(255,-1,255,0,0,0);
	$col[2] = array(0,0,255,0,0,1);
	$col[3] = array(0,0,255,-1,255,0);
	$col[4] = array(0,1,0,0,255,0);
	$col[5] = array(255,0,0,0,255,-1);
	$col[6] = array(255,-1,0,0,0,0);

	$text1=substr($helptext,0,strpos($helptext,"=")+1);
	$pos1=strpos($helptext,"]");
	$pos2=strpos($helptext,"/");
	$text2=substr($helptext,$pos1+1,$pos2-$pos1);

	for ($j=0;$j<6+1;$j++)
	{
		for ($i=0;$i<($pas+1);$i++)
		{
			$r = floor($col[$j][0]+$col[$j][1]*$i*255/$pas);
			$g = floor($col[$j][2]+$col[$j][3]*$i*255/$pas);
			$b = floor($col[$j][4]+$col[$j][5]*$i*255/$pas);
			$codehex = $r.$g.$b;
			echo "<td class=\"colorbar\" bgColor=\"".RGB2Hexa($r,$g,$b)."\" onClick=\"colorThis(this.bgColor,'$inputfield');lightCase(this.bgColor,'".$inputfield."ColorUsed')\" onmouseover=\"lightCase1(this.bgColor,'".$inputfield."ColorUsed1')\" title=\"".$text1.RGB2Hexa($r,$g,$b)."]".$text2."color]\" width=\"$w\" height=\"$h\"><IMG height=\"$h\" src=\"gfx/space.gif\" width=\"$w\" border=0></TD>\n";
		}
	}
}
function dec2Hexa($number)
{
	$base_hexa = "0123456789ABCDEF";
	return substr($base_hexa,floor($number / 16),1).substr($base_hexa,($number % 16),1);
}
function RGB2Hexa($TR,$TG,$TB)
{
	return "#".dec2Hexa($TR).dec2Hexa($TG).dec2Hexa($TB);
}
?>