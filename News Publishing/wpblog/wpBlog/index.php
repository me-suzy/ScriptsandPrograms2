<?php
/*
--------^^wpBlog 0.4^^--------
©2003-2005 Wire Plastik Design
------------------------------
*/

//Configuration

//Config Info
$params['title'] = "wpBlog"; //The Title of Your Blog
$params['logoimg'] = "bloglogo.png"; //Title Image For Your Blog
$params['copyright'] = "© 2005 Wire Plastik"; //Copyright
$params['user'] = "johndoe"; //What's your name?
$params['password'] = "blogpass"; //What's your password?
$params['timediff'] = "0"; //Difference in Hours (between the server and your computer)

//DB Info
$params['dbhost'] = "localhost"; //Your MySQL Host
$params['dbuser'] = "username"; //Your MySQL Username
$params['dbpass'] = "password"; //Your MySQL Password
$params['dbname'] = "databasename"; //Your MySQL Database Name
$params['dbpref'] = "wpg"; //Your Database Prefix

//wpRant Integration
$params['wprant'] = 1; //Use wpRant?
$params['wprantloc'] = "wpRant/wprant.php"; //Relative location of wpRant

//!!
//Stop Editing From Here On
//!!

session_start();
mysql_connect($params['dbhost'],$params['dbuser'],$params['dbpass']);
mysql_select_db($params['dbname']);
require_once("functions.php");
if($params['wprant'] == 1){
	require_once($params['wprantloc']);
}
ob_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr">

<head>

<title><?php echo $params['title'] ?></title>

<style type="text/css">
@import url("style.css");
</style>

<script type="text/javascript" src="script.js"></script>

</head>

<body>
<div align="center">

<div class="header"><img src="<?php echo $params['logoimg']?>" alt="Blog Logo" /></div>
<div class="nav">

<form name="blogviewoptions" action="<?php echo $_SERVER['PHP_SELF']?>" method="get" style="border: none; margin: 0px; padding: 0px;">
<select name="howmuch" onchange="document.blogviewoptions.submit()">
<option value="10">show last 10 posts...</option>
<?php
for($i=20;$i<=50;$i+=10){
	if($_GET['howmuch'] != "" && $_GET['howmuch'] == $i){
		$extra = " selected=\"selected\"";
	}
	else{
		$extra = "";
	}
	echo "<option value=\"$i\"$extra>show last $i posts...</option>";
}

if($_GET['howmuch'] != "" && $_GET['howmuch'] == "all"){
	$extra = " selected=\"selected\"";
}
else{
	$extra = "";
}
?>
<option value="all"<?php echo $extra ?>>show all posts...</option>
</select>
<select name="whatmonth" onchange="document.blogviewoptions.submit()">
<?php
if($_GET['whatmonth'] == ""){
	$extra[3] = " selected=\"selected\"";
}
?>
<option value=""<?php echo $extra[3]?>>from all entries...</option>
<?php
for($i=2005;$i<=2005;$i++){
	for($j=1;$j<=12;$j++){
		if($_GET['whatmonth'] != "" && $_GET['whatmonth'] == $i.$j){
			$extra = " selected=\"selected\"";
		}
		else{
			$extra = "";
		}
		echo "<option value=\"$i$j\"".$extra.">from ".strtolower(getMonthName($j))." $i...</option>";
	}
}
?>
</select>
<select name="ascdesc" onchange="document.blogviewoptions.submit()">
<option value="DESC">in...</option>
<?php
if($_GET['ascdesc'] != "" && $_GET['ascdesc'] == "ASC"){
	$extra[1] = " selected=\"selected\"";
}
else{
	$extra[2] = " selected=\"selected\"";
}
?>
<option value="ASC"<?php echo $extra[1] ?>>in ascending order...</option>
<option value="DESC"<?php echo $extra[2] ?>>in descending order...</option>
</select>
</form>
</div>
<?php
if(isset($_GET['action'])){
	switch($_GET['action']){
		case "add":
			addblog();
			break;
		case "mod":
			modblog();
			break;
		case "del":
			delblog();
			break;
		case "out":
			dologout();
			break;
		case "all":
			displayblogsall();
			break;
		default:
			displayblogs();
			break;
	}
}
else{
	displayblogs();
}

function displayblogs(){
	global $params;

	$imgArray = getImages();
	
	if($_GET['howmuch'] != ""){
		if($_GET['howmuch'] == "all"){
			$slimit = "";
		}
		else{
			$slimit = $_GET['howmuch'];
		}
	}
	else{
		$slimit = "10";
	}
	if($slimit != ""){
		$sqllimit = " LIMIT ".$slimit;
	}
	else{
		$sqllimit = "";
	}
	if($_GET['whatmonth'] != ""){
		$month = substr($_GET['whatmonth'],4);
		$year = substr($_GET['whatmonth'],0,4);
		$dateq = " WHERE MONTH(`date`) = $month AND YEAR(`date`) = $year";
	}
	else{
		$dateq = "";
	}
	$query = "SELECT * FROM ".$params['dbpref']."blogs".$dateq." ORDER BY id DESC".$sqllimit;
	$blogs = mysql_query($query);
	
  	$i=0;
    while ($tempstore = mysql_fetch_array($blogs)){
	  	foreach($tempstore as $key => $value){
   	    	$ablogs[$i][$key] = $value;
        }
        $i++;
    }
    if($_GET['ascdesc'] == "ASC"){
    	$ablogs = array_reverse($ablogs);
    }
    
    if($_GET['postid'] != ""){
    	unset($ablogs);
    	$query = "SELECT * FROM ".$params['dbpref']."blogs WHERE id='".$_GET['postid']."'";
    	$ablogs[0] = mysql_fetch_array(mysql_query($query));
    }
    ?>
	<br /><br />
	<table class="tform" cellpadding="0" cellspacing="0">
	<?php
	for($i=0;$i<count($ablogs);$i++){
		if($i%2==0){
			$tdtype1 = "<td class=\"blog1\"></td>";
			$tdtype2 = "<td class=\"blog2\"></td>";
		}
		else{
			$tdtype1 = "<td class=\"blog2\"></td>";
			$tdtype2 = "<td class=\"blog1\"></td>";
		}
		?>
		<tr class="borderit1">
		<?php echo $tdtype1 ?>
		<td><div class="bheader"><?php echo $ablogs[$i]['title']; ?></div></td>
		<?php echo $tdtype2 ?>
		</tr>
		<tr>
		<?php echo $tdtype1 ?>
		<td>
		<div class="bcontent">
		<?php echo returnImage($imgArray,$i) ?>
		<?php echo stripslashes($ablogs[$i]['content']); ?>
		</div>
		</td>
		<?php echo $tdtype2 ?>
		</tr>
		<tr class="borderit2">
		<?php echo $tdtype1 ?>
		<td><div class="bfooter"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?postid=<?php echo $ablogs[$i]['id']?>">directlink</a>&nbsp;|&nbsp;posted by <span class="othertext"><?php echo $params['user'] ?></span> on <span class="othertext"><?php echo strtolower(formatTime($ablogs[$i]['date'])); ?></span></div></td>
		<?php echo $tdtype2 ?>
		</tr>
		<tr>
		<td colspan="3"><br /><br /></td>
		</tr>
		<?php
	}
	if(count($ablogs) == 0){
		echo "<b>no posts to show...</b>";
	}
	?>
	</table>
	<br />
	<?php
	if($params['wprant'] == 1){
		echo "<div style=\"padding-top: 5px;padding-bottom: 5px;text-align: center;\">";
		wpr_create("blog","600","120",10,1,1);
		echo "</div>";
	}
}

function addblog(){
	checkLogin();
	global $params;
	if(isset($_POST['submitted']) && $_POST['submitted'] == 1){
		echo "<b>Add Another Blog:</b>";
	}
	else{
		echo "<b>Add A Blog:</b>";
	}
	?>
	<script language="javascript">
	function checkAB(){
		if(document.addblog.title.value == ""){
			alert("Your blog needs a title.");
			document.addblog.title.focus();
		}
		else if(document.addblog.content.value == ""){
			alert("Your blog needs content.");
			document.addblog.content.focus();
		}
		else{
			document.addblog.submit();
		}
	}
	</script>
	<form name="addblog" action="<?php echo $_SERVER['PHP_SELF'] ?>?action=add" method="post">
	<input type="hidden" name="submitted" value="1" />
	
	<table class="tform" cellspacing="0" cellpadding="2">
	
	<tr>
	<td class="tleft">
	<span class="formtitle">blog title</span>
	<div class="formdesc">type in a title for this blog.</div>
	</td>
	<td class="tright"><input type="text" name="title" value="" size="25" maxlength="100" /></td>
	</tr>	
		
	<tr>
	<td class="tleft">
	<span class="formtitle">blog content</span>
	<div class="formdesc">type in your thoughts.</div>
	</td>
	<td class="tright">
	<input type="button" name="edbold" value="b" accesskey="b" onclick="bold()" onmouseover="window.status='bold, alt+b'" />
	<input type="button" name="edbold" value="i" accesskey="i" onclick="italics()" onmouseover="window.status='italics, alt+i'" />
	<input type="button" name="edbold" value="break" accesskey="r" onclick="brfunc()" onmouseover="window.status='line break, alt+r'" />
	<input type="button" name="edbold" value="link" accesskey="l" onclick="link()" onmouseover="window.status='link, alt+l'" />
	<br />
	<textarea id="contenth" name="content" rows="10" cols="60" onfocus="storeCaret(this);" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" ondblclick="storeCaret(this);"></textarea>
	</td>
	</tr>	
	
	<tr>
	<td class="tmiddle" colspan="2"><input type="button" value="Add Blog!" onclick="checkAB()" /></td>
	</tr>
	</table>
	</form>
	<br />
	<?php
	if(isset($_POST['submitted']) && $_POST['submitted'] == 1){
		$_POST['content'] = addslashes($_POST['content']);
		mysql_query("INSERT INTO ".$params['dbpref']."blogs SET title='".$_POST['title']."', content='".$_POST['content']."'");
		if(mysql_error() != ""){
			echo "<b>MySQL Error: ".mysql_error()."<br />";
		}
		else{
			echo "<b>Added Blog!</b><br />";
		}
	}
}

function modblog(){
	checkLogin();
	global $params;
	echo "<b>Modify A Blog:</b>";
	$blogs = mysql_query("SELECT * FROM ".$params['dbpref']."blogs ORDER BY id");
	
	if(!isset($_POST['chosen']) || $_POST['chosen'] == ""){
		?>
		<form name="modblog1" id="modblogform" action="<?php echo $_SERVER['PHP_SELF'] ?>?action=mod" method="post">
		<input type="hidden" name="chosen" value="1">
		<input type="hidden" name="blogmod" id="modblogbox" value="">
		</form>
		<script type="text/javascript">
		function selectMod(blogid){
			document.getElementById('modblogbox').value = blogid;
			document.getElementById('modblogform').submit();
		}
		</script>
		
		<table class="tform" cellspacing="0" cellpadding="2">
		
		<tr>
		<td class="tleft">
		<span class="formtitle">blog title</span>
		<div class="formdesc">choose a blog to edit</div>
		</td>
		<td class="tright">
		<?php
		while($ablogs = mysql_fetch_array($blogs)){
			echo "<a href=\"#\" onclick=\"selectMod('".$ablogs['id']."')\">".$ablogs['title']."</a> on ".strtolower(formatTime($ablogs['date']))."<br />";
		}
		?>
		</td></tr>
		</table>
		<?php
	}
	else{
		$currblog = mysql_fetch_array(mysql_query("SELECT * FROM ".$params['dbpref']."blogs WHERE id='".$_POST['blogmod']."'"));
		?>
		<form name="modblog2" action="<?php echo $_SERVER['PHP_SELF'] ?>?action=mod" method="post">
		<input type="hidden" name="chosen" value="1" />
		<input type="hidden" name="submitted" value="1" />
		<input type="hidden" name="blogmod" value="<?php echo $_POST['blogmod'] ?>" />
		
		<table class="tform" cellspacing="0" cellpadding="2">
		
		<tr>
		<td class="tleft">
		<span class="formtitle">blog title</span>
		<div class="formdesc">edit the title for this blog.</div>
		</td>
		<td class="tright"><input type="text" name="title" value="<?php echo $currblog['title'] ?>" size="25" maxlength="100" /></td>
		</tr>	
			
		<tr>
		<td class="tleft">
		<span class="formtitle">blog content</span>
		<div class="formdesc">type in your thoughts.</div>
		</td>
		<td class="tright">
		<input type="button" name="edbold" value="b" accesskey="b" onclick="bold()" onmouseover="window.status='bold, alt+b'" />
		<input type="button" name="edbold" value="i" accesskey="i" onclick="italics()" onmouseover="window.status='italics, alt+i'" />
		<input type="button" name="edbold" value="break" accesskey="r" onclick="brfunc()" onmouseover="window.status='line break, alt+r'" />
		<input type="button" name="edbold" value="link" accesskey="l" onclick="link()" onmouseover="window.status='link, alt+l'" />
		<br />
		<textarea id="contenth" name="content" rows="10" cols="60" onfocus="storeCaret(this);" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" ondblclick="storeCaret(this);"><?php echo stripslashes($currblog['content']) ?></textarea>
		</td>
		</tr>	
		
		<tr>
		<td class="tmiddle" colspan="2"><input type="submit" value="Edit Blog!" /></td>
		</tr>
		</table>
		</form>
		<?php
		if(isset($_POST['submitted']) && $_POST['submitted'] == 1){
			$_POST['content'] = addslashes($_POST['content']);
			mysql_query("UPDATE ".$params['dbpref']."blogs SET title='".$_POST['title']."', content='".$_POST['content']."' WHERE id='".$_POST['blogmod']."'");
			if(mysql_error() != ""){
				echo "<b>MySQL Error: ".mysql_error()."<br />";
			}
			else{
				echo "<b>Edited Blog!</b><br />";
			}
		}
	}
}
	
	

function delblog(){
	checkLogin();
	global $params;
	echo "<b>Delete A Blog:</b>";
	$blogs = mysql_query("SELECT * FROM ".$params['dbpref']."blogs ORDER BY id");
	?>
	<form name="addblog" id="delblogform" action="<?php echo $_SERVER['PHP_SELF'] ?>?action=del" method="post">
	<input type="hidden" name="submitted" value="1" />
	<input type="hidden" name="blogdel" id="delblogbox" value="">
	</form>
	<script type="text/javascript">
	function selectDel(blogid){
		document.getElementById('delblogbox').value = blogid;
		document.getElementById('delblogform').submit();
	}
	</script>

	<table class="tform" cellspacing="0" cellpadding="2">
	
	<tr>
	<td class="tleft">
	<span class="formtitle">blog title</span>
	<div class="formdesc">choose a blog to delete</div>
	</td>
	<td class="tright">
	<?php
	while($ablogs = mysql_fetch_array($blogs)){
		echo "<a href=\"#\" onclick=\"selectDel('".$ablogs['id']."')\">".$ablogs['title']."</a> on ".strtolower(formatTime($ablogs['date']))."<br />";
	}
	?>
	</td></tr>
	</table>
	<?php
	if(isset($_POST['submitted']) && $_POST['submitted'] == 1){
		global $params;
		mysql_query("DELETE FROM ".$params['dbpref']."blogs WHERE id='".$_POST['blogdel']."'");
		if(mysql_error() != ""){
			echo "<b>MySQL Error: ".mysql_error()."<br />";
		}
		else{
			echo "<b>Deleted Blog!</b><br />";
		}
	}
}
?>
<div align="left" style="width: 50%;">
<?php
if(!isset($_SESSION['password'])){
	?>
	<form name="passwordenter" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" style="border: none; padding: 0px; margin: 0px;">
	<input type="password" name="password" />
	<input type="submit" value="log in" />
	</form>
	<?php
	if(isset($_POST['password']) && $_POST['password'] == $params['password']){
		$_SESSION['password'] = $_POST['password'];
		header("location: ".$_SERVER['PHP_SELF']);
	}
}
else{
	?>
	<form name="actions" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" style="border: none; padding: 0px; margin: 0px;">
	<select name="action" onchange="document.actions.submit()">
	<option value="">action...</option>
	<option value="">go 2 home</option>
	<option value="add">add a blog</option>
	<option value="mod">mod a blog</option>
	<option value="del">del a blog</option>
	<option value="out">log out</option>
	</select>
	</form>
	<?php
}
?>
</div>
<div class="footer">
<?php echo $params['copyright'] ?>&nbsp;<br />
powered by <a href="http://www.wireplastik.com">wpBlog 0.4</a>&nbsp;
</div>
</div>
</body>
</html>
<?php
ob_end_flush();
?>