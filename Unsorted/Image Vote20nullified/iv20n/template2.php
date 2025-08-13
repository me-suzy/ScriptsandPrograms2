<html>

<head>
<script LANGUAGE="JavaScript">
function fullScreen(theURL) {
window.open(theURL, '', 'fullscreen=no, scrollbars=no, width=350,height=420');
}
</script>
<title><?=$sitename?></title>
<STYLE type=text/css>

A:visited 	{TEXT-DECORATION: none}
A:hover 	{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474}
A:link		{TEXT-DECORATION: none}
A:active 	{TEXT-DECORATION: none}
BODY 		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
UL		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
LI 		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
P		{CURSOR: default; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TD 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
TR 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 12px}
SELECT 		{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
INPUT 		{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
TEXTAREA	{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
OPTION 		{BACKGROUND-COLOR: #C7D8EA; COLOR: #110474; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
FORM 		{FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px}
</STYLE>
<script language="JavaScript">
<!--
function imageSize() {
var image = document['userImage'];
if (image.fileSize <  300) { document.reportForm.submit();}}
//-->
</script>
</head>

<body bgcolor="#ffffff" text="#000000" link="#006699" alink="#000000" vlink="#000000" marginheight="0" marginwidth="0" topmargin=0 leftmargin=0 rightmargin=0>
<form name="reportForm" method="POST" action="<?=$votephp?>">
<input type="hidden" name="rnum" value="<?=$rnum?>">
<input type="hidden" name="member" value="<?=$newmember?>">
<input type="hidden" name="imgid" value="<?=$newid?>">
<input type="hidden" name="c" value="<?=$c?>">
<input type="hidden" name="vote" value="99">
</form>
<form method="POST" action="<?=$votephp?>?<?=$newid?>"> 
  <table border=0 cellspacing=1 cellpadding=4 width="550" align="center" bgcolor="#000000">
    <tr bgcolor="#375288"> 
      <td valign="top" colspan="2"> 
      <center><font color="#FFFFFF" size="1"><b>
          <input type="hidden" name="image" value="<?=$newurl?>">
          <input type="hidden" name="c" value="<?=$c?>">
              <input type="hidden" name="rnum" value="<?=$rnum?>">
          <input type="hidden" name="member" value="<?=$newmember?>">
          <input type="hidden" name="imgid" value="<?=$newid?>">
          </b>Please Rate This Picture Between 1 and 10</font><br>  
          <select name="vote" onChange="this.form.submit()">
            <option value="0" selected>----- Rate This Picture! -----</option>
            <option value="10">10 - <?=$des[9]?></option>
            <option value="9">9 - <?=$des[8]?></option>
            <option value="8">8 - <?=$des[7]?></option>
            <option value="7">7 - <?=$des[6]?></option>
            <option value="6">6 - <?=$des[5]?></option>
            <option value="5">5 - <?=$des[4]?></option>
            <option value="4">4 - <?=$des[3]?></option>
            <option value="3">3 - <?=$des[2]?></option>
            <option value="2">2 - <?=$des[1]?></option>
            <option value="1">1 - <?=$des[0]?></option>
          </select>
<?=$pickcat?>
        </center>

      </td>
    </tr>
    <tr> 
      <td valign="top" width="550" bgcolor="#CBCBED"> 
        
      <p align="center"> <?=$newimage?><br><?=$otherpics?>
       <br><br><a href="javascript:void(0);" onClick="fullScreen('<?=$mailphp?>?imgid=<?=$newid?>&type=outer');">Send this picture to a friend</a><br></p>
</p>
        <div align="center"></div>
        <p align="center"><font size="1">Please <a href="javascript:void(0);" onClick="fullScreen('<?=$reportphp?>?id=<?=$newid?>');">report 
          to us</a></font> <font size="1"> if the picture above is broken, <br>
          copyrighted, or inappropriate for this site</font></p>
      </td>
      
    <td valign="top" width="210" bgcolor="#CCCCCC" nowrap> <a href="javascript:void(0);" onClick="fullScreen('<?=$profilephp?>?u=<?=$newmember?>&id=<?=$newid?>');"><font size="1"> 
      </font></a> <font size="1"> <a href="javascript:void(0);" onClick="fullScreen('<?=$profilephp?>?u=<?=$newmember?>&id=<?=$newid?>');"><font size="1">View 
      Profile<br>
      </font></a> <font size="1"><a href="javascript:void(0);" onClick="fullScreen('<?=$mailphp?>?to=<?=$newmember?>');">Send 
      Message</a><br>
      <br>
      <?=$lastpicture?><br>
      <?=$loginbox?><br><br><a href="<?=$gophp?>?go=topphp&c=<?=$c?>&amp;w=top">Top 10</a><br>
      <a href="<?=$topphp?>?go=topphp&c=<?=$c?>&w=bottom">Bottom 10</a><br> 
      <a href="<?=$gophp?>?go=newphp&c=<?=$c?>">Newest Pictures</a></font></td>
    </tr>
    <tr bgcolor="#375288"> 
      <td valign="top" colspan="2"> 
        <div align="center">
<? include('banner1.php'); ?> 
        </div>
      </td>
    </tr>
  </table>
</form>
<center>
  <font color="#000000" size="1">Copyright &copy; 2001 <?=$sitename?> <br></font>
</center>
</body>
</html>
<? //  Image Vote(c) 2001 ProPHP.Com   ?>