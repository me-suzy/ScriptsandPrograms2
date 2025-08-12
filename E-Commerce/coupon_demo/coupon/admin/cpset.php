<table width= 80% align=center><tr><td>
<table width=90%><tr><td>
<?
if ($dele==1){echo"

<center><h1><font color=\"red\">Coupon $cptype deleted.</h1></font></center>";
}
?>
<? if ($cm !="c"){ echo "
<center><h2>Master Payment Setup</h2></center>
<p>Click the below button to setup / change  your \"Master Payment\" option that you can use with all your coupons to save time.</p>
<p><FORM action=\"admin_s.php\" method=\"post\" name=\"master\">
<input type=\"hidden\" name=\"fr\" value=\"5\">
<input type=\"hidden\" name=\"cptype\" value=\"mastersetup\">

<br><center><input type=\"submit\" value=\"Master Setup\" name=\"master\">
</FORM></center></p>";
}
?>

<hr>
</tr></td></table>
<center><h2>Coupon Setup/Edit Menu</h2></center>
<? if ($cm !="c"){ echo "
<p align=\"center\">Please select coupon number to setup or edit.</p>";}?>
<? if ($cm =="c"){ echo "
<p align=\"center\">Please enter coupon number to setup or edit.</p>
<p align=\"center\"><b>Do not leave any space after your last number.</b></p>
";}?>
       <FORM action="admin_s.php" method="post" name="set">
<? if ($cm !="c"){ echo "
<input type=\"hidden\" name=\"fr\" value=\"1\">
<table width=90% border=1><tr><td>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"1\" checked>Coupon one&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"2\">Coupon two&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"3\">Coupon three&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"4\">Coupon four&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"5\">Coupon five&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"6\">Coupon six&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"7\">Coupon seven&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"8\">Coupon eight&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"9\">Coupon nine&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"10\">Coupon ten&nbsp;&nbsp;<br>
</td><td>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"11\">Coupon eleven&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"12\">Coupon twevle&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"13\">Coupon thirteen&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"14\">Coupon fourteen&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"15\">Coupon fifteen&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"16\">Coupon sixteen&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"17\">Coupon seventeen&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"18\">Coupon eighteen&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"19\">Coupon nineteen&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"20\">Coupon twenty&nbsp;&nbsp;<br>
</td><td>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"21\">Coupon twenty-one&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"22\">Coupon twenty-two&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"23\">Coupon twenty-three&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"24\">Coupon twenty-four&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"25\">Coupon twenty-five&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"26\">Coupon twenty-six&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"27\">Coupon twenty-seven&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"28\">Coupon twenty-eight&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"29\">Coupon twenty-nine&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"30\">Coupon thirty&nbsp;&nbsp;<br>
</td></tr><tr><td>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"31\">Coupon thirty-one&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"32\">Coupon thiry-two&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"33\">Coupon thirty-three&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"34\">Coupon thirty-four&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"35\">Coupon thirty-five&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"36\">Coupon thirty-six&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"37\">Coupon thiry-seven&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"38\">Coupon thirty-eighth&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"39\">Coupon thirty-nine&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"40\">Coupon forty&nbsp;&nbsp;<br>
</td><td>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"41\">Coupon forty-one&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"42\">Coupon forty-two&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"43\">Coupon forty-three&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"44\">Coupon forty-four&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"45\">Coupon forty-five&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"46\">Coupon forty-six&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"47\">Coupon forty-seven&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"48\">Coupon forty-eight&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"49\">Coupon forty-nine&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"cptype\" Value=\"50\">Coupon fifty&nbsp;&nbsp;<br>
</td><td ALIGN=\"center\" VALIGN=\"top\"><br><p> Need More Coupons?<br><a href=\"cpset.php?cm=c\"> Advances Menu</a></p></td></tr></table>";
}?>
<? if ($cm =="c"){ echo "
<input type=\"hidden\" name=\"fr\" value=\"1\">
<input type=\"text\" name =\"cptype\" size=\"5\"> Enter coupon number.<br>";
}
?>
<br><input type="submit" value="Submit" name="set">
</FORM>

<p><hr></p>
<p><center><h2> Quick Edit</h2></center></p>

<p>Use the below form to do a quick edit of a coupon you already have setup.</p>

<form action="admin_s.php" method="post" name="quick">
<? if ($cm !="c"){ echo "
Select coupon number to edit.
<select name=\"cptype\" size=1> 
<option value=\"1\" SELECTED>1</option>
<option value=\"2\">2</option>
<option value=\"3\">3</option>
<option value=\"4\">4</option>
<option value=\"5\">5</option>
<option value=\"6\">6</option>
<option value=\"7\">7</option>
<option value=\"8\">8</option>
<option value=\"9\">9</option>
<option value=\"10\">10</option>
<option value=\"11\">11</option>
<option value=\"12\">12</option>
<option value=\"13\">13</option>
<option value=\"14\">14</option>
<option value=\"15\">15</option>
<option value=\"16\">16</option>
<option value=\"17\">17</option>
<option value=\"18\">18</option>
<option value=\"19\">19</option>
<option value=\"20\">20</option>
<option value=\"21\">21</option>
<option value=\"22\">22</option>
<option value=\"23\">23</option>
<option value=\"24\">24</option>
<option value=\"25\">25</option>
<option value=\"26\">26</option>
<option value=\"27\">27</option>
<option value=\"28\">28</option>
<option value=\"29\">29</option>
<option value=\"30\">30</option>
<option value=\"31\">31</option>
<option value=\"32\">32</option>
<option value=\"33\">33</option>
<option value=\"34\">34</option>
<option value=\"35\">35</option>
<option value=\"36\">36</option>
<option value=\"37\">37</option>
<option value=\"38\">38</option>
<option value=\"39\">39</option>
<option value=\"40\">40</option>
<option value=\"41\">41</option>
<option value=\"42\">42</option>
<option value=\"43\">43</option>
<option value=\"44\">44</option>
<option value=\"45\">45</option>
<option value=\"46\">46</option>
<option value=\"47\">47</option>
<option value=\"48\">48</option>
<option value=\"49\">49</option>
<option value=\"50\">50</option>
</select>";
}
?>
<? if ($cm =="c"){ echo "
<input type=\"text\" name =\"cptype\" size=\"5\"> Enter coupon number.<br>";
}
?>
<br><br>
<INPUT TYPE="radio" NAME="fr" Value="1" checked>"Form 1" Basic (Headline,Thank You URL,and more.) &nbsp;&nbsp;<br>
<INPUT TYPE="radio" NAME="fr" Value="2">"Form 2" (Product Description) &nbsp;&nbsp;<br>
<INPUT TYPE="radio" NAME="fr" Value="3">"Form 3" (Payment Description) &nbsp;&nbsp;<br>
<INPUT TYPE="radio" NAME="fr" Value="4">"Form 4" (Payment Setup)&nbsp;&nbsp;<br>


<br><input type="submit" value="Submit" name="quick">

</FORM>
<? if ($cm =="c"){ echo "
<hr>
<p> Delete Coupon.</p>
<FORM action=\"cpset.php\" method=\"post\" name=\"del\">
<input type=\"hidden\" name=\"dele\" value=\"1\">
<input type=\"hidden\" name=\"cm\" value=\"c\">
<input type=\"text\" name =\"cptype\" size=\"5\"> Enter coupon number.<br>
<br><input type=\"submit\" value=\"Delete Coupon\" name=\"del\">
</FORM>";
}
?>

<? 
if ($cm =="c"){
$ck="inc/design.php";
if (file_exists($ck)){
$ld=file("inc/design.php");
}
 echo "
<hr>
<tr><td>
<p> Change Coupon Design.</p>

<FORM action=\"cpset.php\" method=\"post\" name=\"design\">
<input type=\"hidden\" name=\"des\" value=\"1\">
<input type=\"hidden\" name=\"cm\" value=\"c\">
<input type=\"text\" name =\"cptype\" size=\"5\"> Enter coupon number.(Leave blank for global coupon setting ).<br>
<input type=\"text\" name =\"border_c\" size=\"7\" value=\"$ld[0]\"> Border Outline Color.<br>
<input type=\"text\" name =\"headbg\" size=\"7\" value=\"$ld[1]\"> Header Background Color.<br>
<input type=\"text\" name =\"headln_c\" size=\"7\" value=\"$ld[2]\"> Headline Font Color.<br>
<input type=\"text\" name =\"bg_color\" size=\"7\" value=\"$ld[3]\"> Background Color.<br>
<input type=\"text\" name =\"bg_tbl\" size=\"7\" value=\"$ld[4]\"> Background Table Color.<br>
<input type=\"text\" name =\"bg_img\" size=\"30\" value=\"$ld[5]\"> Url To Background Image.(optional)
</td><td><br>
<input type=\"radio\" name =\"coupon_b\" size=\"7\" value=\"dashed\" checked> Coupon Border Dashed<br>
<input type=\"radio\" name =\"coupon_b\" size=\"7\" value=\"dotted\"> Coupon Border Dotted.<br>
<input type=\"radio\" name =\"coupon_b\" size=\"7\" value=\"solid\"> Coupon Border Solid.<br>
<br>
<input type=\"text\" name =\"coupon_width\" size=\"1\" value=\"$ld[7]\"> Coupon Border Width.<br>
<input type=\"text\" name =\"coupon_c\" size=\"7\" value=\"$ld[8]\"> Coupon Border Color.<br>
</td></tr><tr><td>
<br><input type=\"submit\" value=\"Change Design\" name=\"design\">
</FORM>";
}

?>


<?
if ($dele==1){

include "delete.php";


}
?>

</td></tr>
<? if ($cm =="c"){ echo "
<TR> 
                <TD valign=\"top\">
<hr>


<a href=\"admin.php\">Click Here For Admin Main</a> | <a href=\"cpset.php\">Click Here For Coupon Select Menu</a>

</TD>
              </TR>";

}
?>

</table>






<? 
if ($des==1){

if ($cptype!=""){
$setdesign="inc/design$cptype.php";

$string = $border_c."\n".$headbg."\n".$headln_c."\n".$bg_color."\n".$bg_tbl."\n".$bg_img."\n".$coupon_b."\n".$coupon_width."\n".$coupon_c;

$fp = fopen($setdesign,"w");
fwrite($fp, $string);

fclose($fp);
}
else {
$setdesign="inc/design.php";

$string = $border_c."\n".$headbg."\n".$headln_c."\n".$bg_color."\n".$bg_tbl."\n".$bg_img."\n".$coupon_b."\n".$coupon_width."\n".$coupon_c;

$fp = fopen($setdesign,"w");
fwrite($fp, $string);

fclose($fp);
}
}
?>