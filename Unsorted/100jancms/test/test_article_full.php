<?php 
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
include "../100jancms/config_connection.php";

//receive posted data
$id=$_GET["id"];
$vote=$_GET["vote"];
$voted=$_GET["voted"];

$cookie_name="website_article_rate_".$id;

if ( ($_COOKIE["$cookie_name"]=="") and (isset($vote)) )  {
	header("Location: test_article_rate.php?id=".$id."&vote=".$vote."&sentby=".$PHP_SELF."&cookie_name=".$cookie_name."");
}



//visits counter
$cookie_name_2="website_article_visits_".$id;
if ($_COOKIE["$cookie_name_2"]=="")
	{
	setcookie ($cookie_name_2, $id , time()+1800 ); //half an hour counting

		$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$id;
		$result=mysql_query($query);
		$row = mysql_fetch_array($result); //wich row

		$visits=$row["visits"];
		$visits=$visits+1;
		
		$query_c = "UPDATE ".$db_table_prefix."articles_items SET visits='$visits' WHERE idArtc='".$id."' ";
		mysql_query($query_c);
	}

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
      <td align="left" valign="top"> <table width="100%" height="80" border="0" cellspacing="5" cellpadding="0" class="maintext">
          <tr> 
            <td width="79%" > <span class="titletext0">100janCMS Articles Control 
              Test</span><br>
              <span class="titletext0blue"><strong>Full article</strong></span><br> 
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
		
//load config
$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='app_url'";
$result=mysql_query($query);
$app_url=mysql_result($result,0,"config_value");


if (!empty($id)) {
		//list all article
		$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$id;
		$result=mysql_query($query);
		$row = mysql_fetch_array($result); //wich row


		//load data
		$id=$row["idArtc"];
		$title=$row["title"];
		$marker=$row["marker"];
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
		$text2=mysql_result($result,$i,"text2");
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
echo $text2.'<br>';

echo '
		</td>
	</tr>
</table>';
//service table
echo '<table width="753" height="10" border="0" cellpadding="5" cellspacing="0" class="maintext">
          <tr>
            <td align="left">';

echo '<a href="test_index.php">Read all articles<a>';
if ($comments_allow==1) {echo ' / <a href="test_comments_add.php?id='.$id.'&marker='.$marker.'&section=articles">Add Comment</a>';}
if ($comments_allow==1) {echo ' / <a href="test_comments_view_all.php?id='.$id.'">View all comments ('.$num_c.')</a>';}

echo '
		</td>
	</tr>
</table>';

//=====================================================================

//properties table
echo '<table width="100%" border="0" cellpadding="5" cellspacing="0" class="maintext">
          <tr>
            <td align="left">
			<br>
			<br>';
			
//RATE THIS ARTICLE
//=====================================================================
echo "<span class='maintext'><b>RATE THIS ARTICLE</b></span><br><br>";
if ($_COOKIE["$cookie_name"]=="") {
echo 'Rate this article:  <select name="select" onChange="window.open(this.value ,\'_self\')" class="formfields" style="width:90">
                            <option>Rate article</option>
                            <option value="'.$PHP_SELF.'?vote=1&id='.$id.'">1 (bad)</option>
                            <option value="'.$PHP_SELF.'?vote=2&id='.$id.'">2</option>
                            <option value="'.$PHP_SELF.'?vote=3&id='.$id.'">3</option>
                            <option value="'.$PHP_SELF.'?vote=4&id='.$id.'">4</option>
                            <option value="'.$PHP_SELF.'?vote=5&id='.$id.'">5 (excellent)</option>
                          </select>';
}
else
{ echo "You have rated this article.";}

echo '<br><br>';


//LATEST ARTICLES
//=====================================================================
echo "<span class='maintext'><b>LATEST ARTICLES</b></span><br><br>";

$query="SELECT * FROM ".$db_table_prefix."articles_items  WHERE idArtc<>'".$id."' ORDER BY priority DESC, date DESC, idArtc DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_numrows($result); //koliko ima redova

if ($num==0) {echo "There are no articles.<br>";}
for ($i=0;$i<$num;$i++) {

		$id_latest=mysql_result($result,$i,"idArtc");
		$title=mysql_result($result,$i,"title");


echo '<b>&#8226;  <a class="maintext" href="test_article_full.php?id='.$id_latest.'">'.$title.'</a></b><br>';
}	  


//RALATED ARTICLES
//=====================================================================
if ($keywords<>"") {
echo "<br><span class='maintext'><b>RELATED ARTICLES</b></span><br><br>";

$key_array=split(",",$keywords);
$count_key_array = count($key_array);

//$searching=" (text='100jan'";

for ($i=0;$i<$count_key_array;$i++) {
$key_array[$i]=trim($key_array[$i]);

if ($searching=="") {$searching='(text LIKE "%'.$key_array[$i].'%" '.' OR title LIKE "%'.$key_array[$i].'%" '.' OR text2 LIKE "%'.$key_array[$i].'%" ';}
else {$searching=$searching.' OR text LIKE "%'.$key_array[$i].'%" '.' OR title LIKE "%'.$key_array[$i].'%" '.' OR text2 LIKE "%'.$key_array[$i].'%" ';}

}

$searching=$searching." ) ";
//echo $searching;



$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE ".$searching." AND idArtc<>'".$id."' ORDER BY priority DESC, date DESC, idArtc DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_numrows($result); //how many rows

if ($num==0) {echo "There are no articles.<br><br>";}
for ($i=0;$i<$num;$i++) {

	  $id_related=mysql_result($result,$i,"idArtc");
	  $title=mysql_result($result,$i,"title");


echo '<b>&#8226;  <a class="maintext" href="test_article_full.php?id='.$id_related.'">'.$title.'</a></b><br>';
	}
}


//MOST POPULAR ARTICLES
//=====================================================================
echo "<br><span class='maintext'><b>MOST POPULAR ARTICLES</b></span><br><br>";
//$last_month = mktime (0,0,0,date("m")-1,date("d"),  date("Y"));
//WHERE date > ".$last_month."

$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE visits<>'0' AND idArtc<>'".$id."' ORDER BY visits DESC, priority DESC, date DESC, idArtc DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_numrows($result); //how many rows

if ($num==0) {echo "There are no articles.<br>";}
for ($i=0;$i<$num;$i++) {

	  $id_popular=mysql_result($result,$i,"idArtc");
	  $title=mysql_result($result,$i,"title");
	  $visits=mysql_result($result,$i,"visits");
	  $visits="&nbsp;&nbsp;(".$visits.")";


echo '<b>&#8226;  <a class="maintext" href="test_article_full.php?id='.$id_popular.'">'.$title.$visits.'</a></b><br>';
}


//BEST RATED ARTICLES
//=====================================================================
echo "<br><span class='maintext'><b>BEST RATED ARTICLES</b></span><br><br>";
//$last_month = mktime (0,0,0,date("m")-1,date("d"),  date("Y"));
//WHERE date > ".$last_month."

$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE rate<>'0' AND idArtc<>'".$id."' ORDER BY rate DESC, priority DESC, date DESC, idArtc DESC LIMIT 10";
$result=mysql_query($query);
$num=mysql_numrows($result); //how many rows

if ($num==0) {echo "There are no articles.<br><br>";}
for ($i=0;$i<$num;$i++) {

	  $id_rated=mysql_result($result,$i,"idArtc");
	  $title=mysql_result($result,$i,"title");
  	  $rate=mysql_result($result,$i,"rate");	  
	  $rate="&nbsp;&nbsp;(".$rate.")";
	  
echo '<b>&#8226;  <a class="maintext" href="test_article_full.php?id='.$id_rated.'">'.$title.$rate.'</a></b><br>';
}	  




//closing properties table
echo '
		</td>
	</tr>
</table>';
//=====================================================================
}
else 
{
echo "
<br>
<br>
<br>
&nbsp;&nbsp;No article selected.<br>
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