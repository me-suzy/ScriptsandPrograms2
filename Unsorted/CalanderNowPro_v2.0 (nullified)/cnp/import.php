<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>Import Data</strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
if ($cval == ""){
?>
  <strong>Instructions</strong>: </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">To import your data it MUST 
  follow the import guidelines.<br>
  </font><font size="1" face="Arial, Helvetica, sans-serif">Your data must be 
  such as:</font></p>
<blockquote> 
  <p><font size="1" face="Arial, Helvetica, sans-serif">date;time;header;main 
    content</font></p>
</blockquote>
<p><font size="1" face="Arial, Helvetica, sans-serif">The separator of the fields 
  do not have to be semicolons. Each entry / row must either be on different lines 
  / paragraphs or they must be separated by something in particular. If they are 
  not on different lines / paragraphs the character / symbol that separates the 
  entries / rows must be specified when importing. You may import sections and 
  pieces of the data, however, it must always follow the layout that is shown 
  above.</font></p>
<form name="form1" method="post" action="main.php">
  <p> 
    <textarea name="words" cols="65" rows="12"></textarea>
  </p>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><b>What separates the 
    data fields?</b></font></p>
  <table width="400" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="50"><b><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="sep1" type="text" id="sep12" value=";" size="1" maxlength="5">
        </font></b></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"><font size="1">Default 
        = ; (semicolon)</font></font></td>
    </tr>
  </table>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><b>Select how each entry 
    (row) is separated:</b></font></p>
  <p> <font size="2" face="Arial, Helvetica, sans-serif"> 
    <input type="radio" name="type" value="par" checked>
    There is a new line for each entry<br>
    </font><font size="2" face="Arial, Helvetica, sans-serif"> 
    <input type="radio" name="type" value="cust">
    Other - Each row is separated by something other than a new line.<br>
    <i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Specify 
    what separates the entries / rows: </i> 
    <input type="text" name="cust">
    </font></p>
  <p> 
    <input type="submit" name="Submit" value="Submit">
    <input type="hidden" name="page" value="import">
    <input type="hidden" name="nl" value="<? print $nl; ?>">
    <input type="hidden" name="cval" value="TRUE">
  </p>
</form>
<? }
else {
?>
<div align="center"> 
  <p align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
    <?
  $num = 0;
  $num2 = 0;
  if ($type == par){
  $words=explode("\n",$words);
  }
  if ($type == cust){
  $words=explode("$cust",$words);
  }
  do {
      set_time_limit(0); 
ignore_user_abort();
  $words2=explode("$sep1",$words[$num]);
   $date = $words2[0];
  $time = $words2[1];
  $header = $words2[2];
    $info = $words2[3];

   mysql_query ("INSERT INTO cnpCalendar (date,time,header,info,nl) VALUES ('$date','$time','$header','$info','$nl')");
 
$num = $num + 1;
$num2 = 0;
flush();
} while($words[$num] != "");
?>
    <font color="#FF0000">Completed</font>.</font> 
    <? } ?>
  </p>
</div>
</body>
</html>
