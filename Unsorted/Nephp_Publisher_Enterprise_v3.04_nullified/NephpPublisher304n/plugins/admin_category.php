<?
///////////////////////////////////////////////////////////////////////////////
//      =   =       ====  =   = ====                                         //
//      =   =       =   = =   = =   =                                        //
//      ==  =  ===  =   = =   = =   =                                        //
//      = = = =   = ====  ===== ====                                         //
//      =  == ===== =     =   = =                                            //
//      =   = =     =     =   = =                                            //
//      =   =  ==== =     =   = =                                            //
//      ------------------------------------------------------               //
//      ====        =     ===     =         =                                //
//      =   =       =       =               =                                //
//      =   = =   = ====    =   ===    ==== ====   ===   ===                 //
//      ====  =   = =   =   =     =   =     =   = =   = =   =                //
//      =     =   = =   =   =     =    ===  =   = ===== =                    //
//      =     =   = =   =   =     =       = =   = =     =                    //
//      =      ===  ====  ===== ===== ====  =   =  ==== =                    //
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
// Program Name         : Nephp Publisher Enterprise                         //
// Release Version      : 3.04                                               //
// Program Author       : Kenny Ngo     (CTO of Nelogic Technologies.)       //
// Program Author       : Ewdision Then (CEO of Nelogic Technologies.)       //
// Retail Price         : $499.00 United States Dollars                      //
// WebForum Price       : $000.00 Always 100% Free                           //
// ForumRu Price        : $000.00 Always 100% Free                           //
// xCGI Price           : $000.00 Always 100% Free                           //
// Supplied by          : Scoons [WTN]                                       //
// Nullified by         : CyKuH [WTN]                                        //
// Distribution         : via WebForum, ForumRU and associated file dumps    //
///////////////////////////////////////////////////////////////////////////////
function admin_category()
{

        global $gbl_env,$gbl_mnv,$gbl_microtime,$_cfig;

	$tpl_template_url=$_cfig[url_tpl];

	$jscript_counter=0;

	if($gbl_env["opt"]=='')
	{
		print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_category.html",0));
	}
	elseif($gbl_env["opt"]=='list')
        {
                $calliberation=array();
		$result = mysql_query("SELECT `nnet_cid`,`nnet_name` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_parent`='0' ORDER BY `nnet_cid` ASC;");
                while ($line = mysql_fetch_row($result))
                {
			$jscript_output.="Tree[$jscript_counter]  = \"$line[0]|0|$line[1]|admin.php?mod=category&id=$line[0]&opt=edit\";\n";
			$line[1]=preg_replace("/ /","&nbsp;",$line[1]);
			$cnode=0;
                	$sub_result = mysql_query("SELECT `nnet_cid`,`nnet_name`,`nnet_child`,`nnet_parent` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_parent`='$line[0]' ORDER BY `nnet_cid` ASC;");
                	while ($sub_line = mysql_fetch_row($sub_result))
                	{
				$jscript_counter++;
				$sub_line[1]=preg_replace("/ /","&nbsp;",$sub_line[1]);
				$jscript_output.="Tree[$jscript_counter]  = \"$sub_line[0]|$sub_line[2]|$sub_line[1]|admin.php?mod=category&id=$sub_line[0]&opt=edit\";\n";
				if($gbl_env["expand"]==1)
				{
					$calliberation[].=$sub_line[2];
					$calliberation[].=$line[0];
				}
				$cnode=1;
                	}
               		mysql_free_result($sub_result);
			$jscript_counter++;
                }
                mysql_free_result($result);
		if($gbl_env["expand"]==1)
		{
			$calliberation=array_unique($calliberation);
			foreach ($calliberation as $values)
			{
				$open_node.="oc($values,0);\n";
			}
		}
                print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_category_list.html",0));
        }
        elseif($gbl_env["opt"]=='add')
        {
                if($gbl_env["act"]=='')
		{
			if ($dir = @opendir("$_cfig[dir_tpl]/html/customs/categories"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tpl_categories.="<option value=\"$file\">$file</option>\n";
					}
				}  
				closedir($dir);
			}
			$file='';
			if ($dir = @opendir("$_cfig[dir_tpl]/html/customs/features"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tpl_features.="<option value=\"$file\">$file</option>\n";
					}
				}  
				closedir($dir);
			}
			$file='';
			if ($dir = @opendir("$_cfig[dir_tpl]/html/customs/listings"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tpl_listings.="<option value=\"$file\">$file</option>\n";
					}
				}  
				closedir($dir);
			}
			$file='';
			if ($dir = @opendir("$_cfig[dir_tpl]/icons"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tpl_icons.="<option value=\"$file\">$file</option>\n";
					}
				}  
				closedir($dir);
			}

			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_category_add_root.html",0));
		}
		else
		{
			if($gbl_mnv["nnet_name"]=='')
			{
				_err("Please specify your category name");
			}
			$result=mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_category` (`nnet_name`,`nnet_desc`,`nnet_icon`,`nnet_categories`,`nnet_listings`,`nnet_features`,`nnet_flimit`,`nnet_fmax`,`nnet_date`) VALUES('".$gbl_mnv["nnet_name"]."','".$gbl_mnv["nnet_desc"]."','".$gbl_mnv["icons"]."','".$gbl_mnv["tpl_template"]."','".$gbl_mnv["tpl_listing"]."','".$gbl_mnv["tpl_features"]."','".$gbl_mnv["nnet_flimit"]."','".$gbl_mnv["nnet_fmax"]."','".time()."');")
				 or die("Error #".mysql_errno().": ".mysql_error());
			mysql_free_result($result);
			print "Category: <b>".$gbl_env["nnet_name"]."</b> is added sucessfully.&nbsp;&nbsp; <a href=\"admin.php?mod=category\" target=\"main\">Click here</a> to continue.";
		}
        }
	elseif($gbl_env["opt"]=='edit')
        {
                if($gbl_env["act"]=='')
		{
			if($gbl_env["id"]=='') { _err("Please specify your id."); }
			
			$id=$gbl_env["id"];
			$result = mysql_query("SELECT * FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='".$gbl_env["id"]."' LIMIT 1;")
				  or die("Error #".mysql_errno().": ".mysql_error());
        	       	extract(mysql_fetch_array($result, MYSQL_ASSOC));
			mysql_free_result($result);
			
			if ($dir = @opendir("$_cfig[dir_tpl]/html/customs/categories"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tmp=cif("$nnet_categories==$file","selected","");
						$tpl_categories.="<option value=\"$file\" $tmp>$file</option>\n";
					}
				}  
				closedir($dir);
			}
			$file='';
			if ($dir = @opendir("$_cfig[dir_tpl]/html/customs/features"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tmp=cif("$nnet_features==$file","selected","");
						$tpl_features.="<option value=\"$file\" $tmp>$file</option>\n";
					}
				}  
				closedir($dir);
			}
			$file='';
			if ($dir = @opendir("$_cfig[dir_tpl]/html/customs/listings"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tmp=cif("$nnet_listings==$file","selected","");
						$tpl_listings.="<option value=\"$file\" $tmp>$file</option>\n";
					}
				}  
				closedir($dir);
			}
			$file='';
			if ($dir = @opendir("$_cfig[dir_tpl]/icons"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tmp=cif("$nnet_icon==$file","selected","");
						$tpl_icons.="<option value=\"$file\" $tmp>$file</option>\n";
					}
				}  
				closedir($dir);
			}

			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_category_edit.html",0));
		}
		else
		{
			if($gbl_mnv["nnet_name"]=='')
			{
				_err("Please specify your category name");
			}
			if($gbl_mnv["id"]=='')
			{
				_err("Please specify your id");
			}
			$result=mysql_query("UPDATE `$_cfig[sql_db]`.`nnet_category` SET `nnet_name`='".$gbl_mnv["nnet_name"]."',`nnet_desc`='".$gbl_mnv["nnet_desc"]."',`nnet_icon`='".$gbl_mnv["icons"]."',`nnet_categories`='".$gbl_mnv["tpl_template"]."',`nnet_listings`='".$gbl_mnv["tpl_listing"]."',`nnet_features`='".$gbl_mnv["tpl_features"]."',`nnet_flimit`='".$gbl_mnv["nnet_flimit"]."',`nnet_fmax`='".$gbl_mnv["nnet_fmax"]."' WHERE `nnet_cid`='".$gbl_env["id"]."' LIMIT 1;")
				 or die("Error #".mysql_errno().": ".mysql_error());
			mysql_free_result($result);
			print "Category: <b>".$gbl_env["nnet_name"]."</b> is updated sucessfully.&nbsp;&nbsp; <a href=\"admin.php?mod=category\" target=\"main\">Click here</a> to continue.";
		}
        }
        elseif($gbl_env["opt"]=='addsub')
        {
                $id=$gbl_env["id"];

                if($gbl_env["act"]=='')
                {
                        if ($dir = @opendir("$_cfig[dir_tpl]/html/customs/categories"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tpl_categories.="<option value=\"$file\">$file</option>\n";
					}
				}  
				closedir($dir);
			}
			$file='';
			if ($dir = @opendir("$_cfig[dir_tpl]/html/customs/features"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tpl_features.="<option value=\"$file\">$file</option>\n";
					}
				}  
				closedir($dir);
			}
			$file='';
			if ($dir = @opendir("$_cfig[dir_tpl]/html/customs/listings"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tpl_listings.="<option value=\"$file\">$file</option>\n";
					}
				}  
				closedir($dir);
			}
			$file='';
			if ($dir = @opendir("$_cfig[dir_tpl]/icons"))
			{
  				while (($file = readdir($dir)) !== false)
				{
					if(strlen($file)>2)
					{
						$tpl_icons.="<option value=\"$file\">$file</option>\n";
					}
				}  
				closedir($dir);
			}

			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_category_add_sub.html",0));
                }
                else
                {
                        if($gbl_env["id"]=='')
			{
				_err("You need to specify parent ID.");
			}

			if($gbl_mnv["nnet_name"]=='')
			{
				_err("Please specify your category name");
			}

			$result = mysql_query("SELECT `nnet_nav`,`nnet_parent` FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='".$gbl_env["id"]."' LIMIT 1;")
				  or die("Error #".mysql_errno().": ".mysql_error());
        	       	$data=mysql_fetch_row($result);
			mysql_free_result($result);
	
			if($data[0]!=''){$nav_info = explode("|",$data[0]);}
			
			if($data[1]<1)  { $nav_parents=$gbl_env["id"];}
			else            { $nav_parents=$data[1];      }

			$nav_info[]=$gbl_env["id"];
			$nav_txt=implode("|",$nav_info);

			$result=mysql_query("INSERT INTO `$_cfig[sql_db]`.`nnet_category` (`nnet_name`,`nnet_desc`,`nnet_icon`,`nnet_categories`,`nnet_listings`,`nnet_features`,`nnet_child`,`nnet_parent`,`nnet_flimit`,`nnet_fmax`,`nnet_nav`,`nnet_date`) VALUES('".$gbl_mnv["nnet_name"]."','".$gbl_mnv["nnet_desc"]."','".$gbl_mnv["icons"]."','".$gbl_mnv["tpl_template"]."','".$gbl_mnv["tpl_listing"]."','".$gbl_mnv["tpl_features"]."','".$gbl_env["id"]."','$nav_parents','".$gbl_env["nnet_flimit"]."','".$gbl_env["nnet_fmax"]."','$nav_txt','".time()."');")
				 or die("Error #".mysql_errno().": ".mysql_error());
			mysql_free_result($result);
			print "Sub-category: <b>".$gbl_env["nnet_name"]."</b> is added sucessfully.&nbsp;&nbsp; <a href=\"admin.php?mod=category\" target=\"main\">Click here</a> to continue.";
                }
        }
	elseif($gbl_env["opt"]=='tpl_edit')
	{
		if($gbl_env["act"]!='proceed')
		{
			if($gbl_env["tpl_type"]=='')
			{
				_err("Template Type is missing.");
			}
			$type=$gbl_env["tpl_type"];
			$filename=$gbl_env["file"];
			if($filename!='')
			{
				$tpl_contents=_html("$_cfig[dir_skins]/$_cfig[template]/html/customs/$type/$filename",1);
			}
			print preg_replace("/{%(\w+)%}/ee", "$\\1",_html("$_cfig[dir_skins]/$_cfig[template]/html/admin_category_tpl.html",0));
		}
		else
		{
			$gbl_env["tpl_contents"]=stripslashes($gbl_env["tpl_contents"]);
			if($gbl_env["tpl_filename"]=='')
			{
				_err("Please specify filename");
			}
			if($gbl_env["type"]=='')
			{
				_err("Please specify template type");
			}
			$out=fopen ("$_cfig[dir_skins]/$_cfig[template]/html/customs/".$gbl_env["type"]."/".$gbl_env["tpl_filename"], "w");
			if ($out)
			{
				fwrite ($out,$gbl_env["tpl_contents"]);
				fclose ($out);
			}
			else
			{
				_err("unabled to update the file '$filename'.");
			}
			print "File ".$gbl_env["tpl_filename"]." is saved sucessfully.<br><br><p align=\"center\"><input type=button name=\"\" value=\"Close Windows\" Onclick=\"window.opener.location='admin.php?mod=category&opt=add';window.close();\">";
		}
	}
	elseif($gbl_env["opt"]=='tpl_del')
	{
		if($gbl_env["file"]=='')
		{
			_err("Please specify filename");
		}
		if($gbl_env["tpl_type"]=='')
		{
			_err("Please specify filename");
		}
		if(file_exists("$_cfig[dir_skins]/$_cfig[template]/html/customs/".$gbl_env["tpl_type"]."/".$gbl_env["file"]))
		{
			unlink ("$_cfig[dir_skins]/$_cfig[template]/html/customs/".$gbl_env["tpl_type"]."/".$gbl_env["file"]);
		}
		print "File <b>".$gbl_env["file"]."</b> is deleted sucessfully.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<p align=\"center\"><input type=button name=\"\" value=\"Click here to continue\" Onclick=\"location='admin.php?mod=category&opt=add';\">";
	}
	elseif($gbl_env["opt"]=='drop')
	{
		$result=mysql_query("SELECT COUNT(*) FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_child`='".$gbl_env["id"]."';")
				 or die("Error #".mysql_errno().": ".mysql_error());
		$search_found=mysql_fetch_row($result);
		mysql_free_result($result);
		
		if($search_found[0]>0)
		{
			_err("Not an empty category. Please delete its sub-category(ies).");
		}

		$result=mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_category` WHERE `nnet_cid`='".$gbl_env["id"]."' LIMIT 1;")
				 or die("Error #".mysql_errno().": ".mysql_error());
		mysql_free_result($result);
		
		$result=mysql_query("SELECT `nnet_aid` FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_cid`='".$gbl_env["id"]."'")
			or die("Error #".mysql_errno().": ".mysql_error());
		while($data=mysql_fetch_row($result))
		{
			if(file_exists("$_cfig[dir_upload]/thumb_$data[0].jpg"))
			{
				unlink(file_exists("$_cfig[dir_upload]/thumb_$data[0].jpg"));
			}
			if(file_exists("$_cfig[dir_upload]/thumb_$data[0].gif"))
			{
				unlink(file_exists("$_cfig[dir_upload]/thumb_$data[0].gif"));
			}
			if(file_exists("$_cfig[dir_upload]/full_$data[0].gif"))
			{
				unlink(file_exists("$_cfig[dir_upload]/full_$data[0].gif"));
			}
			if(file_exists("$_cfig[dir_upload]/full_$data[0].jpg"))
			{
				unlink(file_exists("$_cfig[dir_upload]/full_$data[0].jpg"));
			}
	
			if($_cfig[static_pages]==1)
	        	{
				if(file_exists("$_cfig[dir_static]/article$data[0].html"))
				{
					unlink("$_cfig[dir_static]/article$data[0].html");
				}
			}
		}
		mysql_free_result($result);

		// DELETE SPECIFIED ARTICLE
		mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_articles` WHERE `nnet_cid`='{$gbl_env["id"]}' LIMIT 1;")
		or die("Error #".mysql_errno().": ".mysql_error());

		// CLEAN POLL BELONG TO THIS ARTICLE
		mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_polls` WHERE `nnet_cid`='{$gbl_env["id"]}';")
		or die("Error #".mysql_errno().": ".mysql_error());
	
		// CLEAN REVIEWS THAT BELONG TO THIS ONE
		mysql_query("DELETE FROM `$_cfig[sql_db]`.`nnet_reviews` WHERE `nnet_cid`='{$gbl_env["id"]}';")
		or die("Error #".mysql_errno().": ".mysql_error());


		print "This category is deleted sucessfully.&nbsp;&nbsp; <a href=\"admin.php?mod=category\" target=\"main\">Click here</a> to continue.";
	}
}
?>