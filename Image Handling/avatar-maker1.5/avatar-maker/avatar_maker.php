<?php
//////////////////////
//
// Net Avatar Maker
// version 1.5
// http://php-net.net/
// 1:01 AM 4/28/2005
//
//////////////////////
if(!function_exists('imagecreate') || !function_exists('imagettfbbox')){
echo'<br /><div align="center"><b><span style="color:#FF0000;">Error: Your server does not support PHP image generation! Contact your host for more information.</span></b></div><br />';
}

echo <<<EOT
<script type="text/javascript">
//<![CDATA[
function update_font(newimage){document.getElementById('font').src='./images/avatar_maker/fonts/'+newimage+'.gif';}
function checkit(mainForm){var oSubmit=document.getElementById('sub');if(mainForm.name.value==''){alert('Hey, ya gotta enter something for your name!');document.form.name.focus();return false;}else if(oSubmit){oSubmit.disabled=true;oSubmit.value='Creating . . .';}else{return true;}}
function GiveHex(Dec){if(Dec == 10){Value = 'A';}else if(Dec == 11){Value='B';}else if(Dec == 12){Value='C';}else if(Dec == 13){Value='D';}else if(Dec == 14){Value='E';}else if(Dec == 15){Value='F';}else{Value=''+Dec;}return Value;}
function DecToHex(){Red=window.document.forms['form'].elements['RedInput'].value;Green=window.document.forms['form'].elements['GreenInput'].value;Blue=window.document.forms['form'].elements['BlueInput'].value;a=GiveHex(Math.floor(Red / 16));b=GiveHex(Red % 16);c=GiveHex(Math.floor(Green / 16));d=GiveHex(Green % 16);e=GiveHex(Math.floor(Blue / 16));f=GiveHex(Blue % 16);z=a+b+c+d+e+f;window.document.forms['form'].elements['color'].value=z;}
function showHide(which){z="help"+which;if(document.getElementById && document.createTextNode){m=document.getElementById(z);trig=m.getElementsByTagName("div").item(0).style.display;h=m.getElementsByTagName("a").item(0).firstChild;if(trig=="block") trig="none";else if(trig==""||trig=="none") trig="block";m.getElementsByTagName("div").item(0).style.display=trig;}}
function colorvalues(s){var rgbs=s.options[s.selectedIndex].value;document.forms['form'].elements['color'].value=rgbs;}
//]]>
</script>
<p>Avatars are small images that appear next to your name on a post and also on your profile. They are ment to help give each person their own identity. Some free avatars (with a few that are animated) are below for your forum or what ever you may want to use them for. Just remeber to give us credit when you use them.</p>
<p><b>jak avatar maker</b><br />
<img src="./images/c_hr.gif" width="550" height="2" alt="" class="lo" /><br />
<form method="get" action="_avatar_created.php" name="form" onsubmit="return checkit(this);">

<table cellpadding="3" cellspacing="3" width="100%">
<tr>
<th colspan="4">This is still in beta!</th>
</tr>
<tr>
<td align="center"><img src="images/avatar_maker/1.gif" width="80" height="80" alt="Jak Fan Avatar" class="lo" /></td>
<td align="center"><img src="images/avatar_maker/2.gif" width="80" height="80" alt="Jak Fan Avatar" class="lo" /></td>
<td align="center"><img src="images/avatar_maker/3.gif" width="80" height="80" alt="Jak Fan Avatar" class="lo" /></td>
<td align="center"><img src="images/avatar_maker/4.gif" width="80" height="80" alt="Jak Fan Avatar" class="lo" /></td>
</tr>
<tr>
<td align="center"><input name="avatar" type="radio" value="1" /></td>
<td align="center"><input name="avatar" type="radio" value="2" /></td>
<td align="center"><input name="avatar" type="radio" value="3" /></td>
<td align="center"><input name="avatar" type="radio" value="4" /></td>
</tr>
<tr>
<td align="center"><img src="images/avatar_maker/5.gif" width="80" height="80" alt="Jak Fan Avatar" class="lo" /></td>
<td align="center"><img src="images/avatar_maker/6.gif" width="80" height="80" alt="Jak Fan Avatar" class="lo" /></td>
<td align="center"><img src="images/avatar_maker/7.gif" width="80" height="80" alt="Jak Fan Avatar" class="lo" /></td>
<td align="center"><img src="images/avatar_maker/8.gif" width="80" height="80" alt="Jak Fan Avatar" class="lo" /></td>
</tr>
<tr>
<td align="center"><input name="avatar" type="radio" value="5" /></td>
<td align="center"><input name="avatar" type="radio" value="6" /></td>
<td align="center"><input name="avatar" type="radio" value="7" /></td>
<td align="center"><input name="avatar" type="radio" value="8" /></td>
</tr>
<tr>
<td colspan="4">Your name: <input type="text" name="name" id="name" value="" style="width:117px;" /></td>
</tr>
<tr>
<td colspan="4">Pick A Font: <select name="font" onchange="update_font(this.options[selectedIndex].value);">
<option value="01">1</option>
<option value="02">2</option>
<option value="03">3</option>
<option value="04">4</option>
<option value="05">5</option>
<option value="06">6</option>
<option value="07">7</option>
<option value="08">8</option>
<option value="09">9</option>
<option value="10">10</option>
</select> <img src="./images/avatar_maker/fonts/01.gif" width="168" height="23" alt="" id="font" style="vertical-align:middle;" />


&nbsp; Use a Shadow? <input type="checkbox" name="shadow" value="y" />
</td>
</tr>
<tr>
<td colspan="4">Pick A Font Size: <select name="size">
<option value="4">4px</option>
<option value="5">5px</option>
<option value="6">6px</option>
<option value="7">7px</option>
<option value="8">8px</option>
<option value="9">9px</option>
<option value="10" selected="selected">10px</option>
<option value="11">11px</option>
<option value="12">12px</option>
<option value="13">13px</option>
<option value="14">14px</option>
<option value="15">15px</option>
<option value="16">16px</option>
<option value="17">17px</option>
<option value="18">18px</option>
<option value="19">19px</option>
<option value="20">20px</option>
<option value="21">21px</option>
<option value="22">22px</option>
<option value="23">23px</option>
<option value="24">24px</option>
<option value="25">25px</option>
<option value="26">26px</option>
<option value="27">27px</option>
<option value="28">28px</option>
<option value="29">29px</option>
<option value="30">30px</option>
</select></td>
</tr>
<tr>
<td colspan="4">Font Color: <select onchange="colorvalues(this)">
<option style="background:#800000;" value="800000">maroon</option>
<option style="background:#800080;" value="800080">purple</option>
<option style="background:#000080;" value="000080">navy</option>
<option style="background:#000000;" value="000000">black</option>
<option style="background:#FF0000;" value="FF0000">red</option>
<option style="background:#FF00FF;" value="FF00FF">magenta</option>
<option style="background:#0000FF;" value="0000FF">blue</option>
<option style="background:#008080;" value="008080">teal</option>
<option style="background:#808080;" value="808080">gray</option>
<option style="background:#00FFFF;" value="00FFFF">cyan</option>
<option style="background:#00FF00;" value="00FF00">lime</option>
<option style="background:#008000;" value="008000">green</option>
<option style="background:#C0C0C0;" value="C0C0C0">silver</option>
<option style="background:#FFFFFF;" value="FFFFFF" selected="selected">white</option>
<option style="background:#FFFF00;" value="FFFF00">yellow</option>
<option style="background:#808000;" value="808000">olive</option>
</select> &nbsp; Color: <input type="text" name="color" value="FFFFFF" style="width:55px;" maxlength="6" /><script type="text/javascript">
//<![CDATA[
document.write(' <a href="#help1" onclick="javascript:showHide(1)">RGB Converter<\/a>');
//]]>
</script><div id="help1" align="center"><div style="display:none;"><a name="help1" id="help1"></a>
Red: <input type="text" size="4" name="RedInput" maxlength="3" />
Green: <input type="text" size="4" name="GreenInput" maxlength="3" />
Blue: <input type="text" size="4" name="BlueInput" maxlength="3" /> <input type="button" value="RBG to Hex it!" onclick="DecToHex()" name="button" class="sub" /></div></div></td>
</tr>
<tr>
<td colspan="4" align="center">Random Avatar? <input name="avatar" type="radio" id="avatar" value="random" checked="checked" /></td>
</tr>
<tr>
<td colspan="4" align="center"><input type="submit" value="Create My Avatar!" class="sub" id="sub" /></td>
</tr>
</table>
</form>

<div align="center"><a href="http://php-net.net/">(c) 2005 NetAvatar</a></div>

EOT;
//
// you can put something for your footer here, maybe even an include
//
?>