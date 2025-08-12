<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0
 
// Restrict acces to this page

//this page clearance
$arr = array (  
  '0' => 'ADMIN',
  '1' => 'ARTICLES_MASTER',
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php';

?>
<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding"; ?>
<link href="cms_style.css" rel="stylesheet" type="text/css">

<style type="text/css">
body
{
background-image: 
url("images/app/page_bg.jpg");
background-repeat: 
repeat-y;
background-attachment: 
fixed
}
</style>

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onload="document.searchform.article_title.focus()" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Articles: View/Edit Articles:<span class="titletext0blue"> Search Articles</span></td>
  </tr>
</table>
<br>
<br>
<form action="articles_items_list.php" method=post name="searchform">
  <p class="maintext"><span class="maintext"><strong>Title:</strong></span> <br>
    <input name="article_title" type="text" class="formfields" id="article_title" size="44" maxlength="255">
    <br>
    <strong><span class="maintext">Marker:</span></strong><br>
    <select name="marker" class="formfields" id="marker" style="width:250">
      <option value=""></option>
      <?php 
		   
//kill old session
unset($_SESSION["search_query_articles"]);
		   
		    //load current user_privileges
			$query="SELECT * FROM ".$db_table_prefix."users WHERE username='".$_SESSION["current_user_username"]."'";
			$result=mysql_query($query);
			$row = mysql_fetch_array($result); //wich row
			$user_privileges=$row["user_privileges"];
		  
		  
		  //load all markers
			$query2="SELECT * FROM ".$db_table_prefix."articles_marker ORDER BY marker";
			$result2=mysql_query($query2);
			$num2=mysql_numrows($result2); //how many rows

			

$i=0;
$brojac=0;
while ($i < $num2) {

			$marker=mysql_result($result2,$i,"marker");

//display allowed markers
if (substr_count($user_privileges, "ARTICLES[$marker]")<>"0") {
echo "<option value=\"".$marker."\" >".$marker."</option>";
if ($brojac>0) {$to_query=$to_query." or marker='".$marker."'";} else {$to_query=" marker='".$marker."'";}

	$brojac++;
}

++$i;
}



		  ?>
    </select>
    <br>
    <strong>Category:</strong><br>
    <select name="category" class="formfields" id="category" style="width:250">
      <option value=""></option>
      <?php 
	   
		  
		  //load all categories
			$query2="SELECT * FROM ".$db_table_prefix."articles_category ORDER BY category";
			$result2=mysql_query($query2);
			$num2=mysql_numrows($result2); //how many rows

			

//loop
$i=0;
$brojac=0;
while ($i < $num2) {
			$category=mysql_result($result2,$i,"category");

echo "<option value=\"".$category."\" >".$category."</option>";

$brojac++;
++$i;
}

//end loop

		  ?>
    </select>
    <br>
    <strong>Containing text:</strong><br>
    <input name="con_text" type="text" class="formfields" id="con_text" size="44" maxlength="255">
    

    <div class="maintext">
<br>
	<strong>
      Date:</strong>
    <span class="maintext">
    <br>

    <input name="expired" type="checkbox" id="expired" value="1">
   
  <strong>Expired only</strong></span><br>
    <br>
    <table width="300" height="30" border="0" cellpadding="0" cellspacing="5" class="okvir">
      <tr> 
        <td align="left" valign="top" class="maintext"><strong>In the past: <span class="maintext"> 
          <select name="past" class="formfields" id="past">
            <option value=""></option>
            <option value="1">1 day old</option>
            <option value="2">2 days old</option>
            <option value="3">3 days old</option>
            <option value="4">4 days old</option>
            <option value="5">5 days old</option>
            <option value="6">6 days old</option>
            <option value="7">7 days old</option>
            <option value="8">8 days old</option>
            <option value="9">9 days old</option>
            <option value="10">10 days old</option>
          </select>
          </span></strong></td>
      </tr>
    </table>

    <strong><br>


    </strong></div> 
  <table width="300" height="100" border="0" cellpadding="0" cellspacing="5" class="okvir">
    <tr> 
      <td align="left" valign="top"><span class="maintext"><strong>From:</strong> 
        <br>
        Day: 
        <select name="from_day" class="formfields" id="from_day">
          <option value=""></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select>
        / Month: 
        <select name="from_month" class="formfields" id="from_month">
          <option value=""></option>
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
        / Year: 
        <select name="from_year" class="formfields" id="from_year">
          <option value=""></option>
          <option value="2004">2004</option>
          <option value="2005">2005</option>
          <option value="2006">2006</option>		  
          <option value="2007">2007</option>		  		  
          <option value="2008">2008</option>		  		  
          <option value="2009">2009</option>
          <option value="2010">2010</option>		  		  
          <option value="2011">2011</option>
          <option value="2012">2012</option>		  		  		  
          <option value="2013">2013</option>		  		  
          <option value="2014">2014</option>		  		  		  
        </select>
        <br>
        <strong><br>
        To:<br>
        </strong> Day: 
        <select name="to_day" class="formfields" id="to_day">
          <option value=""></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
          <option value="11">11</option>
          <option value="12">12</option>
          <option value="13">13</option>
          <option value="14">14</option>
          <option value="15">15</option>
          <option value="16">16</option>
          <option value="17">17</option>
          <option value="18">18</option>
          <option value="19">19</option>
          <option value="20">20</option>
          <option value="21">21</option>
          <option value="22">22</option>
          <option value="23">23</option>
          <option value="24">24</option>
          <option value="25">25</option>
          <option value="26">26</option>
          <option value="27">27</option>
          <option value="28">28</option>
          <option value="29">29</option>
          <option value="30">30</option>
          <option value="31">31</option>
        </select>
        / Month: 
        <select name="to_month" class="formfields" id="to_month">
          <option value=""></option>
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
        / Year: 
        <select name="to_year" class="formfields" id="to_year">
          <option value=""></option>
          <option value="2004">2004</option>
          <option value="2005">2005</option>
          <option value="2006">2006</option>		  
          <option value="2007">2007</option>		  		  
          <option value="2008">2008</option>		  		  
          <option value="2009">2009</option>
          <option value="2010">2010</option>		  		  
          <option value="2011">2011</option>
          <option value="2012">2012</option>		  		  		  
          <option value="2013">2013</option>		  		  
          <option value="2014">2014</option>		  		  
        </select>
        </span></td>
    </tr>
  </table>
  <span class="maintext"><br>
  
    Show 
    
  <select name="pagelimit" class="formfields" id="select">
      
    <option value="5">5</option>    
    <option value="10">10</option>      
    <option value="20" selected>20</option>      
    <option value="30">30</option>      
    <option value="40">40</option>      
    <option value="50">50</option>      
    <option value="100">100</option>      
    <option value="200">200</option>      
    <option value="300">300</option>
    <option value="500">500</option>
    <option value="1000">1000</option>		
    
  </select>
    results per page.</span> <br>
    <br>
  
  <br>
    <br>
    <input name="to_query" type="hidden" value="<?php echo "$to_query";?>">
	<input type="submit" name="submit" value="Search articles ->" style="width: 100px; height: 26px;" class="formfields2"> 
    <br>
    
</form>

<br>
<br>
<br>

</body>
</html>
