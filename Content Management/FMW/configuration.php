<?php
require('db_connect.php');

$dbQuery = "SELECT rights "; 

$dbQuery .= "FROM users WHERE username = ('$_SESSION[username]')"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))

{ 
$permission = "$row[rights]";      // get access level
    $_SESSION["perm"] = "$permission";      // make session variables 


}
session_start();
if (($_SESSION['perm'] < "5")) {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$query="SELECT * FROM admin WHERE username = 'admin'";
$result=mysql_query($query);
$num=mysql_numrows($result); 

if ($_POST['reset values'] == 'reset values') {
?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=configuration.php"> <?php
}




if ($_POST['default'] == 'default') {
$query="UPDATE admin SET col_back='EEEEEE', col_link='CC0000', col_text='000000', col_table_border='CCCCCC', col_table_border_2='888888', col_table_row='EEEEEE', col_table_row2='AAAAAA', col_table_header='000066', col_table_header_text='FFFF00', col_table_header_2='000000', col_table_row_text='000000', texture='Light_Rock3', theme_col='Grey'
WHERE username='admin'";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);

?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=coltest.php"> <?php

}
if ($_POST['TestPage'] == 'TestPage') {
?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=coltest.php"> <?php
}

if ($_POST['submit'] == 'submit') {
$col_back = $_POST['col_back'];
$col_text = $_POST['col_text'];
$col_link = $_POST['col_link'];
$col_table_border = $_POST['col_table_border'];
$col_table_border_2 = $_POST['col_table_border_2'];
$col_table_row = $_POST['col_table_row'];
$col_table_row2 = $_POST['col_table_row2'];
$col_table_header = $_POST['col_table_header'];
$col_table_header_2 = $_POST['col_table_header_2'];
$col_table_row_text = $_POST['col_table_row_text'];
$col_table_header_text = $_POST['col_table_header_text'];
$logo_pos = $_POST['logo_pos'];
$texture = $_POST['texture'];
$title_message = $_POST['title_message'];
$admin_message = $_POST['admin_message'];
$theme_col = $_POST['theme_col'];
$site_url = $_POST['site_url'];
$admin_email = $_POST['admin_email'];
$pom_vote = $_POST['pom_vote'];



$query="UPDATE admin SET col_back='$col_back', col_text='$col_text', col_link='$col_link', col_table_border='$col_table_border',
 col_table_border_2='$col_table_border_2',  col_table_row='$col_table_row',
 col_table_row2='$col_table_row2', col_table_header='$col_table_header', col_table_header_2='$col_table_header_2', col_table_row_text='$col_table_row_text',
 col_table_header_text='$col_table_header_text', logo_pos='$logo_pos', texture='$texture', title_message='$title_message',
 admin_message='$admin_message', theme_col='$theme_col', site_url='$site_url', admin_email='$admin_email', pom_vote = '$pom_vote'
WHERE username='admin'";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);

if ("$col_text" == "$col_back") {
die( "Background and main text are same colour, please click 'Back' and change.");
}

$query="SELECT * FROM admin WHERE username = 'admin'";
$result=mysql_query($query);
$num=mysql_numrows($result); 

}

$i=0;
while ($i < $num) {

$col_back=mysql_result($result,$i,"col_back");
$col_text=mysql_result($result,$i,"col_text");
$col_link=mysql_result($result,$i,"col_link");
$col_table_row=mysql_result($result,$i,"col_table_row");
$col_table_row2=mysql_result($result,$i,"col_table_row2");
$col_table_header=mysql_result($result,$i,"col_table_header");
$col_table_header_2=mysql_result($result,$i,"col_table_header_2");
$col_table_border=mysql_result($result,$i,"col_table_border");
$col_table_border_2=mysql_result($result,$i,"col_table_border_2");
$col_table_row_text=mysql_result($result,$i,"col_table_row_text");
$col_table_header_text=mysql_result($result,$i,"col_table_header_text");
$logo_pos=mysql_result($result,$i,"logo_pos");
$texture=mysql_result($result,$i,"texture");
$title_message=mysql_result($result,$i,"title_message");
$admin_message=mysql_result($result,$i,"admin_message");
$theme_col=mysql_result($result,$i,"theme_col");
$site_url=mysql_result($result,$i,"site_url");
$admin_email=mysql_result($result,$i,"admin_email");
$pom_vote=mysql_result($result,$i,"pom_vote");




?>

<HTML>
<HEAD>

<!-- flooble Color Picker header start --> 
<script language="Javascript">
// Color Picker Script from Flooble.com
// For more information, visit 
//	http://www.flooble.com/scripts/colorpicker.php
// Copyright 2003 Animus Pactum Consulting inc.
// You may use and distribute this code freely, as long as
// you keep this copyright notice and the link to flooble.com
// if you chose to remove them, you must link to the page
// listed above from every web page where you use the color
// picker code.
//---------------------------------------------------------
     var perline = 9;
     var divSet = false;
     var curId;
     var colorLevels = Array('0', '3', '6', '9', 'C', 'F');
     var colorArray = Array();
     var ie = false;
     var nocolor = 'none';
	 if (document.all) { ie = true; nocolor = ''; }
	 function getObj(id) {
		if (ie) { return document.all[id]; } 
		else {	return document.getElementById(id);	}
	 }

     function addColor(r, g, b) {
     	var red = colorLevels[r];
     	var green = colorLevels[g];
     	var blue = colorLevels[b];
     	addColorValue(red, green, blue);
     }

     function addColorValue(r, g, b) {
     	colorArray[colorArray.length] = '' + r + r + g + g + b + b;
     }
     
     function setColor(color) {
     	var link = getObj(curId);
     	var field = getObj(curId + 'field');
     	var picker = getObj('colorpicker');
     	field.value = color;
     	if (color == '') {
	     	link.style.background = nocolor;
	     	link.style.color = nocolor;
	     	color = nocolor;
     	} else {
	     	link.style.background = color;
	     	link.style.color = color;
	    }
     	picker.style.display = 'none';
	    eval(getObj(curId + 'field').title);
     }
        
     function setDiv() {     
     	if (!document.createElement) { return; }
        var elemDiv = document.createElement('div');
        if (typeof(elemDiv.innerHTML) != 'string') { return; }
        genColors();
        elemDiv.id = 'colorpicker';
	    elemDiv.style.position = 'absolute';
        elemDiv.style.display = 'none';
        elemDiv.style.border = '#000000 1px solid';
        elemDiv.style.background = '#FFFFFF';
        elemDiv.innerHTML = '<span style="font-family:Verdana; font-size:11px;">Pick a color: ' 
          	+ '(<a href="javascript:setColor(\'\');">No color</a>)<br>' 
        	+ getColorTable() 
        	+ '<center><a href="http://www.flooble.com/scripts/colorpicker.php"'
        	+ ' target="_blank">color picker</a> by <a href="http://www.flooble.com" target="_blank"><b>flooble</b></a></center></span>';

        document.body.appendChild(elemDiv);
        divSet = true;
     }
     
     function pickColor(id) {
     	if (!divSet) { setDiv(); }
     	var picker = getObj('colorpicker');     	
		if (id == curId && picker.style.display == 'block') {
			picker.style.display = 'none';
			return;
		}
     	curId = id;
     	var thelink = getObj(id);
     	picker.style.top = getAbsoluteOffsetTop(thelink) + 20;
     	picker.style.left = getAbsoluteOffsetLeft(thelink);     
	picker.style.display = 'block';
     }
     
     function genColors() {
        addColorValue('0','0','0');
        addColorValue('3','3','3');
        addColorValue('6','6','6');
        addColorValue('8','8','8');
        addColorValue('9','9','9');                
        addColorValue('A','A','A');
        addColorValue('C','C','C');
        addColorValue('E','E','E');
        addColorValue('F','F','F');                                
			
        for (a = 1; a < colorLevels.length; a++)
			addColor(0,0,a);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(a,a,5);

        for (a = 1; a < colorLevels.length; a++)
			addColor(0,a,0);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(a,5,a);
			
        for (a = 1; a < colorLevels.length; a++)
			addColor(a,0,0);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(5,a,a);
			
			
        for (a = 1; a < colorLevels.length; a++)
			addColor(a,a,0);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(5,5,a);
			
        for (a = 1; a < colorLevels.length; a++)
			addColor(0,a,a);
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(a,5,5);

        for (a = 1; a < colorLevels.length; a++)
			addColor(a,0,a);			
        for (a = 1; a < colorLevels.length - 1; a++)
			addColor(5,a,5);
			
       	return colorArray;
     }
     function getColorTable() {
         var colors = colorArray;
      	 var tableCode = '';
         tableCode += '<table border="0" cellspacing="1" cellpadding="1">';
         for (i = 0; i < colors.length; i++) {
              if (i % perline == 0) { tableCode += '<tr>'; }
              tableCode += '<td bgcolor="#000000"><a style="outline: 1px solid #000000; color: ' 
              	  + colors[i] + '; background: ' + colors[i] + ';font-size: 10px;" title="' 
              	  + colors[i] + '" href="javascript:setColor(\'' + colors[i] + '\');">&nbsp;&nbsp;&nbsp;</a></td>';
              if (i % perline == perline - 1) { tableCode += '</tr>'; }
         }
         if (i % perline != 0) { tableCode += '</tr>'; }
         tableCode += '</table>';
      	 return tableCode;
     }
     function relateColor(id, color) {
     	var link = getObj(id);
     	if (color == '') {
	     	link.style.background = nocolor;
	     	link.style.color = nocolor;
	     	color = nocolor;
     	} else {
	     	link.style.background = color;
	     	link.style.color = color;
	    }
	    eval(getObj(id + 'field').title);
     }
     function getAbsoluteOffsetTop(obj) {
     	var top = obj.offsetTop;
     	var parent = obj.offsetParent;
     	while (parent != document.body) {
     		top += parent.offsetTop;
     		parent = parent.offsetParent;
     	}
     	return top;
     }
     
     function getAbsoluteOffsetLeft(obj) {
     	var left = obj.offsetLeft;
     	var parent = obj.offsetParent;
     	while (parent != document.body) {
     		left += parent.offsetLeft;
     		parent = parent.offsetParent;
     	}
     	return left;
     }


</script>
<!-- flooble Color Picker header end -->      


<p><font color="#000000"></font></p>
<body bgcolor="#b3b3cc">
<TITLE>Configuration</TITLE>
</HEAD>



<BODY>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

<center><h3>GiftList Configuration Screen</h3>
<br>
<a href="main.php"><font color="#<?php echo $col_link ?>">Click to return to main page</font></a>
</font>

			
<br><br>


</table>
		</td>
		<td valign="top" bgcolor="#000000">
		<table cellpadding="3" cellspacing="1" border="1" width="100%">
		<tr>


<tr><th colspan="4">Current Logo <br> <p align="<?php echo "$logo_pos" ?>"><img src="images/logo.gif"> <br><br> <center><a href="logo_upload.php"><font color="CC0000">Click to change logo<br><br></font></a> </th></tr>





			<td>
			<p></p>
			<table id="demo_table"  cellpadding="3" cellspacing="0" border="2" width="45%" align="center">
		
<tr><th colspan="4">Select Logo Position</th></tr>

<tr><th colspan="4"> 
<?php
/* 
echo "<select name=\"logo_pos\">"; 
echo "<option value=\"$logo_pos\">(Current Position) : $logo_pos </option> ";
echo "<option value=\"Center\">Center</option> "; 
echo "<option value=\"Left\">Left</option>";
echo "<option value=\"Right\">Right</option>"; 
*/


/*
switch ($logo_pos) {
case 'Center': $iscenter=' selected'; break;
case 'Left': $isleft=' selected'; break;
case 'Right': $isright=' selected'; break;
}
echo '<select name="logo_pos">'; 
echo '<option value="Center"' . $iscenter . '>Center</option>'; 
echo '<option value="Left"' . $isleft . '>Left</option>'; 
echo '<option value="Right"' . $isright . '>Right</option>
</select>'; 
*/

// this and the code above worked to default the list box to current selected. I'll use this one for now.
echo '<select name="logo_pos">'; 
echo '<option value="Center"' . ($logo_pos == 'Center' ? ' selected' : '') . '>Center</option>'; 
echo '<option value="Left"' . ($logo_pos == 'Left' ? ' selected' : '') . '>Left</option>'; 
echo '<option value="Right"' . ($logo_pos == 'Right' ? ' selected' : '') . '>Right</option>'; 





 
?>
</th></tr>


			<tr><th colspan="4">Colour Selection <br> (enter hex value or click colour square for picker)</th></tr>

			
			<tr><th>Item</th><th>Colour</th></tr> 

			<tr><td>Background</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104182755');" id="pick1104182755"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104182755field" size="7" 
onChange="relateColor('pick1104182755', this.value);" name="col_back" value="<?php echo "$col_back" ?>">
<script language="javascript">relateColor('pick1104182755', getObj('pick1104182755field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | <a href="http://www.avatarity.com/">Avatars</a>
</noscript>
<!-- flooble Color Picker end -->
</td></tr>
			<tr><td>Main Text</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183208');" id="pick1104183208"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183208field" size="7" 
onChange="relateColor('pick1104183208', this.value);" name="col_text" value="<?php echo "$col_text" ?>">
<script language="javascript">relateColor('pick1104183208', getObj('pick1104183208field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end -->
</td></tr>
			<tr><td>Hyperlink</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183209');" id="pick1104183209"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183209field" size="7" 
onChange="relateColor('pick1104183209', this.value);" name="col_link" value="<?php echo "$col_link" ?>">
<script language="javascript">relateColor('pick1104183209', getObj('pick1104183209field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end --></td></tr>

			<tr><td>Table Border Outline 1</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183210');" id="pick1104183210"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183210field" size="7" 
onChange="relateColor('pick1104183210', this.value);" name="col_table_border" value="<?php echo "$col_table_border" ?>">
<script language="javascript">relateColor('pick1104183210', getObj('pick1104183210field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end --></td></tr>

<tr><td>Table Border Outline 2</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183215');" id="pick1104183215"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183215field" size="7" 
onChange="relateColor('pick1104183215', this.value);" name="col_table_border_2" value="<?php echo "$col_table_border_2" ?>">
<script language="javascript">relateColor('pick1104183215', getObj('pick1104183215field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end --></td></tr>


			<tr><td>Table Header</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183212');" id="pick1104183212"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183212field" size="7" 
onChange="relateColor('pick1104183212', this.value);" name="col_table_header" value="<?php echo "$col_table_header" ?>">
<script language="javascript">relateColor('pick1104183212', getObj('pick1104183212field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end --></td></tr>

<tr><td>Table Header 2</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183217');" id="pick1104183217"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183217field" size="7" 
onChange="relateColor('pick1104183217', this.value);" name="col_table_header_2" value="<?php echo "$col_table_header_2" ?>">
<script language="javascript">relateColor('pick1104183217', getObj('pick1104183217field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end --></td></tr>



<tr><td>Table Header Text</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183213');" id="pick1104183213"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183213field" size="7" 
onChange="relateColor('pick1104183213', this.value);" name="col_table_header_text" value="<?php echo "$col_table_header_text" ?>">
<script language="javascript">relateColor('pick1104183213', getObj('pick1104183213field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end --></td></tr>


			<tr><td>Table Row 1</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183211');" id="pick1104183211"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183211field" size="7" 
onChange="relateColor('pick1104183211', this.value);" name="col_table_row" value="<?php echo "$col_table_row" ?>">
<script language="javascript">relateColor('pick1104183211', getObj('pick1104183211field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end --></td></tr>


			<tr><td>Table Row 2</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183216');" id="pick1104183216"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183216field" size="7" 
onChange="relateColor('pick1104183216', this.value);" name="col_table_row2" value="<?php echo "$col_table_row2" ?>">
<script language="javascript">relateColor('pick1104183216', getObj('pick1104183216field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end --></td></tr>

<tr><td>Table Row Text</td><td><!-- flooble.com Color Picker start -->
<a href="javascript:pickColor('pick1104183214');" id="pick1104183214"
style="border: 1px solid #000000; font-family:Verdana; font-size:10px;
text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
<input id="pick1104183214field" size="7" 
onChange="relateColor('pick1104183214', this.value);" name="col_table_row_text" value="<?php echo "$col_table_row_text" ?>">
<script language="javascript">relateColor('pick1104183214', getObj('pick1104183214field').value);</script>
<noscript><a href="http://www.flooble.com/scripts/colorpicker.php">javascript color picker by flooble</a> | Read free <a href="http://perplexus.info/category/2/">logic puzzles</a>
</noscript>
<!-- flooble Color Picker end --></td></tr>

<tr><th colspan="4"><font color="000000">Select Background Texture<br>(This will override background colour)</font></a> </th></tr>

<tr><th>Texture
<?php
echo '<select name="texture">'; 
echo '<option value="0"' . ($texture == 'None' ? ' selected' : '') . '>None</option>'; 
echo '<option value="Beige"' . ($texture == 'Beige' ? ' selected' : '') . '>Beige</option>'; 
echo '<option value="Blue_Pattern"' . ($texture == 'Blue_Pattern' ? ' selected' : '') . '>Blue_Pattern</option>';
echo '<option value="Blue_Pattern2"' . ($texture == 'Blue_Pattern2' ? ' selected' : '') . '>Blue_Pattern2</option>';
echo '<option value="Christmas1"' . ($texture == 'Christmas1' ? ' selected' : '') . '>Christmas1</option>';
echo '<option value="Christmas2"' . ($texture == 'Christmas2' ? ' selected' : '') . '>Christmas2</option>';
echo '<option value="Christmas3"' . ($texture == 'Christmas3' ? ' selected' : '') . '>Christmas3</option>';
echo '<option value="Dark_Grey"' . ($texture == 'Dark_Grey' ? ' selected' : '') . '>Dark_Grey</option>'; 
echo '<option value="Dark_Grey2"' . ($texture == 'Dark_Grey2' ? ' selected' : '') . '>Dark_Grey2</option>'; 
echo '<option value="Dark_Rock"' . ($texture == 'Dark_Rock' ? ' selected' : '') . '>Dark_Rock</option>';
echo '<option value="Dark_Rock2"' . ($texture == 'Dark_Rock2' ? ' selected' : '') . '>Dark_Rock2</option>';
echo '<option value="Green_Pattern"' . ($texture == 'Green_Pattern' ? ' selected' : '') . '>Green_Pattern</option>';
echo '<option value="Green_Pattern2"' . ($texture == 'Green_Pattern2' ? ' selected' : '') . '>Green_Pattern2</option>';
echo '<option value="Light_Rock"' . ($texture == 'Light_Rock' ? ' selected' : '') . '>Light_Rock</option>';
echo '<option value="Light_Rock2"' . ($texture == 'Light_Rock2' ? ' selected' : '') . '>Light_Rock2</option>';
echo '<option value="Light_Rock3"' . ($texture == 'Light_Rock3' ? ' selected' : '') . '>Light_Rock3</option>';
echo '<option value="Red_Balls"' . ($texture == 'Red_Balls' ? ' selected' : '') . '>Red_Balls</option>';
echo '<option value="Red_Pattern"' . ($texture == 'Red_Pattern' ? ' selected' : '') . '>Red_Pattern</option>';
echo '<option value="Space"' . ($texture == 'Space' ? ' selected' : '') . '>Space</option>';
echo '<option value="Stars"' . ($texture == 'Stars' ? ' selected' : '') . '>Stars</option>';
echo '<option value="Water_Blue"' . ($texture == 'Water_Blue' ? ' selected' : '') . '>Water_Blue</option>';
echo '<option value="Water_Blue_Bubbles"' . ($texture == 'Water_Blue_Bubbles' ? ' selected' : '') . '>Water_Blue_Bubbles</option>';
echo '<option value="Water_Dark_Blue"' . ($texture == 'Water_Dark_Blue' ? ' selected' : '') . '>Water_Dark_Blue</option>';



?>

</th><th><table border="1" cellpadding="0" cellspacing="0" background="textures/<?php echo "$texture" ?>.jpg" width="110"><tr>
    <td width="100%" height="60">
    <p align="left">&nbsp;</td>
  </tr>



</table></th></tr> 

<tr><th colspan="4">Select Border Theme Colour <br> (Used for borders that are based on theme colour)</th></tr>
<tr><th colspan="4">
<?php
echo '<select name="theme_col">'; 
echo '<option value="Red"' . ($theme_col == 'Red' ? ' selected' : '') . '>Red</option>'; 
echo '<option value="Purple"' . ($theme_col == 'Purple' ? ' selected' : '') . '>Purple</option>'; 
echo '<option value="Grey"' . ($theme_col == 'Grey' ? ' selected' : '') . '>Grey</option>'; 
?>




<tr><th colspan="4">Click to reset values<br>
(only before clicking submit)
<center><input type="Submit" name="reset values" value="Reset Values">
<br><br>
Click to set all colour values to default setting
<center><input type="Submit" name="default" value="default">
<br><br>
Click to view test page
<center><input type="Submit" name="TestPage" value="TestPage"></th></tr>

			
			</table>

<br><br><br>
<center>

 
<table border="2" cellpadding="0" cellspacing="0" width="70%" id="admintitle" height="30">
            
<td width="70%" height="30"> 
<center>
<H3>Enter Site Tiltle</H3>(This is the title on main page)
<input id="currency" size="100" name="title_message" value="<?php echo "$title_message" ?>">


<br><br>


<H3>Enter Admin Message</H3>(This is the welcome message on the main page) 
<table border="2" cellpadding="0" cellspacing="0" width="70%" id="adminnews" height="30">
            <td width="70%" height="30"> 
<td><textarea rows=8 cols=80 name="admin_message"><?php echo "$admin_message" ?></textarea></td>

</td></tr>
            

          </tr>


        </table>
        </center>
<H4><center>Enter Site URL
<br>
(This is used as site info in emails sent to members)
<input id="site_url" size="80" name="site_url" value="<?php echo "$site_url" ?>">
<br><br><BR>
<center>Enter Admin Email Address<br>
<input id="admin_email" size="80" name="admin_email" value="<?php echo "$admin_email" ?>">

<BR><BR>
<center>Select number of days after match to allow players to vote for 'Player Of The Match'<br>
<input id="pom_vote" size="1" name="pom_vote" value="<?php echo "$pom_vote" ?>">

</H4>


<br>

<center><br><input type="Submit" name="submit" value="submit">
<br><br>
</FORM>

</CENTER>




</BODY>
</HTML> 

<?php
++$i;
} 
?>