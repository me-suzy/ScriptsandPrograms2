<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class template
{
	/*---------------------------------- */
	//			OLD TPL CLASS
	//		needs recoding *sigh*
	/*---------------------------------- */
	var $templatefolder = "";
	var $rootfolder = "";
	var $cssfile = "";
	var $showcomment = 0;
	var $extension = "";
	var $tplname="";
	/*----------------------------------- */
	
	function template ()
	{
		global $root,$settings,$admin,$evoLANG;

		$this->imgfolder=$root.$settings['imgfolder'];
		$this->admintpl="templates/admin/";
	}

	function process_style($tplfolder='')
	{
		global $settings,$udb,$database,$admin,$evoLANG,$scache;
		
		$tpl_folder = $tplfolder == '' ? $settings['defstyle']:$tplfolder;
		$css_loc = OUT_FOLDER."css_$tpl_folder.file";		
		
		if (file_exists($css_loc))
		{
			$css = $admin->get_file($css_loc);
		}
		else
		{
			
			if (!is_array($scache[$tpl_folder]))
			{
				$s_row = $udb->query_once("SELECT bgcolor, fontcolor, subfontcolor, fontsize, fontfamily, link, linkvisited, linkhover, tblborder, tabletitlebgcolor, tabletitlefontcolor, firstalt, secondalt, thirdalt, tplfolder FROM $database[article_styles] WHERE tplfolder='".$tpl_folder."'");
				
				if (!is_array($s_row))
				{
					$s_row = $udb->query_once("SELECT bgcolor, fontcolor, subfontcolor, fontsize, fontfamily, link, linkvisited, linkhover, tblborder, tabletitlebgcolor, tabletitlefontcolor, firstalt, secondalt, thirdalt, tplfolder FROM $database[article_styles] WHERE tplfolder='".$settings['defstyle']."'");

					$this->templatefolder = $settings['defstyle'];
				}

				$scache[$tpl_folder] = $s_row;
			}
			else
			{
				$s_row = $scache[$tpl_folder];
			}

			$css = $admin->get_file("templates/styles/".$s_row['tplfolder']."/css.inc");
			$i = 0;

			foreach ($s_row as $name => $val)
			{
				$i++;
				if (!number_format($name) && $name != "0" && $name != "tplfolder")
				{
					$css = preg_replace("/{".$name."}/i",$val,$css);
				}
			}
		}

		return $css;
	}

	function gettemplate($tplname,$timer=0,$tplfolder='') 
	{
		global $site,$cssfile,$add_links,$custom,$layout,$tpl_cache,$evoLANG,$userinfo,$settings,$usr,$root,$admin;	
		$ext = $this->extension;
		//echo $tplname." : ".$evoLANG."<br />";

		if (is_array($this->layout))
		{
			foreach ($this->layout as $lname => $lval)
			{
				$layout[$lname] = $lval;
			}
		}
		
		if (!is_array($tpl_cache[$tplname]))
		{
			$templatename = stripslashes($tplname);
			
			$tplfolder = ($tplfolder == '') ? $tplfolder=$this->templatefolder:$tplfolder;
			$tplfolder = !is_dir($tplfolder) ? str_replace(basename($tplfolder),$settings['defstyle'],$tplfolder):$tplfolder;
			
			
				// added 09/10/2002 : if template takde
				if (!file_exists($tplfolder."/".$tplname.".".$ext))
				{
					//echo $tplfolder."/".$tplname.".".$ext;
					return $evoLANG['xtpl'].": <b>".$tplname."</b>";
				}

			$code = file($tplfolder."/".$tplname.".".$ext);
			$template = implode('',$code);
			$template = preg_replace("'([\n])[\s]+'","\\1",$template);
			
			
			$template = preg_replace("/(\[)(IF:)(.*)(])(\n)*(.*)(\[\/IF\])/seiU","\$this->replace_perm('\\3','\\6')",$template);
			$template = preg_replace("/({)(include:)(.*)(})/seiU","\$this->do_include('\\3')",$template);

			$template = str_replace("\"","\\\"",$template);

			
			
			//tempaltes that name wont be showed
			$dont_detail = array(
									"_layout",
									"header",
									"footer",
									"article_print",
									"main",
									"popup",
									"phpparse"
								);

			if ($settings['tpldetail'] == 1)
			{
				if ( !in_array($tplname,$dont_detail) )
				{
					$template = "\n<!-- Template : $tplname -->\n".$template;
					$template .= "\n<!-- / Template : $tplname -->\n";	
				}
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
			$template = str_replace("{timer}",addslashes($time),$template);
		}

		
		//eval('if ('.stripslashes($found[1][$i]).'){ $true=1; }');
		//if ($true)
		//{
		//	$template = str_replace($found[0][$i],$found[3][$i],$template);
		//}
		//else
		//{
		//	$template = str_replace($found[0][$i],'',$template);
		//}

		
			
		preg_match_all("/{IF:(.*)}(\n)*(.*){\/IF}/sieU",$template,$found);
		// my great conditional trick
		if (!empty($found[1]))
		{		
			$total = count($found[1]);
			for($i=0;$i<$total;$i++)
			{
				$template = str_replace($found[0][$i],'".('.$found[1][$i].' ? "'.$found[3][$i].'":\'\')."',$template);
				//echo $template;
			}
		}
		
		return str_replace("{root}",$root,$template);
	}

	function do_include($file)
	{
		global $admin;
		
		
		/*
		ob_start(); 		
		virtual ($file);
		$content = ob_get_contents();
		ob_end_clean();
		*/		
		return ;
	}

	function do_test($what)
	{
		echo $what;
	}
	
	function replace_perm($perm,$content)
	{
		global $userinfo,$usr,$evoLANG,$admin,$udb,$settings;
		if ($usr->checkperm('',$perm,1) == true)
		{
			return stripslashes($content);
		}
	}

	function generate($file,$timer=0)
	{
		global $content,$site;
		$cssfile = $this->cssfile;
		
		if ($timer) { $content .= showtime(); }
		$this->tplname = $file;
		eval("echo(\"".$this->gettemplate($file)."\");");
	}

}
?>