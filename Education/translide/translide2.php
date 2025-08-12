<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Translide - Translation Solutions</title>


  <meta http-equiv="content-type"
 content="text/html; charset=ISO-8859-1">






	<link rel="stylesheet" type="text/css" href="style.css">
















</head>

<body leftmargin=0 topmargin=0 marginheight="0" marginwidth="0"  >






<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td><img src="t_11.gif" width="10" height="9" alt="" border="0"></td>
	<td background="t_13.gif"><img src="t_12.gif" width="6" height="9" alt="" border="0"></td>
	<td background="t_13.gif" align="right"><img src="t_14.gif" width="6" height="9" alt="" border="0"></td>
	<td><img src="t_15.gif" width="10" height="9" alt="" border="0"></td>
</tr>
<tr valign="top">
	<td background="t_fon_left.gif"><img src="t_21.gif" width="10" height="6" alt="" border="0"></td>
	<td rowspan="2" colspan="2">


<table><tr>
<td>
<img src="globe.jpg" height="50" alt="" border="0">
</td>
<td>

<p>Translide - Translation Software

</td></tr></table>

<hr>


<form name="form1" method="post" action="translide2.php">

<p>
<input type="radio" name="lang1" value="EnglishTOChinese" checked>English To Chinese
<input type="radio" name="lang1" value="EnglishTOFrench">English To French
<input type="radio" name="lang1" value="EnglishTOGerman">English To German
<input type="radio" name="lang1" value="EnglishTOJapanese">English To Japanese
<input type="radio" name="lang1" value="EnglishTOKorean">English To Korean
<input type="radio" name="lang1" value="EnglishTOPortuguese">English To Portuguese
<input type="radio" name="lang1" value="EnglishTOSpanish">English To Spanish
<input type="radio" name="lang1" value="ChineseTOEnglish">Chinese To English
<input type="radio" name="lang1" value="FrenchTOEnglish">French To English
<input type="radio" name="lang1" value="FrenchTOGerman">French To German
<input type="radio" name="lang1" value="GermanTOEnglish">German To English
<input type="radio" name="lang1" value="GermanTOFrench">German To French
<input type="radio" name="lang1" value="ItalianTOEnglish">Italian To English
<input type="radio" name="lang1" value="JapaneseTOEnglish">Japanese To English
<input type="radio" name="lang1" value="PortugueseTOEnglish">Portuguese To English
<input type="radio" name="lang1" value="RussianTOEnglish">Russian To English
<input type="radio" name="lang1" value="SpanishTOEnglish">Spanish To English
</p>   
<br><br>    


<table width=100%><tr><td>


<textarea NAME="sst" COLS="60" ROWS="15" value="test">
<?



class trans {


	function transL($langx,$datax) {	
		$curval1 	= $langx;
		$curval2 	= urlencode($datax);

       
       $url="http://www.webservicex.net//TranslateService.asmx/Translate?LanguageMode=$curval1&Text=$curval2";
       //ini_set('user_agent','MSIE 4\.0b2;');

       $dh = fopen("$url",'r');
       $result = fread($dh,8192); 
        $pieces = explode("net", $result);
        $pieces2 = explode(">", $pieces[1]);    
        $pieces3 = explode("</string", $pieces2[1]);                                                                                                         
       return $pieces3[0]; 
	}
}





$d = new trans();
$lang1=$_POST["lang1"];
$sst=$_POST["sst"];
$translideresult=$d -> transL("$lang1","$sst");
echo "$translideresult";





?>

</textarea>


<br>
  <input type="submit" name="Submit" value="Translate Now" size="100">
  
</p>
</form>


</td>
<td>

<?
$lang1=$_POST["lang1"];
echo "<img src=$lang1.gif width=200>";
?>
<br><br><br><br>

</td>
</tr>
</table>





<center>
<p>Designed by <a href="http://www.slidecorp.com" onclick="exit=false">Slidecorp.com</a></p>

















	</td>
	<td background="t_fon_right.gif"><img src="t_23.gif" width="10" height="6" alt="" border="0"></td>
</tr>
<tr valign="bottom">
	<td background="t_fon_left.gif"><img src="t_31.gif" width="10" height="7" alt="" border="0"></td>
	<td background="t_fon_right.gif"><img src="t_33.gif" width="10" height="7" alt="" border="0"></td>
</tr>
<tr>
	<td><img src="t_41.gif" width="10" height="10" alt="" border="0"></td>
	<td background="t_fon_bot.gif"><img src="t_42.gif" width="6" height="10" alt="" border="0"></td>
	<td background="t_fon_bot.gif" align="right"><img src="t_44.gif" width="6" height="10" alt="" border="0"></td>
	<td ><img src="t_45.gif" width="10" height="10" alt="" border="0"></td>
</tr>
</table>


</body>
</html>








