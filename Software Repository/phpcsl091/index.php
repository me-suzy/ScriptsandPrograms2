<?
/*
* Project:      PHP-CSL (PHP Code Snippet Library)
* Version:      0.9.1
* Date:         2005/08/05 (y/m/d)
* Author:       Stuart Cochrane
* URL:          http://www.php-csl.com
* Sourceforge:  http://www.sourceforge.net/projects/php-csl/
*
* Read: reame.txt, install.txt, license.txt
*
*
*
*
*
*/


session_start();
include 'config.php';
include 'functions.php';

$current_library = str_replace("/","", $snippet_library);
$current_library = str_replace("_"," ", $current_library);

?>

<html>
<head>
<title><?=$site_name?></title>
<meta name="keywords" content="php-csl, code snippet library">
<meta name="description" content="PHP Code Snippet Library stores all your code for you for easy access, runs on any PHP platform and does not require a database... easy to install and full of features.">
<META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<link rel="stylesheet" href="<?=$snippet_theme?>/phpcsl.css" type="text/css">


<!-- head -->
<table width="100%" cellpadding="0" cellspacing="0" class="rowpic" border="0">
	<tr>
  		<td class="top">&nbsp;&nbsp;<a href="<?=$site_url?>" class="head" title="<?=$site_name?>"><?=$site_name?></a></td>
 	</tr>
</table>
<!-- /head -->

<br>

<!-- main table -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
 <tr valign="top">
 
 <!-- left column -->
  <td width="200" class="col" valign="top">
  
	  <table class="rowpic" width="200" cellpadding="0" cellspacing="0">
        <tr>
		<td>
			<table class="option" cellpadding="0" cellspacing="0"><tr><td width="28">&nbsp;<img src="<?=$snippet_theme?>/img/lib_icon.gif" alt="+"></td><td><?=$current_library?></td></tr></table>
		</td></tr>
		</table>
		<table class="table1" width="200" cellpadding="2">
         <tr>
          <td><? include 'menu.php'; ?></td>
		 </tr>
		</table>

<?
if(($hide_options == 0) || (isset($_SESSION['phpcsl']) || isset($_GET['show']))) {
?>		
	<!-- snippet options -->		
		<table class="rowpic" width="200" cellpadding="0" cellspacing="0">
         <tr>
		  <td>
		  <table class="option" cellpadding="0" cellspacing="0"><tr><td width="28">&nbsp;<img src="<?=$snippet_theme?>/img/code_icon.gif" alt="+"></td><td>Snippet Options</td></tr></table>
		  </td></tr>
		</table>
		<table class="table1" width="200" cellpadding="2">
		<?
		if($hide_options == 0 || isset($_SESSION['phpcsl'])) {
		?>
		<tr>
			<td>
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=snips&act=add">&nbsp;Add new Snippet</a>
			<?
			if(isset($_GET['show'])) {
			?>
			<br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=snips&act=edit&snip=<?=$_GET['show']?>&cats=<?=$_GET['cat_select']?>">&nbsp;Edit this Snippet</a><br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=snips&act=delete&snips_delete=<?=$_GET['show']?>&cats=<?=$_GET['cat_select']?>">&nbsp;Delete this Snippet</a><br />
			<? } ?>
		<?
		}
			if(isset($_GET['show'])) {
		?>
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a target="_blank" href="printer_friendly.php?cat_select=<?=$_GET['cat_select']?>&show=<?=$_GET['show']?>">&nbsp;Printer Friendly</a>
			<?
			}
			?>
			</td></tr>
		</table>
	<!-- /snippet options -->
<?
}
?>	
	
<?
if($hide_options == 0 || isset($_SESSION['phpcsl'])) {
?>		
	<!-- category options -->
	<table class="rowpic" width="200" cellpadding="0" cellspacing="0">
         <tr>
		   <td>
		  <table class="option" cellpadding="0" cellspacing="0"><tr><td width="28">&nbsp;<img src="<?=$snippet_theme?>/img/cats_icon.gif" alt="+"></td><td>Category Options</td></tr></table> 
		   </td>
		  </tr>
		</table>
		<table class="table1" width="200" cellpadding="2">
		<tr>
			<td>
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=cats&act=add">&nbsp;Add new Category</a><br />		
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=cats&act=edit">&nbsp;Rename a Category</a><br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=cats&act=delete">&nbsp;Delete a Category</a>
			<?
			if(isset($_GET['cat_select'])) {
			?>
			<br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=cats&act=edit&cats=<?=$_GET['cat_select']?>">&nbsp;Rename this Category</a><br />
			
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=cats&act=delete&cats_delete=<?=$_GET['cat_select']?>">&nbsp;Delete this Category</a>
			<?
			}
			?>
			</td></tr>
	    </table>
	<!-- /category options -->
<?
}
?>		
	<!-- Library options -->
	<table class="rowpic" width="200" cellpadding="0" cellspacing="0">
         <tr>
		  <td>
		  	<table class="option" cellpadding="0" cellspacing="0"><tr><td width="28">&nbsp;<img src="<?=$snippet_theme?>/img/lib_icon.gif" alt="+"></td><td>Library Options</td></tr></table> 
		  </td>
		 </tr>
		</table>
		<table class="table1" width="200" cellpadding="2">
		<tr>
			<td>
<?
if($hide_options == 0 || isset($_SESSION['phpcsl'])) {
?>	
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=lib&act=add">&nbsp;Add new Library</a><br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=lib&act=edit">&nbsp;Rename a Library</a><br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=lib&act=delete">&nbsp;Delete a Library</a><br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=lib&act=change">&nbsp;Change default Library</a><br />
<?
}
?>
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=lib&act=session">&nbsp;View Other Library</a>
			</td></tr>
	    </table>
	<!-- /Library options -->
		
	
	<!-- General options -->
	<table class="rowpic" width="200" cellpadding="0" cellspacing="0">
         <tr>
		   <td>
		  	<table class="option" cellpadding="0" cellspacing="0"><tr><td width="28">&nbsp;<img src="<?=$snippet_theme?>/img/options.gif" alt="+"></td><td>General Options</td></tr></table>
		   </td>
		 </tr>
		</table>
		<table class="table1" width="200" cellpadding="2">
		<tr>
			<td>		
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php">&nbsp;Library Home</a><br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?op=credits">&nbsp;PHP-CSL Credits</a><br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?license=1">&nbsp;PHP-CSL License</a><br />
			<img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
			<a href="index.php?resources=1">&nbsp;Resource Links</a>
	<?
	if(!session_is_registered('phpcsl')) {
	?>
	<br />
	         <img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
	         <a href="index.php?login=y">&nbsp;Log in</a><br />
	<?
	} else {
	?>
	<br />
	         <img src="<?=$snippet_theme?>/img/link.gif" alt="+">&nbsp;
	         <a href="index.php?logout=y">&nbsp;Log out</a>
	<?
	}   
	?>
			</td></tr>
	    </table>
	<!-- /General options -->
		
</td>
 <!-- /left column -->
   
 <!-- center column -->
   <td valign="top" align="center">

  <table class="rowpic" width="100%" cellpadding="2" align="center">
  <tr><td>

  <?
  if(isset($_GET['show'])) {
  	$cat_1 = htmlentities(ereg_replace("_", "&nbsp;", $_GET['cat_select']));
	$snip_1 = htmlentities(ereg_replace("_", "&nbsp;", $_GET['show']));

   echo "<font class=hdr>Syntax for:</font> $cat_1 / $snip_1";
  } else {
  
  
  	if(isset($_GET['op'])) {
		if($_GET['op'] == "cats") {
			if(isset($_GET['cats'])) {
				$cat_3 = htmlentities(ereg_replace("_", "&nbsp;", $_GET['cats']));
				echo "<font class=hdr>Edit a Category:</font> $cat_3";
			} else {
				echo "Edit a Category";
			}
		} elseif($_GET['op'] == "snips") {
			if(isset($_GET['cats'])) {
				if($_GET['act'] == "edit") {
					$cat_3 = htmlentities(ereg_replace("_", "&nbsp;", $_GET['cats']));
					$snip_3 = htmlentities(ereg_replace("_", "&nbsp;", $_GET['snip']));
					echo "<font class=hdr>Edit Snippet:</font> $cat_3 / $snip_3";
				} else {
					$cat_3 = htmlentities(ereg_replace("_", "&nbsp;", $_GET['cats']));
					$snip_3 = htmlentities(ereg_replace("_", "&nbsp;", $_GET['snips_delete']));
					echo "<font class=hdr>Delete Snippet:</font> $cat_3 / $snip_3";
				}
			} else {
				if($_GET['act'] == "add") {
					echo "Add a Snippet";
				} elseif($_GET['act'] == "edit") {
					echo "Edit a Snippet";
				} else {
					echo "Delete a Snippet";
				}
			}
		} else {
   			echo $site_name;
   		}
	} else {
		if(isset($_GET['cat_select'])) {
			$cat_3 = htmlentities(ereg_replace("_", "&nbsp;", $_GET['cat_select']));
			echo "<font class=hdr>Snippet available for: </font> $cat_3";
		}
		
			if($_SERVER["QUERY_STRING"] == "") {
   				echo "Welcome to ".$site_name;
			}
   		}
  }
  ?>

  </td></tr>
   </table> 
   
     
<table class="table1" width="100%" cellpadding="2" align="center" border="0">
	<tr>
      <td>
	 <? 
if(isset($_GET['show']) || isset($_GET['op'])) {
	 
	 
	 // Are we using Inline Frames?
	 if($use_iframe == 1 && !isset($_GET['op'])) {
	 	if(isset($_GET['show']) && isset($_GET['cat_select'])) {
			$qst = $_SERVER["QUERY_STRING"];
		}
	 	echo '<iframe src="middle.php?'.$qst.'" width="100%" height="300" frameborder="0" align="center">-- iframe is enabled!!, please disable in config.php --</iframe>';
	 } else {
	 	require 'middle.php';
	 }
	 
	 
	 
	 } elseif (isset($_GET['cat_select'])) {
	 		$cat_2 = ereg_replace("_", "&nbsp;", $_GET['cat_select']);
			// show sub menu
			$ob = new SNIPS;
			$ob->snips_list_set($_GET['cat_select']);
			$array = $ob->snips_list_get();
			$per_r = 3;
			
			if(is_array($array)) {
				echo '<br><br><table width="90%" align="center" border="0"><tr>';
			 foreach($array as $ar) {
			 	if(!isset($cur_r)) { $cur_r = 0; }
				if(!isset($cur_c)) { $cur_c = 0; }
	
				$_GET['show'] = $ar;
				$show_half = "yes";
				$ar_2 = ereg_replace("_", "&nbsp;", $ar);
				
				// check cell
				if ($cur_c < $per_r) { 
					echo '<td valign="top" width="33%">';    
				} else {
					echo '</tr><tr><td valign="top" width="33%">';
					$cur_c = 0;
				}
		
		
				echo '<table border="0"><tr><td valign="top"><img src='.$snippet_theme.'/img/code.gif alt=category border=0>&nbsp;</td><td valign="top"><a class="link" href=index.php?cat_select='.$_GET['cat_select'].'&show='.$ar.'>'.$ar_2.'</a><br>';
				include 'description.php';
				echo '</td></tr></table></td>';
				unset($_GET['show']);
				
				$cur_c++;
			 } // for
			 while($cur_c < $per_r) { 
					echo '<td width="33%">&nbsp;</td>';
					$cur_c++;
			 } // while
			 echo '</tr></table>';
			 include 'cat_options.php';
			} // if
			
			   
   } elseif(isset($_GET['login'])) {
   
   echo '<table class=op cellpadding=6><tr><td><h1>Please Login</h1></td></tr><tr align=center><td>';
         echo '<form name=phpcsllogin method=post action=index.php>';
      
      if($err == "yes") {
         echo "<font color=red>Wrong Password - please try again</font><br><br>";
      }
	  
	  /* 
      if($_GET['op1'] == "") {
         $query = "";
      } else {
         $query = "op=".$_GET['op1']."&act=".$_GET['act1'];
      }
	  */
	  
	  	echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
      	echo '<b>Enter Password:</b>&nbsp;';
         echo '<input type=password name=csl_pass size=20></td><tr><tr><td class="fr2" align="center">';
         echo '<input type=hidden name=query value="'.base64_decode($_GET['q']).'">';
         echo '<input type=submit name=submit value=submit class=submit></td></tr></table>';
      echo '</form>';
   echo '</td></tr></table>';   
	
	
	} elseif(isset($_GET['resources'])) {
		
		include 'resources.php';
		
	} elseif(isset($_GET['license'])) {
		
		echo '<pre>';
		include 'license.txt';
		echo '</pre>';
	
	} else {
		
		// you can add your own main/default page below
		include 'menu_middle.php';
		
		// un-highlight to show login message
		/*
		if(!session_is_registered('phpcsl')) {
			echo '<br><table class="message" width="100%"><tr><td align="center"><br>You must be <a href="index.php?login=y">logged-in</a> in order to use any <b>add, edit or delete</b> functionality.<Br><Br></td></tr></table><br><br>';
		}
		*/
		include 'maincat_options.php';
		// default page end
 } ?>&nbsp; 
	 </td>
	</tr>
   </table>
   <?
   if(isset($_GET['show'])) {
   ?>
   <table class="rowpic" width="100%" cellpadding="2" align="center">
    <tr> 
      <td height="22">
	  <?
	  echo "<font class=hdr>Description for:</font> $cat_1 / $snip_1";
	  ?>
	  </td>
    </tr>
   </table>
   
   <table class="table1" width="100%" cellpadding="2" align="center">
    <tr><td>
	<?
	
	 // Are we using Inline Frames?
	 if($use_iframe == 1 && !isset($_GET['op'])) {
	 	if(isset($_GET['show']) && isset($_GET['cat_select'])) {
			$qst = $_SERVER["QUERY_STRING"];
		}
	 	echo '<iframe src="description.php?'.$qst.'" width="100%" height="90" frameborder="0" align="center">-- iframe is enabled!!, please disable in config.php</iframe>';
	 } else {
	 	require 'description.php';
	 } // if
	 include 'snip_options.php';
	 
	?>&nbsp;
	 </td></tr>
   </table>
<? 
} // if
?>


  </td>
</tr>
</table>
<!-- /main table -->


<!-- foot -->
<!-- Please note the credit message below is required if you want support -->

<table width="100%" cellpadding="0" cellspacing="0" class="rowpic">
	<tr>
  		<td align="right" class="hdr">Powered by: <a href="http://www.php-csl.com/" class="foot" title="PHP-CSL">PHP-CSL V0.9</a>&nbsp;</td>
 	</tr>
</table>

<!-- /foot -->
</body>
</html>
