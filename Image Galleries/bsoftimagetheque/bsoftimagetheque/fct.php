<?
function verifima($filename)
{
	$ext = strrchr ( $filename , "." );
	if((!strcasecmp ($ext, ".jpeg")) || (!strcasecmp ($ext, ".jpg")) || (!strcasecmp ($ext, ".gif")) || (!strcasecmp ($ext, ".png")))
		return true;
	else
		return false;
}

function redimage($img_src,$img_dest,$dst_w='',$dst_h='') 
{
	if (!file_exists($img_dest)) // on ne recrée pas si la vignette existe deja.
  	{ 
		// Lit les dimensions de l'image
		$size = GetImageSize($img_src);  
		$src_w = $size[0]; $src_h = $size[1];  
		// Teste les dimensions tenant dans la zone
		$test_h = round(($dst_w / $src_w) * $src_h);
		$test_w = round(($dst_h / $src_h) * $src_w);
		// Si Height final non précisé (0)
		if(!$dst_h) $dst_h = $test_h;
		// Sinon si Width final non précisé (0)
		elseif(!$dst_w) $dst_w = $test_w;
		// Sinon teste quel redimensionnement tient dans la zone
		elseif($test_h>$dst_h) $dst_w = $test_w;
		else $dst_h = $test_h;

		// Crée une image vierge aux bonnes dimensions
   		$dst_im = imagecreatetruecolor($dst_w,$dst_h);
		$ext = strrchr ( $img_src , "." );
		switch ($ext) {
			case ".jpeg" :
			case ".jpg" :
				$src_im = ImageCreateFromJpeg($img_src);
				break;
			case ".gif" :
				$src_im = imagecreatefromgif($img_src);
				break;
			case ".png" :
				$src_im = imagecreatefrompng($img_src);
				break;
		}
		// Copie dedans l'image initiale redimensionnée
   		ImageCopyResized($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
	   // Sauve la nouvelle image
		switch ($ext) {
			case ".jpeg" :
			case ".jpg" :
				ImageJpeg($dst_im,$img_dest);
				break;
			case ".gif" :
			   	imagegif($dst_im,$img_dest);
				break;
			case ".png" :
			   	imagepng($dst_im,$img_dest);
				break;
		}
	   // Détruis les tampons
	   ImageDestroy($dst_im);  
	   ImageDestroy($src_im);
	}	
   // Affiche le descritif de la vignette
   $affich_vig = "src='".$img_dest."' width=".$dst_w." height=".$dst_h;
	return $affich_vig;
}

function chercheiptc($val,$rep)
{
	$chemin1 = "image/".$rep;
	$handle=opendir($chemin1);
	while (false !== ($filename = readdir($handle))) 
	{
	  	if(verifima($filename))
	    {
			$size = getimagesize("image/$rep/$filename", $info);
			if (isset($info["APP13"])) 
			{
			  	$iptc = iptcparse($info["APP13"]);
			  	if (isset($iptc["2#025"])) {
			  		if (in_array($val,$iptc["2#025"])) $resu[] = "image/$rep/$filename";
			  	}
			}
		}
	}
	return $resu;
}

$change_couleur_td = "onmouseover=\"javascript:style.backgroundColor='#c0c0c0';\"";
$change_couleur_td .= "onmouseout=\"javascript:style.backgroundColor='#9B9B9B';\"";
$gris = "style='filter:alpha(opacity=50); -moz-opacity: .5;' onMouseover='makevisible(this,0)' onMouseout='makevisible(this,1)'";

?>

