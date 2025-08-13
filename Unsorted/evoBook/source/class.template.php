<?php
class template
{
	/*---------------------------------- */
	var $templatefolder = "";
	var $rootfolder = "";
	var $cssfile = "";
	var $showcomment = 0;
	var $extension = "";
	var $smiliesdb=""; //smilies db
	var $smiliesbreak=""; // break smilies box
	var $smiliesshow="";
	var $tplname="";
	var $smilieslimit="5";
	/*----------------------------------- */
	
	function template ()
	{
		global $root,$site,$admin;
	}
	
	function gettemplate($tplname,$timer=0,$tplfolder='')
	{
		global $site,$cssfile,$add_links,$custom,$layout,$tpl_cache,$LANG_aerror,$conf;	
		$ext = $this->extension;
		
		if (!is_array($tpl_cache[$tplname]))
		{
			
			$templatename = stripslashes($tplname);			
			$tplfolder = ($tplfolder == '') ? $tplfolder=$this->templatefolder:$tplfolder;
			
				// added 09/10/2002 : if template takde
				if (!file_exists($tplfolder."/".$tplname.".".$ext))
				{
					return "Invalid Template".": <b>".$tplname."</b> (".$tplfolder."/".$tplname.".".$ext.")";
				}

			$code = file($tplfolder."/".$tplname.".".$ext);
			$template = implode('',$code);
			$template = preg_replace("'([\n])[\s]+'","\\1",$template);
			
			$template = str_replace("\"","\\\"",$template);
				
				if ($this->showcomment == 1) {
					$template = "\n<!-- Start Tpl:$templatename -->\n".$template;
					$template .= "\n<!-- End Tpl:$templatename -->\n";
				}
			$tpl_cache[$tplname] = $template;
		}
		else 
		{
			$template = $tpl_cache[$tplname];
		}

		if ($timer)
		{
			$time = showtime();
			$template = str_replace("{timer}",$time,$template);
		}

		return $template;
	}
	
	function getnav($file,$folder='')
	{
		global $site,$_SERVER;
		$PHP_SELF = $_SERVER['PHP_SELF'];
		$ext = $this->extension;

		eval("\$add_links = \"".$this->gettemplate($file,0,$folder)."\";");
		eval("\$template = \"".$this->gettemplate("addon_links")."\";");
		return $template;
		
	}

	function generate($file,$timer=0)
	{
		global $content,$site,$conf;
		$cssfile = $this->cssfile;
		
		if ($timer) $timer = showtime();
		$this->tplname = $file;
		eval("echo(\"". str_replace( "{timer}" , $timer , $this->gettemplate($file) )."\");");
	}

}
?>