<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+


error_reporting (E_ERROR|E_WARNING|E_PARSE);
require("./admin_common.php");
$usr->checkperm('',"isadmin");
// *************************************************** //
$script['version'] = "1.0";
$script['filename'] = "styles.php";
$script['name'] = "Style Editor";
$script['prefix'] = "tpl";
$site['title'] = "Style Editor";
$script['tplfolder'] = $tpl->templatefolder."/styles";

// *************************************************** //
$PHP_SELF = $_SERVER['PHP_SELF'];

class Styles
{
	function Styles()
	{
		global $root;
		$this->stylepath = $root."templates/styles/";
		$this->cannot_delete = "1";
	}

	function unpacker($dir="templates/styles/",$newname="newstyle")
	{
		global $admin,$_GET;

		$maindir = $dir;
		$a2 = opendir($maindir);
		while ($ddir= readdir($a2))
		{
			if(($ddir != ".") && ($ddir != ".."))
			{
				$dfile = explode(".",$ddir);
				if ($admin->get_ext($ddir) == "tmpl")
				{
					$c .= "Folder : <b>$dfile[0]</b><Br />";
				/*----------------------------------------*/
					
					$get = $admin->get_file($maindir.$ddir);
					$get2 = explode("******",$get);
					unset($i);
					while ( $i < count($get2) )
					{
						$i++;
						@mkdir($maindir.$newname,0777);
						@chmod($maindir.$newname,0777);
						if (trim($get2[$i]) != "")
						{	
							$get3 = explode("|||",$get2[$i]);
							//$get3[0] //filename
							//$get3[1] //content
						
							$admin->write_file($maindir.$newname."/".$get3[0],$get3[1]);
							@chmod($maindir.$dfile[0]."/".$get3[0],0666);
							$c .= $maindir.$dfile[0]."/".$get3[0]."<Br />";
						}											
					}
				/*----------------------------------------*/
				$c .= "<br />";
				}
			}
		}
		closedir($a2);
		return $c;
	}

	function makedefault($id)
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database;
		if (number_format($id) != '')
		{
			$udb->query("UPDATE $database[article_styles] SET isdefault='1' WHERE id='$id'");
			$udb->query("UPDATE $database[article_styles] SET isdefault='0' WHERE id <> '$id'");
		}
	}
	
	function wrap_confirm($text,$loc)
	{
		$js = "var tanya = confirm('Are you sure?'); if (tanya == true) { window.location = '".$loc."'}";
		return "<span style=\"cursor: hand; border-bottom: 1px dashed #333\" onclick=\"".$js."\">".$text."</span>";
	}


	
	function main()
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database;
		global $evoLANG;
		
		$sql = $udb->query("SELECT *
							FROM $database[article_styles]
							ORDER BY id
							");


		while ($row = $udb->fetch_array($sql))
		{
			$i++;
			$bg = ($row['isdefault'] == "1") ? $admin->get_bg("",3): $admin->get_bg($i);
			$bg2 = ($row['isdefault'] == "1") ? $admin->get_bg("",3): $admin->get_bg($i+1);
			$def = ($row['isdefault'] == "1") ? "<font color=\"red\" title=\"Default Style\">*</font>":"";

			$delete = "[".$this->wrap_confirm("delete",$_SERVER['PHP_SELF']."?do=delete&id=$row[id]")."]";
			eval("\$loop .= \"".$tpl->gettemplate("styles_loop",0,$script[tplfolder])."\";");
		}

		eval("\$a = \"".$tpl->gettemplate("styles_main",0,$script[tplfolder])."\";");
		
		$b .= $admin->form_start("importform","$_SERVER[PHP_SELF]?do=import",1);
		$admin->row_align="left";
		$b .= $admin->add_row($evoLANG['import'],$admin->form_input("file","","file"));
		$b .= $admin->form_submit("import");
		$a .= "<br /><br />".$admin->add_table($admin->add_spacer($evoLANG['import']).$b,"65%");
		return $a;
	}

	function add()
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database;
		global $evoLANG;
		
		$total = $udb->query_once("SELECT COUNT(id) as totalstyles FROM $database[article_styles]");
		$newtotal = $total['totalstyles']+1;
		$folder = "style_".$newtotal;

		eval("\$a .= \"".$tpl->gettemplate("add",0,$script[tplfolder])."\";");		
		return $a;
	}

	

	function sql_add()
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database,$_SERVER;
		global $_POST;
		
		$path = $this->stylepath;
		$this->unpacker($path,$_POST['tplfolder']);
		
		$udb->query("INSERT INTO ".$database['article_styles']." VALUES ('', '".$_POST['name']."', '".$_POST['tplfolder']."', 1, '#ffffff', '#000000', '#990000', 'Verdana, Arial, Helvetica, sans-serif', '10px', '#0000FF', '#5A267F', '#000000', '#F4F4F4', '#F2F2EB', '#BABAAC', '#A8A89A', '#990000', '#ffffff')");

		header("Location: $_SERVER[PHP_SELF]");
		exit;
	}

	function templates($dir='')
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database,$root;
		global $evoLANG,$_GET,$cssfile;
		
			$stylepath = $this->stylepath;
			$dir = $stylepath.$dir;
			if (isset($_GET['file']) && $_GET['file'] != "")
			{
				$filecontent = $admin->get_file($dir."/".$_GET['file']);
				$filecontent = htmlspecialchars($filecontent);
				$filename = str_replace(".inc","",$_GET['file']);
				$f_ = $dir."/".$_GET['file'];
			}
			
			$sql = $udb->query("SELECT * FROM $database[article_styles]");
			while ($row = $udb->fetch_array($sql))
			{
				$i++;
				$row = $admin->strip_array($row);
				$_stloop .= $row[tplfolder]."|".$row[name].",";
				unset($chk);
				$chk = ($_GET['dir'] == $row['tplfolder']) ? "checked":"";
				$applyloop .= '<input type="checkbox" name="apply['.$i.']" value="'.$stylepath.$row[tplfolder]."/".$_GET['file'].'" '.$chk.'> <b><a href="'.$_SERVER[PHP_SELF].'?do=templates&amp;file='.$_GET['file'].'&amp;dir='.$row[tplfolder].'">'.$row[name].'</a></b> ';
			}

			$additional = $admin->add_row("<small>Apply For</small>",$applyloop,"","left");
			$styleslist = $admin->form_select("styles",$_stloop,$_GET['dir'],"","onchange=\"window.location=('$PHP_SELF?do=templates&amp;file=".$_GET['file']."&amp;dir='+this.options[this.selectedIndex].value)\"");

			
			$f = dir($dir);
			$tpl_files = array();
			while (false != ($file = $f->read()))
			{
				if ($file != "." && $file != ".." && !is_dir($file))
				{				
					if(strstr($file,".inc"))
					{
						array_push($tpl_files,$file);
					}
				}			
			}
			$f->close();

			foreach ($tpl_files as $val)
			{
				$list .= $val."|".basename($val,".inc");
				if ($val != end($tpl_files))
				{
					$list .= ",";
				}
			}


			$templates_list = $admin->form_select("file",$list,$_GET['file'],"","onchange=\"window.location=('$PHP_SELF?do=templates&amp;dir=".$_GET['dir']."&amp;file='+this.options[this.selectedIndex].value)\"");

		eval("\$a .= \"".$tpl->gettemplate("tpledit",0,$script[tplfolder])."\";");
		return $a;

	}

	function add_row($title,$value,$replace,$desc='')
	{		
		global $admin;
		
			$this->align = ($this->align == "") ? "left":$this->align;
			$this->row_width = ($this->row_width == "") ? "30%":$this->row_width;
			$this->row_fontsize = ($this->row_fontsize == "") ? "8pt":$this->row_fontsize;

			$align = $this->align;
			$this->rowcount++;

			$a = "<tr> <td ".$admin->get_bg($this->rowcount)." valign=\"top\" width=\"$this->row_width\"><b style='font-size:".$this->row_fontsize."'>$title</b><br />$this->row_desc</td>";
			$a .= "<td style='font-size:".$this->row_fontsize."' ".$admin->get_bg($this->rowcount)." align='".$this->row_align."'> $value </td>";
			$a .= "<td style='font-size:".$this->row_fontsize."' ".$admin->get_bg($this->rowcount)." align='".$this->row_align."'> $replace </td></tr>\n";

			return $a;
	}
	
	function add_crow()
	{		
		/*
		 -- crow_title,crow_name,crow_val -- 
		*/

		global $admin;
		
			$this->align = ($this->align == "") ? "left":$this->align;
			$this->row_width = ($this->row_width == "") ? "30%":$this->row_width;
			$this->row_fontsize = ($this->row_fontsize == "") ? "8pt":$this->row_fontsize;
			$this->crow_type = ($this->crow_type == "") ? "text":$this->crow_type;
			$this->crow_bg = ($this->crow_val == "") ? "#c0c0c0":$this->crow_val;
			
			$align = $this->align;
			$this->rowcount++;
	
			$a .= "<tr ".$admin->get_bg($this->rowcount)." valign=\"top\">\n";
			$a .= " <td width=\"$this->crow_width\"><b>$this->crow_title</b></td>\n";
			$a .= " <td width=\"60%\"><input type=\"$this->crow_type\" $this->crow_additional name=\"style[$this->crow_name]\" value=\"$this->crow_val\" onchange=\"changecolor(this.form.p_$this->crow_name,this.value)\"></td>\n";
			$a .= " <td><input type=\"button\" id=\"p_$this->crow_name\" value=\"          \" style=\"background-color:$this->crow_bg\" disabled /></td>\n";
			$a .= "  </tr>";

			return $a;
	}

	


	function edit($id)
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database,$root;
		global $evoLANG,$_SERVER,$_GET,$_POST;
		if (!$id)
		{
			header("Location : $_SERVER[HTTP_REFERER]");
		}
		
		$row = $udb->query_once("SELECT * from $database[article_styles] WHERE id='$id'");
		if ($_POST['edit'])
		{
			//print_r($row);
			foreach($_POST['style'] as $name => $val)
			{
				$style_sql .= $name."= '$val'";
				$style_sql .= ", ";
				//$_POST['file']['css'] = preg_replace("/{".$name."}/i",$val,$_POST['file']['css']);
			}

			$style_sql = trim(substr($style_sql,0,-2))." ";
			$udb->query("UPDATE $database[article_styles] SET $style_sql WHERE id = '".$_REQUEST['id']."'");

			$udb->query("UPDATE $database[article_styles]
						 SET 
							name='".$admin->slash($_POST['name'])."',
							tplfolder='".$admin->slash($_POST['tplfolder'])."',
							$style_sql
						 WHERE id='".$_POST['id']."'
						 ");
			//print_r($_POST);
			//exit;
			$admin->write_file($this->stylepath.$row['tplfolder']."/css.inc",$_POST['file']['css']);
			$admin->nocache();
			
			/* ------------------------ */
			$s_row = $udb->query_once("SELECT bgcolor, fontcolor, subfontcolor, fontsize, fontfamily, link, linkvisited, linkhover, tblborder, tabletitlebgcolor, tabletitlefontcolor, firstalt, secondalt, thirdalt,tplfolder FROM $database[article_styles] WHERE id='$_GET[id]'");

			$css = $admin->get_file($root."templates/styles/".$s_row['tplfolder']."/css.inc");
			$i = 0;
			foreach ($s_row as $name => $val)
			{
				$i++;
				if (!number_format($name) && $name != "0" && $name != "tplfolder")
				{
					$css = preg_replace("/{".$name."}/i",$val,$css);
				}
			}

			$admin->write_file( OUT_FOLDER."css_".$row[tplfolder].".file" ,$css);
			/*------------------------- */
			
			if ($_POST['file'])
			{
				while(list($name,$content) = each($_POST['file']))
				{
					$content = str_replace("
","",$content); //dunno
					$admin->write_file($this->stylepath.$row['tplfolder']."/".$name.".inc",$content);
				}			
			}

			$done = $evoLANG['done']." <br />";
			header("location: $_SERVER[REQUEST_URI]");
			exit();
		}
		
		
		
		

		//$tplarray = array("phpparse","header","main_page","footer");
		$tplarray = array("phpparse","header","footer");

		foreach($tplarray as $name)
		{	
			$i++;
			$bg = $admin->get_bg($i);
			$name2 = $name;
			$file[$name] = htmlspecialchars(trim($admin->get_file($this->stylepath.$row['tplfolder']."/".$name.".inc")));
			eval("\$tplloop .= \"".$tpl->gettemplate("styles_tplloop",0,$script[tplfolder])."\";");
		}
		$this->crow_width = "30%";
		/*
		 -- crow_title,crow_name,crow_val -- 
		*/
		/* ----------------------------------------------- */
		  $head = "General Settings";	
		    
			$this->crow_title = "Backgroud Color";
		    $this->crow_name = "bgcolor";
		    $this->crow_val = $row['bgcolor'];
		    $stuff .= $this->add_crow();

			$this->crow_title = "Font Color";
		    $this->crow_name = "fontcolor";
		    $this->crow_val = $row['fontcolor'];
		    $stuff .= $this->add_crow();

			$this->crow_title = "Sub-Header Font Color";
		    $this->crow_name = "subfontcolor";
		    $this->crow_val = $row['subfontcolor'];
			$stuff .= $this->add_crow();
	
		    $stuff .= $this->add_row("Font Size",$admin->form_input("style[fontsize]",$row['fontsize']),"");

			$this->row_desc = "Ex. Arial,Verdana";
			$stuff .= $this->add_row("Font Family",$admin->form_input("style[fontfamily]",$row['fontfamily']),"");
			
		/* =============================================== */
		  eval("\$replacements .= \"".$tpl->gettemplate("table",0,$script[tplfolder])."\";");
		  $replacements .= "<br />";
		  unset ($stuff,$head,$end);
		/* =============================================== */
		
		/* ----------------------------------------------- */
		  $head = "Link Colors";	
		    
			$this->crow_title = "Active Link Color";
		    $this->crow_name = "link";
		    $this->crow_val = $row['link'];
		    $stuff .= $this->add_crow();

			$this->crow_title = "Link Visited Color";
		    $this->crow_name = "linkvisited";
		    $this->crow_val = $row['linkvisited'];
		    $stuff .= $this->add_crow();

			$this->crow_title = "Link Hover Color";
		    $this->crow_name = "linkhover";
		    $this->crow_val = $row['linkhover'];
			$stuff .= $this->add_crow();
		
		/* =============================================== */
		  eval("\$replacements .= \"".$tpl->gettemplate("table",0,$script[tplfolder])."\";");
		  $replacements .= "<br />";
		  unset ($stuff,$head,$end);
		/* =============================================== */
		/* ----------------------------------------------- */
		  $head = "Table Colors";

			$this->crow_title = "Table Border Color";
		    $this->crow_name = "tblborder";
		    $this->crow_val = $row['tblborder'];
			$stuff .= $this->add_crow();

			$this->crow_title = "Table Title Background Color";
		    $this->crow_name = "tabletitlebgcolor";
		    $this->crow_val = $row['tabletitlebgcolor'];
			$stuff .= $this->add_crow();

			$this->crow_title = "Table Title Font Color";
		    $this->crow_name = "tabletitlefontcolor";
		    $this->crow_val = $row['tabletitlefontcolor'];
			$stuff .= $this->add_crow();
		    
			$this->crow_title = "First Alternating Cell Color";
		    $this->crow_name = "firstalt";
		    $this->crow_val = $row['firstalt'];
		    $stuff .= $this->add_crow();

			$this->crow_title = "Second Alternating Cell Color";
		    $this->crow_name = "secondalt";
		    $this->crow_val = $row['secondalt'];
		    $stuff .= $this->add_crow();

			$this->crow_title = "Third Alternating Cell Color";
		    $this->crow_name = "thirdalt";
		    $this->crow_val = $row['thirdalt'];
			$stuff .= $this->add_crow();		
			

		/* =============================================== */
		  eval("\$replacements .= \"".$tpl->gettemplate("table",0,$script[tplfolder])."\";");
		  $replacements .= "<br />";
		  unset ($stuff,$head,$end);
		/* =============================================== */
		
		

		/* =============================================== */
		  $head = "CSS File";
		  $name = "css";
		  $name2 = "css";
		  $file[name] = $name2;

		  $file[$name] = htmlspecialchars(trim($admin->get_file($this->stylepath.$row['tplfolder']."/css.inc")));
		  $file[$name] = $admin->strip($file[$name]);
		  eval("\$stuff .= \"".$tpl->gettemplate("styles_tplloop",0,$script[tplfolder])."\";");

		  eval("\$replacements .= \"".$tpl->gettemplate("table",0,$script[tplfolder])."\";");
		  $replacements .= "<br />";
		  unset ($stuff,$head,$end);
		/* =============================================== */

		eval("\$a .= \"".$tpl->gettemplate("edit",0,$script[tplfolder])."\";");
		
		return $a;
	}

	function medit($dir='')
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database,$root;
		global $evoLANG,$_SERVER,$_GET,$_POST;
		$stylepath = $this->stylepath;
		$dir2 = $stylepath.$dir;
		
		$tplarray = array();
		$tpl_files = array();
		
		if (is_array($_POST['file']))
		{
			if ($_POST['edittpl'])
			{
				while(list($name,$content) = each($_POST['file']))
				{
					$content = str_replace("
","",$content); //dunno
					$admin->write_file($this->stylepath.$_GET['dir']."/".$name.".inc",$content);
					array_push($tplarray,$name);
				}			
			}
		}
		
		if ($_POST['mselect'])
		{
			if (is_array($_POST['templates']))
			{
				foreach($_POST['templates'] as $tpls)
				{
					array_push($tplarray,basename($tpls,".inc"));
				}
			}
		}

		$f = dir($dir2);
		
		while (false != ($file = $f->read()))
		{
			if ($file != "." && $file != ".." && !is_dir($file))
			{				
				if(strstr($file,".inc"))
				{
					array_push($tpl_files,$file);
				}
			}			
		}
		$f->close();

		reset($tpl_files);
		sort($tpl_files);
		
		foreach ($tpl_files as $val)
		{
			$list .= $val."|".basename($val,".inc");
			if ($val != end($tpl_files))
			{
				$list .= ",";
			}
		}

		$tpllist = $admin->form_select("templates[]",$list,"","","multiple size=\"30\"");
		foreach($tplarray as $name)
		{	
			$i++;
			$bg = $admin->get_bg($i);
			$name2 = $name;
			$file[$name] = htmlspecialchars(trim($admin->get_file($dir2."/".$name.".inc")));
			eval("\$tplloop .= \"".$tpl->gettemplate("styles_tplloop",0,$script[tplfolder])."\";");

			if ($name != end($tplarray))
			{
				$tplloop .= $admin->add_spacer("&nbsp;");
			}
		}

		$tplloop = ($tpllooop != "") ? $admin->add_row("","No Templates Specified"):$tplloop;
		eval("\$a .= \"".$tpl->gettemplate("medit",0,$script[tplfolder])."\";");
		
		return $a;
	}

	function add_replacement()
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database;
		global $evoLANG;
		$this->row_width = "25%";

		$form = $admin->form_start("","$_SERVER[PHP_SELF]?do=submit");
		$head = $evoLANG['addrep'];
		$stuff = $this->add_row("","<b>Replacer</b>","<b>Replaced Text</b>");

		$stuff .= $this->add_row("Name",$admin->form_input("thename"),$admin->form_input("value"));
		$end .= $admin->form_submit("addreplace",$evoLANG['button_go'],"3");
		eval("\$a .= \"".$tpl->gettemplate("table",0,$script[tplfolder])."\";");
		
		return $a;
	}

	function manage_replacement()
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database;
		global $evoLANG;
		$this->row_width = "30%";
		
		$head = $evoLANG['managerep'];
		$stuff = $this->add_row("<b>Replacer</b>","<b>Replaced Text</b>","Options");
		$sql = $udb->query("SELECT * FROM $database[replace] ORDER BY rid");
		while ($row = $udb->fetch_array($sql))
		{			
		$stuff .= $this->add_row("<b>".$row['name']."</b>",$row['value'],$admin->makelink($evoLANG[edit],$_SERVER['PHP_SELF']."?do=editrep&amp;id=$row[rid]"));
		}
		eval("\$a .= \"".$tpl->gettemplate("table",0,$script[tplfolder])."\";");
		
		return $a;
	}

	function export($id)
	{
		global $PHP_SELF,$udb,$admin,$tpl,$script,$database,$root;
		if (number_format($id) != '')
		{
			$row = $udb->query_once("SELECT * FROM $database[article_styles] WHERE id='$id'");
			while(list($head,$tail) = each($row) )
			{
				if (!number_format($head) && $head != "0"  && $head != "id")
				{
					$row2[$head] = $tail;
				}
			}
			
			$data = serialize($row2);
			$filedata .= $data."<<<<<<BREAK>>>>>";

			$a = opendir($this->stylepath.$row['tplfolder']);
			while ($file = readdir($a))
			{	
				if(($file != ".") && ($file != ".."))
				{
					if (is_dir($file))
					{

					} else {
						$filedata .= "******$file|||".$admin->get_file($this->stylepath.$row['tplfolder']."/".$file)."\n";
					}
				}
			}
			closedir($a);
			//$admin->write_file($maindir.basename($ddir).".tmpl",$files);
			//$content .= "<b>".basename($ddir)."</b> template pack wrote <br />";
			
			header("Content-Disposition: attachment; filename=styles_".$row['name'].".pack");
			header("Content-Type: text/octet-stream\n");
			header("Content-Length: ".strlen($filedata)."\n\n");
			echo $filedata;	
		}
	}

	function import()
	{
		global $_POST,$_FILES,$database,$udb,$admin;

		//check extension
		if ($admin->get_ext($_FILES['file']['name']) == "pack")
		{
			//now check if contains "styles"
			if ( preg_match("#styles_#",$_FILES['file']['name']) )
			{
				//styles_Default.pack
				$gfile = explode("_", substr(basename($_FILES['file']['name'],$admin->get_ext($_FILES['file']['name'])),0,-1) );
				$stylename = $gfile[1]."_".$this->dir_totalfile($this->stylepath);

				$data = $admin->get_file( $_FILES['file']['tmp_name'] ) ;
				$data2  = explode( "<<<<<<BREAK>>>>>",$data );
				$data3 = unserialize($data2[0]);
				

				while(list($name,$value) = each($data3) )
				{
					
					if ($name == "tplfolder") $value = $stylename;
					$sqlval .= $name."='".$value."',";
				}
				$sqlval = substr($sqlval,0,-1);
				//echo $stylename;
				
				//make dir & write files

				$udb->query("INSERT INTO  $database[article_styles] SET $sqlval");
				
				$maindir = $this->stylepath;
				$newname = $stylename;

				$get2 = explode("******",$data2[1]);
				unset($i);
				while ( $i < count($get2) )
				{
					$i++;
					@mkdir($maindir.$newname,0777);
					@chmod($maindir.$newname,0777);
					if (trim($get2[$i]) != "")
					{	
						$get3 = explode("|||",$get2[$i]);
						//$get3[0] //filename
						//$get3[1] //content
					
						$admin->write_file($maindir.$newname."/".$get3[0],$get3[1]);
						@chmod($maindir.$dfile[0]."/".$get3[0],0666);
						$c .= $maindir.$stylename."/".$get3[0]."<Br />";
					}
							
				}
				
				header("location: $_SERVER[PHP_SELF]");

			}
		}

		//print_r($_FILES);
		//print_r($_POST);

		exit;
	}

	function dir_totalfile($maindir='')
	{
		$a2 = opendir($maindir);
		while ($ddir= readdir($a2))
		{
			if(($ddir != ".") && ($ddir != ".."))
			{
				if (is_dir($maindir.$ddir))
				{
					$count++;
				}
			}
		}
		closedir($a2);

		return $count;
	}


}
/* ----------------------------------------------------*/
$sty = new Styles;
switch ($_GET['do'])
{
	/* ------------------------------ */
	case "addreplacement":
		$content = $admin->do_nav($evoLANG['styles_main']."|".$evoLANG['addrep'],"$PHP_SELF|nolink");
		$content .= $sty->add_replacement();
	break;
	/* ------------------------------ */
	case "managerep":
		$content = $admin->do_nav($evoLANG['styles_main']."|".$evoLANG['managerep'],"$PHP_SELF|nolink");
		$content .= $sty->manage_replacement();
	break;
	/* ------------------------------ */
	case "makedefault":
		$content .= $sty->makedefault($_GET['id']);
		header("Location: $PHP_SELF");
	break;
	/* ------------------------------ */
	case "add":
		$content = $admin->do_nav($evoLANG['styles_main']."|".$evoLANG['styles_add'],"$PHP_SELF|nolink");
		$content .= $sty->add();
	break;
	/* ------------------------------ */
	case "edit":
		$content = $admin->do_nav($evoLANG['styles_main']."|".$evoLANG['styles_edit'],"$PHP_SELF|nolink");
		$content .= $sty->edit(number_format($_GET['id']));
	break;
	/* ------------------------------ */
	case "multiple":
		$content = $admin->do_nav($evoLANG['styles_main']."|".$evoLANG['styles_edittpl'],"$PHP_SELF|nolink");
		$content .= $sty->medit($_GET['dir']);
	break;
	/* ------------------------------ */
	case "delete":
		if ($_GET['id'] != $sty->cannot_delete)
		{
			$row = $udb->query_once("SELECT tplfolder FROM $database[article_styles] WHERE id='".$_GET['id']."'");
			$admin->deldir($sty->stylepath.$row['tplfolder']);
			$udb->query("delete from $database[article_styles] WHERE id = '".$_GET['id']."'");

			header("location: ".$_SERVER['PHP_SELF']);
		}
		else
		{
			$content = $evoLANG['xdelete1ststyle'];
		}

	break;
	/* ------------------------------ */
	case "templates":
		$content = $admin->do_nav($evoLANG['styles_main']."|".$evoLANG['styles_edittpl'],"$PHP_SELF|nolink");
		$content .= $sty->templates($_GET['dir']);
	break;
	/* ------------------------------ */
	case "export":
		$content = $admin->do_nav($evoLANG['styles_main']."|".$evoLANG['export'],"$PHP_SELF|nolink");
		$content .= $sty->export($_GET['id']);
	break;
	/* ------------------------------ */
	case "import":
		$content = $admin->do_nav($evoLANG['styles_main']."|".$evoLANG['import'],"$PHP_SELF|nolink");
		$content .= $sty->import($_GET['id']);
	break;
	/* ------------------------------ */
	case "submit":
		if ($_POST['addreplace'])
		{
			$admin->form_check("thename","Name");
			//$admin->form_check("value","Value");

			$udb->query("INSERT INTO $database[replace] SET name='".$_POST['thename']."',value='".$_POST['value']."'");
			header("Location: $_SERVER[PHP_SELF]?do=managerep");
		}

		if ($_POST['editfile'])
		{				
			if (is_array($_POST['apply']))
			{
				foreach ($_POST['apply'] as $val)
				{
					$admin->write_file($val,$_POST['content']);
				}
				
				unset($content);
			}
			else
			{
				$admin->write_file($_POST['url'],$_POST['content']);
				unset($content);
			}
			$back = ($_SERVER['HTTP_REFERER'] != "") ? $_SERVER['HTTP_REFERER']:$_POST['back'];
			header("Location: $back");
		}

		if ($_POST['add'])
		{
			$sty->sql_add();
		}
	break;
	/* ------------------------------ */
	default:
		$content = $sty->main();
	/* ------------------------------ */
}

eval("echo(\"".$tpl->gettemplate("main",1)."\");");
?>