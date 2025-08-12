<html>

<!--
=============================================================
	
	Copyright (C) 2004 Alex B

	E-Mail: dirmass@yahoo.com
	URL: http://www.devplant.com
	
    This file is part of Bumpy Tables.

    Bumpy Tables is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.

    Bumpy Tables is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

=============================================================== 
-->


<head>
<title>Bumpy Tables v1.0 - New Table</title>
<style type="text/css">
td {
text-align: right;
color: #0033CC;
font-family: verdana;
font-size: 12px;
}

td.n {
text-align: right;
color: #555555;
font-family: verdana;
font-size: 12px;
}


body {
background: #99CCFF;
}
span.red {
color: red;
}
span.orange {
color: orange;
}
span.green {
color: green;
}
span.yellow {
color: yellow;
}
span.black {
color: black;
}
</style>
</head>
<body>
<h4 align="center" style="font-size: 22;font-family:sans-serif;color: #222222;letter-spacing: 0.07cm">Bumpy Tables V1.0<br />
<hr width="100%"></h4>

<h4>Step 1 - Create a new table</h4>

<p>Please enter the table details and values below:</p>
<table border="0" cellpadding="5" cellspacing="0">

<tr>
   <td>
	
	
	<form action="table.php" method="POST">
	<table border="0" cellpadding="3" cellspacing="0">

	   <tr>
	      <td>Border 1 or 0 (0 = no border):</td>
              <td><input type="text" name="border" /></td>
	   </tr>

	   <tr>
	      <td>Border style:<span class="black"> *</span></td>
              <td><input type="text" name="border_type" /></td>
	   </tr>

	   <tr>
	      <td>Border color:<span class="orange"> *</span></td>
              <td><input type="text" name="border_color" /></td>
	   </tr>

	   <tr>
	      <td>Width of the table:<span class="red"> *</span></td> 
	      <td><input type="text" name="width" /></td>	      
	   </tr>
	   <tr>
	      <td>Height of the table:<span class="red"> *</span></td>
	      <td><input type="text" name="height" /></td>
	   </tr>

   	
   <tr>
	      <td>Cellspacing value:</td>
	      <td><input type="text" name="cspacing" /></td>
	   </tr>   
	
	   <tr>
	      <td>Cellpadding value:</td>
	      <td><input type="text" name="cpadding" /></td>
	   </tr>

	   <tr>
	      <td>Table background color:<span class="orange"> *</span></td>
	      <td><input type="text" name="t_bgcolor" /></td>	     
	   </tr>


	   <tr>
	      <td>Rows:</td>
	      <td><input type="text" name="rows" /></td>
	   </tr>

	   <tr>
	      <td>Cols:</td>
	      <td><input type="text" name="cols" /></td>
	   </tr>

	   <tr>
	      <td>Cell background color:<span class="orange"> *</span></td>	
	      <td><input type="text" name="td_bgcolor" /></td>
	   </tr>

	   <tr>
     	      <td>Cell font color:<span class="orange"> *</span></td>
              <td><input type="text" name="td_color" /></td>
	   </tr>


	   <tr>
     	      <td>Cell font family:<span class="green"> *</span></td>
              <td><input type="text" name="td_family" /></td>
	   </tr>

	   <tr>
     	      <td>Cell font size:</td>
              <td><input type="text" name="td_size" /></td>
	   </tr>

	   <tr>
	      <td>Cell content alignment:<span class="yellow"> *</span></td>
	      <td><input type="text" name="td_align" /></td>
	   </tr>

	   <tr>
	      <td>Page background color (optional):<span class="orange"> *</span></td>
	      <td><input type="text" name="bgcolor" /></td>
	   </tr>

	   <tr>
	      <td><input type="submit" value="Step 2" /></td>
	   </tr>
	</table>
	</form>
	


	
   </td>

   <td valign="top" width="50%" class="n">

<h5 align="center">Notes:</h5><br />
<center>
<span class="orange">* </span>Enter a <a href="http://www.webreference.com/html/reference/color/websafe.html" target="_blank">color code</a> or a color name eg: blue, yellow, red etc.
<br /><br />

<span class="red">* </span>The width and height values can be in % "eg: 100% (of the browser window)" or in pixels "eg: 50px"
<br /><br />
<span class="green">* </span>Valid font families would be: verdana, arial, sans-serif etc.
<br /><br />
<span class="yellow">* </span>Possible values: left, center, right
<br /><br />
<span class="black">* </span>Styles: 
dotted, dashed, solid, double, ridge, inset, outset

   </td>

</center>

</tr>

</table>





<div align="center">
&copy<a href="http://www.devplant.com">Bumpy Tables 2004</a> <br />
</div>
</body>
</html>