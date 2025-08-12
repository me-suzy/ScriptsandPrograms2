<?php session_start();

// Check if user is logged in
if($_SESSION['loggedin'] == 'yes')
   {
      // Check if the user is the Admin
      $username = strtolower($_SESSION['username']);
      if($username == 'admin')
         {
            // Include needed files
            include("./class.TemplatePower.inc.php");
            include("./config/config.php");

            // Create a new template object
            $tpl = new TemplatePower("./themes/$themes/tpl/admin/admin.tpl");

            //Create database connection
            mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
            mysql_select_db($sql['data']) or die("Unable to find DB");

            // Getting the content
            if (!$_GET['action'])
               {
                  $action = "main";
               }

            // Getting the right template for each page
            if(!strcmp($action, "main"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/main.tpl");
               }

            if(!strcmp($_GET['action'], "personal"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/options.tpl");
               }

            if(!strcmp($_GET['action'], "index"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/index.tpl");
               }
               
            if(!strcmp($_GET['action'], "system"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/system.tpl");
               }

            if(!strcmp($_GET['action'], "users"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/users.tpl");
               }

            if(!strcmp($_GET['action'], "adduser"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/adduser.tpl");
               }

            if(!strcmp($_GET['action'], "edituser"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/edituser.tpl");
               }

            if(!strcmp($_GET['action'], "backup"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/backup.tpl");
               }

            if(!strcmp($_GET['action'], "images"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/manage.tpl");
               }

            if(!strcmp($_GET['action'], "artist"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/artist.tpl");
               }

            if(!strcmp($_GET['action'], "editimage"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/edit.tpl");
               }

            if(!strcmp($_GET['action'], "editartist"))
               {
                  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/edit.tpl");
               }
               
            if(!strcmp($_GET['action'], "loans"))
               {
                  if($loanenable == "Yes")
                  	{
                  		$tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/loanenable.tpl");
                  	} else {
                  		$tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/loandisable.tpl");
                  	}
               }
               
            if(!strcmp($_GET['action'], "favs"))
               {
                  if($favenable == "Yes")
                  	{
                  		$tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/favsenable.tpl");
                  	} else {
                  		$tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/favsdisable.tpl");
                  	}
               }
               
            if(!strcmp($_GET['action'], "editloan"))
               {
               	  $tpl->assignInclude("adminpage", "./themes/$themes/tpl/admin/editloan.tpl");
               }

            // Prepare the template
            $tpl->prepare();

            if(!strcmp($_GET['action'], "personal"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $select = "SELECT * FROM pmc_user WHERE name = 'Admin'";
                  $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
                  $row = mysql_fetch_array($data);

                  // Query results and assign template values
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);
                  $tpl->assign("pmcuser", $row['realname']);
                  $tpl->assign("pmcmail", $row['email']);
                  $tpl->assign("errormsg", $error);
               }

            if(!strcmp($_GET['action'], "index"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  // Assign needed values
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);
                  
                  $tpl->assign("statsenable", $statsenable);
                  $tpl->assign("statstype", $statstype);
                  $tpl->assign("listtype", $listtype);
                  $tpl->assign("rownumber", $rownumber);
                  
                  if($statsenable == 'Yes') { $enableyes = 'checked'; } else { $enableyes = ''; }
                  if($statsenable == 'No') { $enableno = 'checked'; } else { $enableno = ''; }
                  
                  if($statstype == 'Short') { $stat_short = 'checked'; } else { $stat_short = ''; }
                  if($statstype == 'Full') { $stat_full = 'checked'; } else { $stat_full = ''; }
                  
                  if($listtype == 'Latest') { $list_latest = 'checked'; } else { $list_latest = ''; }
                  if($listtype == 'Fav') { $list_fav = 'checked'; } else { $list_fav = ''; }
                  
                  $tpl->assign("enableyes", $enableyes);
                  $tpl->assign("enableno", $enableno);
                  $tpl->assign("list_fav", $list_fav);
                  $tpl->assign("list_latest", $list_latest);
                  $tpl->assign("stat_short", $stat_short);
                  $tpl->assign("stat_full", $stat_full);                 

               }
               
            if(!strcmp($_GET['action'], "system"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  // Assign needed values
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);
                  $tpl->assign("installdate", $install);
                  $tpl->assign("host", $sql['host']);
                  $tpl->assign("username", $sql['user']);
                  $tpl->assign("password", $sql['pass']);
                  $tpl->assign("database", $sql['data']);
                  $tpl->assign("dateoption", $dateoption);
                  $tpl->assign("currency", $currency);
                  $tpl->assign("maxwidth", $imgwidth);
                  $tpl->assign("maxheight", $imgheight);
                  $tpl->assign("maxsize", $imgsize);
                  $tpl->assign("enablepdf", $pdfenable);
                  $tpl->assign("enableprint", $printenable);                  
                  $tpl->assign("siteurl", $siteurl);
                  $tpl->assign("sitetitle", $sitetitle);
                  $tpl->assign("enableloan", $loanenable);
                  $tpl->assign("rssenable", $rssenable);
                  $tpl->assign("favenable", $favenable);
                  $tpl->assign("language", $language);
                  $tpl->assign("paginate", $paginate);

                  if($pdfenable == 'Yes') { $pdfyes = 'checked'; } else { $pdfyes = ''; }
                  if($pdfenable == 'No') { $pdfno = 'checked'; } else { $pdfno = ''; }

                  if($printenable == 'Yes') { $printyes = 'checked'; } else { $printyes = ''; }
                  if($printenable == 'No') { $printno = 'checked'; } else { $printno = ''; }
                  
                  if($loanenable == 'Yes') { $loanyes = 'checked'; } else { $loanyes = ''; }
                  if($loanenable == 'No') { $loanno = 'checked'; } else { $loanno = ''; }
                  
                  if($rssenable == 'Yes') { $rssyes = 'checked'; } else { $rssyes = ''; }
                  if($rssenable == 'No') { $rssno = 'checked'; } else { $rssno = ''; }
                  
                  if($favenable == 'Yes') { $favyes = 'checked'; } else { $favyes = ''; }
                  if($favenable == 'No') { $favno = 'checked'; } else { $favno = ''; }

                  $tpl->assign("pdfyes", $pdfyes);
                  $tpl->assign("pdfno", $pdfno);
                  $tpl->assign("printyes", $printyes);
                  $tpl->assign("printno", $printno);
                  $tpl->assign("loanyes", $loanyes);
                  $tpl->assign("loanno", $loanno);
                  $tpl->assign("rssyes", $rssyes);
                  $tpl->assign("rssno", $rssno);
                  $tpl->assign("favyes", $favyes);
                  $tpl->assign("favno", $favno);                 

                  // Get the themes from theme directory and list them
                  if(!$handle = opendir("./themes"))
                     {
                        die("Can not open directory ./themes ");
                     }

                  while (false !== ($file = readdir($handle)))
                     {
                        $file_arr = explode(".",$file);

                        if($file_arr[1] == "skin")
                           {
                              $sys_con_skins_arr[$file_arr[0]] = $file_arr[0];
                              if($file_arr[0] == $themes) { $sel = ' selected'; } else { $sel = ''; }

                              $tpl->newBlock("admin_theme");
                              $tpl->assign("pmc_name", $file_arr[0]);
                              $tpl->assign("selected", $sel);
                           }
                     }
                  closedir($handle);

                  // Get the languages from the lang directory
                  if(!$langhandle = opendir("./lang"))
                     {
                        die("Can not open directory ./lang ");
                     }

                  while (false !== ($file = readdir($langhandle)))
                     {
                        $file_arr = explode(".",$file);

                        if($file_arr[1] == "lang")
                           {
                              $sys_con_skins_arr[$file_arr[0]] = $file_arr[0];
                              if($file_arr[0] == $language) { $sel = ' selected'; } else { $sel = ''; }

                              $tpl->newBlock("admin_lang");
                              $tpl->assign("pmc_name", $file_arr[0]);
                              $tpl->assign("selected", $sel);
                           }
                     }
                  closedir($langhandle);

               }

            if(!strcmp($_GET['action'], "users"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assignGlobal("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);

                  // Get users from database
                  $users="SELECT * FROM pmc_user WHERE name!='Admin' ORDER BY name";
                  $datas = mysql_db_query($sql['data'], $users) or die("Select Failed!");

                  while($row = mysql_fetch_array($datas))
                     {
                        $tpl->newBlock("admin_userlist");
                        $tpl->assign("usr_id", $row['ID']);
                        $tpl->assign("usr_name", $row['realname']);
                        $tpl->assign("usr_mail", $row['email']);
                        $tpl->assign("usr_user", $row['name']);
                        $tpl->assign("lang_edit", $lang_edit);
                        $tpl->assign("lang_delete", $lang_delete);
                     }
               }

            if(!strcmp($_GET['action'], "adduser"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);
                  $tpl->assign("errormsg", $error);
               }

            if(!strcmp($_GET['action'], "backup"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);

                  if(!$handle = opendir("./backup"))
                     {
                        die("Can not open directory ./backup ");
                     }

                  while (false !== ($file = readdir($handle)))
                     {
                        $file_arr = explode(".",$file);

                        if($file_arr[1] == "sql")
                           {
                              $file_split = explode("_",$file_arr[0]);

                              $tpl->newBlock("backup_list");
                              $tpl->assign("backup_name", "PhpMyComic Backup");
                              $tpl->assign("backup_date", $file_split[1]);
                              $tpl->assign("backup_options", "<a href=\"function.php?cmd=load_backup&file=$file\" class=\"listlink\">$lang_load</a> - <a href=\"function.php?cmd=delbackup&file=$file\" class=\"listlink\">$lang_delete</a>");
                           }
                     }
                  closedir($handle);
               }

            if(!strcmp($_GET['action'], "images"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);

                  if ($dir = @opendir ("./image"))
                     {
                        while (($file =readdir ($dir)) !== false)
                           {
                              if($file != ".." && $file != "." && $file != "noimage.jpg")
                                 {
                                    $filelist [] = $file;

                                    $size = getimagesize("./image/$file");

                                    $filesize = filesize("./image/$file");
                                    $sizes = Array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
                                    $ext = $sizes[0];

                                    for ($i=1; (($i < count($sizes)) && ($filesize >= 1024)); $i++)
                                       {
                                          $filesize = $filesize / 1024;
                                          $ext  = $sizes[$i];
                                       }

                                    $imagesize = round($filesize, 2);

                                    $tpl->newBlock("file_list");
                                    $tpl->assign("file_name", $file);
                                    $tpl->assign("file_size", "$size[0] x $size[1]");
                                    $tpl->assign("file_file", "$imagesize $ext");
                                    $tpl->assign("comic_width", $size[0]);
                                    $tpl->assign("comic_height", $size[1]);
                                    $tpl->assign("options", "<a href=\"admin.php?action=editimage&file=$file\" class=\"listlink\">$lang_edit</a> - <a href=\"function.php?cmd=delimage&file=$file\" class=\"listlink\">$lang_delete</a>");
                                 }
                           }
                        closedir ($dir);
                     }
               }

            if(!strcmp($_GET['action'], "artist"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);
                  $tpl->assign("type", ucfirst($_GET['type']));

                  $select = "SELECT * FROM pmc_artist WHERE type = '". $_GET['type'] ."' ORDER BY name";
                  $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

                  while ($row = mysql_fetch_array($data))
                     {
                        // Set the values to template variables
                        $tpl->newBlock("artist_list");
                        $tpl->assign("pmc_name", $row['name']);
                        $tpl->assign("pmc_type", $row['type']);
                        $tpl->assign("pmc_uid", $row['uid']);
                        $tpl->assign("options", "<a href='admin.php?action=editartist&uid=".$row['uid']."' class='listlink'>$lang_edit</a> - <a href='function.php?cmd=delete&del=delart&a=".$_GET['type']."&uid=".$row['uid']."' class='listlink'>$lang_delete</a>");
                     }
               }
            
            if(!strcmp($_GET['action'], "editloan"))
               {
               	  include("./lang/$language/admin.lang.php");
               	  
               	  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);
                  
                  $select = "SELECT * FROM pmc_loan WHERE itemid = '". $_GET['id'] ."'";
                  $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");                  
                  $row = mysql_fetch_array($data);
                  
                  $uid = $row['itemid'];
                  $comicid = $row['comicid'];
                  $name = $row['name'];
                  $date = $row['date'];
                  $due = $row['due'];
                  $notes = $row['notes'];
                  
                  $tpl->assign("loan_uid", $uid);
                  $tpl->assign("loan_name", $name);
                  $tpl->assign("loan_date", $date);
                  $tpl->assign("loan_due", $due);
                  $tpl->assign("loan_notes", $notes);
                  $tpl->assign("get_form", 'function.php?cmd=editloan');
                  
                  $comic = "SELECT * FROM pmc_comic WHERE uid = '$comicid'";
			      $comicdata = mysql_db_query($sql['data'], $comic) or die("Select Failed!");

				  // Query results
				  $getrow = mysql_fetch_array($comicdata);
  				  $story = $getrow['story'];
  				  $name = $getrow['title'];
  				  $issue = $getrow['issue'];
  				  $issueltr = $getrow['issueltr'];
  
  				  // Get the series name
  				  $getname = "SELECT * FROM pmc_artist WHERE uid = '$name'";
  				  $namedata = mysql_db_query($sql['data'], $getname) or die("Select Failed!");
  				  $namerow = mysql_fetch_array($namedata);
  				  $name_uid = $namerow['name'];
  						
  				  $tpl->assign("comic_name", $name_uid);
  				  $tpl->assign("comic_issue", $issue);
  				  $tpl->assign("comic_issueltr", $issueltr);
  				  $tpl->assign("comic_story", $story);
               }   
            
            if(!strcmp($_GET['action'], "loans"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);

                  $select = "SELECT * FROM pmc_loan ORDER BY date";
                  $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

                  while ($row = mysql_fetch_array($data))
                     {
                     	$uid = $row['itemid'];
                     	$id = $row['comicid'];
                     	
                        // Set the values to template variables
                        $tpl->newBlock("loanlist");
                        $tpl->assign("loan_name", $row['name']);
                        $tpl->assign("loan_date", $row['date']);
                        $tpl->assign("loan_notes", $row['notes']);
                        $tpl->assign("options", "<a href=\"admin.php?action=editloan&id=$uid\" class=\"listlink\">$lang_edit</a> - <a href=\"function.php?cmd=loandelete&id=$uid\" class=\"listlink\">$lang_delete</a>");
                        
                        if($row['due'] == "0000-00-00")
                        	{
                        		$tpl->assign("loan_due", $lang_no_duedate);
                        	} else {
                        		$tpl->assign("loan_due", $row['due']);
                        	}
                        
                        // Getting Comic details
						$comic = "SELECT * FROM pmc_comic WHERE uid = '$id'";
						$comicdata = mysql_db_query($sql['data'], $comic) or die("Select Failed!");

						// Query results
						$getrow = mysql_fetch_array($comicdata);
  						$uid = $getrow['uid'];
  						$name = $getrow['title'];
  						$issue = $getrow['issue'];
  						$issueltr = $getrow['issueltr'];
  
  						// Get the series name
  						$getname = "SELECT * FROM pmc_artist WHERE uid = '$name'";
  						$namedata = mysql_db_query($sql['data'], $getname) or die("Select Failed!");
  						$namerow = mysql_fetch_array($namedata);
  						$name_uid = $namerow['name'];
  						
  						$tpl->assign("loan_comic", $name_uid);
  						$tpl->assign("loan_issue", $issue);
  						$tpl->assign("loan_issueltr", $issueltr);
  						
                     }
               }

            if(!strcmp($_GET['action'], "favs"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);

                  $select = "SELECT * FROM pmc_comic WHERE fav = 'yes' ORDER BY issue, issueltr";
                  $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

                  while ($row = mysql_fetch_array($data))
                     {
                     	$uid = $row['uid'];
                     	$id = $row['comicid'];
                     	$story = stripslashes($row['story']);
                     	
                        // Set the values to template variables
                        $tpl->newBlock("favlist");
                        $tpl->assign("fav_date", $row['date']);
                        $tpl->assign("fav_story", $story);
                        $tpl->assign("options", "<a href=\"function.php?cmd=favdelete&id=$uid\" class=\"listlink\">$lang_remove</a>");
                        
                        // Getting Comic details
						$comic = "SELECT * FROM pmc_comic WHERE uid = '$uid'";
						$comicdata = mysql_db_query($sql['data'], $comic) or die("Select Failed!");

						// Query results
						$getrow = mysql_fetch_array($comicdata);
  						$uid = $getrow['uid'];
  						$name = $getrow['title'];
  						$issue = $getrow['issue'];
  						$issueltr = $getrow['issueltr'];
  
  						// Get the series name
  						$getname = "SELECT * FROM pmc_artist WHERE uid = '$name'";
  						$namedata = mysql_db_query($sql['data'], $getname) or die("Select Failed!");
  						$namerow = mysql_fetch_array($namedata);
  						$name_uid = $namerow['name'];
  						
  						$tpl->assign("fav_comic", $name_uid);
  						$tpl->assign("fav_issue", $issue);
  						$tpl->assign("fav_issueltr", $issueltr);
  						
                     }
               }
               
            if(!strcmp($_GET['action'], "edituser"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("errormsg", $error);

                  $users = "SELECT * FROM pmc_user WHERE ID = '". $_GET['id'] ."'";
                  $datas = mysql_db_query($sql['data'], $users) or die("Select Failed!");
                  $row = mysql_fetch_array($datas);

                  // Query result and assign template values
                  $tpl->assign("user_id", $row['ID']);
                  $tpl->assign("user_name", $row['realname']);
                  $tpl->assign("user_uname", $row['name']);
                  $tpl->assign("user_mail", $row['email']);
               }

            if(!strcmp($action, "main"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  // Include skin file and print theme values and system values
                  include("themes/$themes.skin.php");
                  include("lang/$language.lang.php");

                  $select = "SELECT * FROM pmc_user WHERE name = 'Admin'";
                  $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
                  $row = mysql_fetch_array($data);

                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);
                  $tpl->assign("admuser", $row['realname']);
                  $tpl->assign("skinver", $skinversion);
                  $tpl->assign("author", $themeauthor);
                  $tpl->assign("authmail", $authormail);
                  $tpl->assign("install", $install);
                  $tpl->assign("themename", $themename);
                  $tpl->assign("langname", $langname);
                  $tpl->assign("langauthor", $langauthor);
                  $tpl->assign("langmail", $langmail);
               }

            if(!strcmp($_GET['action'], "editimage"))
               {
                  include("./lang/$language/admin.lang.php");
                  
                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("new_name", $_GET['file']);
                  $tpl->assign("old_name", $_GET['file']);
                  $tpl->assign("form", "function.php?cmd=editimage");
                  $tpl->assign("title", "Edit Imagename");
               }

            if(!strcmp($_GET['action'], "editartist"))
               {
				  include("./lang/$language/admin.lang.php");

                  $tpl->assignGlobal("theme", $themes);
                  $tpl->assign("imgfolder", "themes/$themes/img");
                  $tpl->assign("version", $version);
                  $tpl->assign("form", "function.php?cmd=manage");
                  $tpl->assign("title", "Edit Artistname");

                  $select = "SELECT * FROM pmc_artist WHERE uid = '". $_GET['uid'] ."'";
                  $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
                  $row = mysql_fetch_array($data);

                  if($row['name'] == 'One Shot')
                  {
                  header("Location: error.php?error=17");
                  exit;
                  } else {

                  $tpl->assign("new_name", $row['name']);
                  $tpl->assign("old_name", $row['uid']);
                  $tpl->assign("old_type", $row['type']);
                  }
               }

            // Print the result
            $tpl->printToScreen();

         } else {

            // Not logged in as ADMIN
            header("Location: error.php?error=13");
            exit;
         }

      } else {

         // Login failed
         header("Location: error.php?error=14");
         exit;
   }

?>