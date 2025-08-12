<?php

#	Author: Alessio Glorioso
#	Email: aleglori@gmail.com

function gallery($dir,$ncol,$w,$h,$r)
{
	$type=array("jpg","jpeg","gif","png"); #tipi di immagine supportati
	
	$handle = opendir($dir);
	$i=0;
	echo"<center><table cellspacing=1>";
	
	while (false !== ($file = readdir($handle)))
	{
		if($file != "." && $file != "..")
		{
			$ext=explode(".",$file); #prende l'estensione
			
			$ext=strtolower($ext[1]); #la converte in minuscolo
			
			if(in_array($ext,$type))
			{
				if($i==0)	echo"<tr>";			
				
				echo "<td>";
				
				thumb("$dir/","$file",$w,$h,$r);
				
				echo"</td>";
				$i++;
				
				if($i==$ncol)
				{
					echo"</tr>";
					$i=0;
				}
				
			}
		}
	}
}

function thumb($dir,$img, $wt, $ht, $resize)
{
	$file=$dir.$img;
	$th_file=$dir."thumbs/th_".$img;

	list($wi, $hi, $type, $attr) = @getimagesize($file);
	list($th_w, $th_h) = @getimagesize($th_file);
	
	if(!is_dir($dir."thumbs/"))	mkdir($dir."thumbs/");	
	
	if(file_exists($th_file) && ($wt==$th_w || $ht==$th_h))
	{
		echo"<a href=\"#\" onClick=\"window.open('visual.php?file=$file&r=100&t=$type','','height=$hi,width=$wi');\"><img src=$th_file border=0></a>";
		if($resize)
		{
			echo"<br><a href=\"#\" onClick=\"window.open('visual.php?file=$file&r=75&t=$type','','height=".(($hi+30)*0.75).",width=".(($wi+30)*0.75)."');\">75%</a> ";
			echo" <a href=\"#\" onClick=\"window.open('visual.php?file=$file&r=50&t=$type','','height=".(($hi+30)*0.50).",width=".(($wi+30)*0.50)."');\">50%</a> ";
		}
	}
	else
	{
		switch($type)
		{
			case 1: $im = @imagecreatefromgif($file);  $ext="@imagegif(\$new, \$th_file);"; break;
			case 2: $im = @imagecreatefromjpeg($file); $ext="@imagejpeg(\$new, \$th_file , 100);"; break;
			case 3: $im = @imagecreatefrompng($file); $ext="@imagepng(\$new, \$th_file);"; break;
		}
		
		if($wi<$hi)			$wt=($ht/$hi)*$wi;
		elseif ($wi>$hi)	$ht=($wt/$wi)*$hi;
		
		if($type!=1)
		{
			$new = imagecreatetruecolor($wt,$ht);
			imagecopyresampled($new , $im , 0 , 0 , 0 , 0 , $wt , $ht , $wi , $hi);
		}
		else
		{
			$new = imagecreate($wt,$ht);
			imagecopyresized($new , $im , 0 , 0 , 0 , 0, $wt , $ht , $wi , $hi);
		}
		
		eval($ext);
		
		echo"<a href=\"#\" onClick=\"window.open('visual.php?file=$file&r=100&t=$type','','height=$hi,width=$wi');\"><img src=$th_file border=0></a>";
		if($resize)
		{
			echo"<br><a href=\"#\" onClick=\"window.open('visual.php?file=$file&r=75&t=$type','','height=".(($hi+30)*0.75).",width=".(($wi+30)*0.75)."');\">75%</a> ";
			echo" <a href=\"#\" onClick=\"window.open('visual.php?file=$file&r=50&t=$type','','height=".(($hi+30)*0.50).",width=".(($wi+30)*0.50)."');\">50%</a> ";
		}
		
		@imagedestroy($im);
		@imagedestroy($new);
	}
}