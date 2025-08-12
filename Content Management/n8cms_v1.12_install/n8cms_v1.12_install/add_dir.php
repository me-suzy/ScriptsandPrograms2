<?
session_start();
require ('_.php');
require ('functions.php');

//echo $lt_date."<BR>";
//echo "userid".$_SESSION[userid];
//admin headers, replace this with a better function
if (!$usr_lvl){
		echo "<h2>Access Denied!</h2>";
	 exit();
		}else {//echo "you user level is ".$usr_lvl;
			if	($usr_lvl < 1) {include ('notyet.php'); exit();}
			if	($usr_lvl ==1){editorpageheader();}
			if	($usr_lvl ==2){Adminpageheader();}
			if	($usr_lvl ==3){Mastpageheader();}
			if 	($usr_lvl ==4){dietypageheader();}
			 echo" <a class=nav_links href=index.php?dir=".$dir."&page_id=".$page_id." target=_new>Preview</a> |<br> ";
		}

$dir_name=$_POST['dir_name'];
$new_dir_query=" CREATE TABLE `".$dir_name."` (
  `page_id` int(6) unsigned NOT NULL auto_increment,
  `rec_crt` varchar(60) NOT NULL default '0000-00-00 00:00:00',
  `rec_edit` datetime NOT NULL default '0000-00-00 00:00:00',
  `auth_id` tinyint(4) NOT NULL default '0',
  `isactive` tinyint(1) NOT NULL default '0',
  `pg_title` tinytext NOT NULL,
  `hits` tinyint(4) NOT NULL default '0',
  `admin_lvl` tinyint(4) NOT NULL default '0',
  `content` text NOT NULL,
  `rec_expire` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`page_id`)
) TYPE=MyISAM COMMENT='n8R0x' AUTO_INCREMENT=100 ;
";
// strip spaces and replace with _
$query1="INSERT INTO `".$dir_name."` (`page_id`, `rec_crt`, `rec_edit`, `auth_id`, `isactive`, `pg_title`, `hits`, `admin_lvl`, `content`, `rec_expire`) VALUES (1, '".$lt_date."', '".$datetime."', ".$userid.", 0, 'index', 0, 4, 'this is the index page for ".$dir_name."', '0000-00-00');";
	if(!$dir_name){include('html/add_dir.html');}
		else{mysql_query($new_dir_query) or die(mysql_error());
		mysql_query($query1) or die(mysql_error());
		//echo $dir_name." added<br> <a href='add_dir.php'>Create another one</a><br>";
		echo "<script>document.location.replace('admin_funtions.php')</script><a href=admin_funtions.php>Admin Functions</a>";
		}
?>

