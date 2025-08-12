<?php 
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
include "../100jancms/config_connection.php";
?>
<html>
<head>
<title>100janCMS Articles Control Test, powered by 100janCMS Articles Control</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="site_style.css" rel="stylesheet" type="text/css">
<meta http-equiv="imagetoolbar" content="no">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="maintext" scroll="auto">

<center>
  <table width="755" height="100%" border="0" cellpadding="0" cellspacing="0" background="images/bg_full.png" class="maintext">
    <tr>
      <td align="left" valign="top"> 
        <table width="100%" height="80" border="0" cellspacing="5" cellpadding="0" class="maintext">
          <tr> 
            <td width="79%" > <span class="titletext0">100janCMS Articles Control Test</span><br>
              <span class="titletext0blue"><strong>Home</strong></span><br> 
              <span class="maintext">powered by 100janCMS 
              Articles Control</span></td>
            <td width="21%" align="right" valign="top"><img src="images/logo_login.jpg" width="128" height="44" hspace="5" vspace="5" border="0"></td>
          </tr>
        </table>
        <hr width="755" size="1" color="#DCDCDC">
        <div align="left"><strong>&nbsp;&nbsp;<a class="fmenu" href="test_index.php">&nbsp;HOME&nbsp;</a> 
          <a class="fmenu" href="test_register.php">&nbsp;REGISTER&nbsp;</a> <span class="maintext"><a class="fmenu" href="test_login.php">&nbsp;LOGIN&nbsp;</a> 
          <a class="fmenu" href="test_logout.php">&nbsp;LOGOUT&nbsp;</a> </span></strong><span class="maintext"> 
          <?php 
if (isset($_COOKIE["website_member"])) {echo "You are logged in as <strong>".$_COOKIE["website_member"]."</strong>.";} else {echo "You are NOT logged in.";}

?>
          </span></div>
        <hr width="755" size="1" color="#DCDCDC"> <br>

        <!-- articles start -->
        <?php 
//this page specific settings
$use_marker="";



//fix
if (!empty($use_marker)) {$marker_exe=" AND marker='".$use_marker."' ";} else {$use_marker="";}
if (!empty($use_marker)) {$marker_area="&marker=".$use_marker;} else {$marker_area="";}
		
//load config
$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='app_url'";
$result=mysql_query($query);
$app_url=mysql_result($result,0,"config_value");

//expire
$now_date=time();
$exp_date=(60*60*24);

		//list all articles
		$query="SELECT * FROM ".$db_table_prefix."articles_items  WHERE NOT(($now_date > (date + ($exp_date * expire))) AND expire <> 0) AND status<>'suspended' ".$marker_exe." ORDER BY priority DESC, date DESC, idArtc DESC";
		$result=mysql_query($query);
		$num=mysql_numrows($result); //how many rows
	  
if ($num>0) {
for ($i=0;$i<$num;$i++) {
		//load data
		$id=mysql_result($result,$i,"idArtc");
		$title=mysql_result($result,$i,"title");
		$marker=mysql_result($result,$i,"marker");
		$status=mysql_result($result,$i,"status");
		$category=mysql_result($result,$i,"category");		
		$image=mysql_result($result,$i,"image");
		$position=mysql_result($result,$i,"position");
		$alt=mysql_result($result,$i,"alt");
		$source=mysql_result($result,$i,"source");
		$location=mysql_result($result,$i,"location");
		$keywords=mysql_result($result,$i,"keywords");
		$expire=mysql_result($result,$i,"expire");
		$date=mysql_result($result,$i,"date");
		$text=mysql_result($result,$i,"text");
		$text2=mysql_result($result,$i,"text");
		$priority=mysql_result($result,$i,"priority");
		$flag=mysql_result($result,$i,"flag");
		$added_by=mysql_result($result,$i,"added_by");
		$edited_by=mysql_result($result,$i,"edited_by");
		$comments_allow=mysql_result($result,$i,"comments_allow");
		$comments_registered=mysql_result($result,$i,"comments_registered");
		$comments_approve=mysql_result($result,$i,"comments_approve");
		$visits=mysql_result($result,$i,"visits");
		$rate=mysql_result($result,$i,"rate");


//user email
		$query_u="SELECT * FROM ".$db_table_prefix."users WHERE username='".$added_by."'";
		$result_u=mysql_query($query_u);
		$row_u = mysql_fetch_array($result_u); //wich row	
		$email=$row_u["email"];
		
//fix
		$date=date('F d. Y. / G:i',"$date");	  
		if (!empty($category)) {$category='<b>Category:</b> '.$category.'; ';} else {$category='';}
		if (!empty($source)) {$source='<b>Source:</b> '.$source.'; ';} else {$source='';}
		if (!empty($location)) {$location='<b>Location:</b> '.$location.'; ';} else {$location='';}
		if (!empty($email)) {$added_by='<a href="mailto:'.$email.'">'.$added_by.'</a>';} else {$added_by=$added_by;}

//count comments
		$query_c="SELECT * FROM ".$db_table_prefix."comments WHERE section='articles' AND marker='".$marker."' AND CID='".$id."' AND approval='1' ";
		$result_c=mysql_query($query_c);
		$num_c=mysql_numrows($result_c); //how many rows
		

//=====================================================================
//main table
echo '<table width="100%" border="0" cellpadding="5" cellspacing="0" class="maintext">
          <tr>
            <td align="left">';

echo '<span class="maintext2blue">'.$title.'</span><br>';
echo $category.$source.$location.' <b>Added by: </b>'.$added_by.'; <b>on</b> '.$date."<br><br>";
if ($image!=="") {echo '<img src="'.$app_url.'images/articles/'.$image.'" alt="'.$alt.'" align="'.$position.'"> ';}
echo $text.'<br>';

echo '
		</td>
	</tr>
</table>';
//service table
echo '<table width="753" height="10" border="0" cellpadding="5" cellspacing="0" class="maintext">
          <tr>
            <td align="left">';

echo '<a href="test_article_full.php?id='.$id.$marker_area.'">Read full article<a>';
if ($comments_allow==1) {echo ' / <a href="test_comments_add.php?id='.$id.'&marker='.$marker.'&section=articles">Add Comment</a>';}
if ($comments_allow==1) {echo ' / <a href="test_comments_view_all.php?id='.$id.'">View all comments ('.$num_c.')</a>';}

echo '
		</td>
	</tr>
</table>';

//=====================================================================

echo '
<!-- spacer  -->
<hr width="753" size="1" color="#DCDCDC">
';
	  
	}
}
else 
{
echo "
<br>
<br>
<br>
&nbsp;&nbsp;There are no articles.<br>
<br>
<br>
<br>
";
} 
	  
		?>
        <!-- articles end -->
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
		<br>
      </td>
    </tr>
  </table>
</center>
</body>
</html>