<?php
//Read in config file
$thisfile = "left";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
?>
<html>
<head>
<title>
<?php echo $la_pagetitle; ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set;?>">
<link rel="stylesheet" href="admin.css" type="text/css">
<script language="JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function selectRow(row)
{
var codeString = row + ".bgColor='#F9EEAE'";
eval(codeString);
}

function unselectRow(row)
{
var codeString = row + ".bgColor='#F0F0F0'";
eval(codeString);
}

function unselectRow2(row)
{
var codeString = row + ".bgColor='#DEDEDE'";
eval(codeString);
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
//-->
</script>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" onLoad="MM_preloadImages('images/arrow1.gif','images/spacer.gif')">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr> 
    <td height="0" colspan="3" class="admintitle" bgcolor="#666666"> 
      <div align="center"> 
        <p>
          <?php echo $la_admin_menu_title ?>
        </p>
      </div>
    </td>
  </tr>

	<?php
		if($sid && $session_get)
			$att_sid="?sid=$sid";
		
	?>

  <tr bgcolor="#F0F0F0" id="row1" name="row1"  onMouseOver="selectRow('row1')" onMouseOut="unselectRow('row1')" onClick="MM_swapImage('ar1','','images/arrow1.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1);MM_goToURL('parent.frames[\'body\']','navigate.php<?php echo $att_sid;?>');return document.MM_returnValue"> 
    <td height="0"><img src="images/icon1.gif" width="25" height="25"></td>
    <td height="0" class="adminitem"><a href="navigate.php<?php echo $att_sid;?>" class="adminitem"  target="body" onClick="MM_swapImage('ar1','','images/arrow1.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1)">
      <?php echo $la_nav1; ?>
      </a> </td>
    <td height="0" class="rightborder"><img src="images/arrow1.gif" width="8" height="9" name="ar1"></td>
  </tr>
  <tr bgcolor="#DEDEDE" id="row2" name="row2"  onMouseOver="selectRow('row2')" onMouseOut="unselectRow2('row2')" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/arrow1.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1);MM_goToURL('parent.frames[\'body\']','pending.php<?php echo $att_sid;?>');return document.MM_returnValue"> 
    <td height="0"><img src="images/icon2.gif" width="25" height="25"></td>
    <td height="0" class="adminitem"><a href="pending.php<?php echo $att_sid;?>" class="adminitem"  target="body" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/arrow1.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1)"> 
      <?php echo $la_nav2; ?>
      </a></td>
    <td height="0" class="rightborder"><img src="images/spacer.gif" width="8" height="9" name="ar2"></td>
  </tr>
  <?php if($ses["user_perm"]==1 || $ses["user_perm"]==2):?>
  <tr bgcolor="#F0F0F0" id="row3" name="row3"  onMouseOver="selectRow('row3')" onMouseOut="unselectRow('row3')" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/arrow1.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1);MM_goToURL('parent.frames[\'body\']','users.php<?php echo $att_sid;?>');return document.MM_returnValue"> 
    <td height="0"><img src="images/icon9.gif" width="25" height="25"></td>
    <td height="0" class="adminitem"><a href="users.php<?php echo $att_sid;?>" class="adminitem" target="body" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/arrow1.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1)" >
      <?php echo $la_nav3; ?>
      </a></td>
    <td height="0" class="rightborder"><img src="images/spacer.gif" width="8" height="9" name="ar3"></td>
  </tr>
  <tr bgcolor="#DEDEDE" id="row4" name="row4"  onMouseOver="selectRow('row4')" onMouseOut="unselectRow2('row4')" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/arrow1.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1);MM_goToURL('parent.frames[\'body\']','log.php<?php echo $att_sid;?>');return document.MM_returnValue"> 
    <td height="0"><img src="images/icon5.gif" width="25" height="25"></td>
    <td height="0" class="adminitem"><a href="log.php<?php echo $att_sid;?>" class="adminitem" target="body" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/arrow1.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1)" >
      <?php echo $la_nav4; ?>
      </a></td>
    <td height="0" class="rightborder"><img src="images/spacer.gif" width="8" height="9" name="ar4"></td>
  </tr>
  <tr bgcolor="#F0F0F0" id="row5" name="row5"  onMouseOver="selectRow('row5')" onMouseOut="unselectRow('row5')" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/arrow1.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1);MM_goToURL('parent.frames[\'body\']','cust_themes.php<?php echo $att_sid;?>');return document.MM_returnValue"> 
    <td height="0"><img src="images/icon3.gif" width="25" height="25"></td>
    <td height="0" class="adminitem"> <a href="cust_themes.php<?php echo $att_sid;?>" class="adminitem" target="body" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/arrow1.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1)" >
      <?php echo $la_nav5; ?>
      </a></td>
    <td height="0" class="rightborder"><img src="images/spacer.gif" width="8" height="9" name="ar5"></td>
  </tr>
  <tr bgcolor="#DEDEDE" id="row6" name="row6"  onMouseOver="selectRow('row6')" onMouseOut="unselectRow2('row6')" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/arrow1.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1);MM_goToURL('parent.frames[\'body\']','conf_output.php<?php echo $att_sid;?>');return document.MM_returnValue"> 
    <td height="0"><a href="conf_output.php"><img src="images/icon4.gif" width="25" height="25" border="0"></a></td>
    <td height="0" class="adminitem"><a href="conf_output.php<?php echo $att_sid;?>" class="adminitem"  target="body" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/arrow1.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1)">
      <?php echo $la_nav6; ?>
      </a></td>
    <td height="0" class="rightborder"><img src="images/spacer.gif" width="8" height="9" name="ar6"></td>
  </tr>
  <tr bgcolor="#F0F0F0" id="row7" name="row7"  onMouseOver="selectRow('row7')" onMouseOut="unselectRow('row7')" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/arrow1.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1);MM_goToURL('parent.frames[\'body\']','backup.php<?php echo $att_sid;?>');return document.MM_returnValue"> 
    <td height="0"><img src="images/icon8.gif" width="25" height="25"></td>
    <td height="0" class="adminitem"><a href="backup.php<?php echo $att_sid;?>" class="adminitem" target="body" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/arrow1.gif','ar8','','images/spacer.gif','ar9','','images/spacer.gif',1)" >
      <?php echo $la_nav7; ?>
      </a></td>
    <td height="0" class="rightborder"><img src="images/spacer.gif" width="8" height="9" name="ar7"></td>
  </tr>
  <tr bgcolor="#DEDEDE" id="row8" name="row8"  onMouseOver="selectRow('row8')" onMouseOut="unselectRow2('row8')" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/arrow1.gif','ar9','','images/spacer.gif',1);MM_goToURL('parent.frames[\'body\']','license.php<?php echo $att_sid;?>');return document.MM_returnValue"> 
    <td height="0"><img src="images/icon6.gif" width="25" height="25"></td>
    <td height="0" class="adminitem"><a href="license.php<?php echo $att_sid;?>" class="adminitem" target="body" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/arrow1.gif','ar9','','images/spacer.gif',1)" >
      <?php echo $la_nav8; ?>
      </a></td>
    <td height="0" class="rightborder"><img src="images/spacer.gif" width="8" height="9" name="ar8"></td>
  </tr>
  <?php endif; ?>
  <tr bgcolor="#F0F0F0" id="row9" name="row9"  onMouseOver="selectRow('row9')" onMouseOut="unselectRow('row9')" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/arrow1.gif',1);MM_goToURL('parent.frames[\'body\']','help/index.htm<?php echo $att_sid;?>');return document.MM_returnValue"> 
    <td height="0"><img src="images/icon7.gif" width="25" height="25"></td>
    <td height="0" class="adminitem"><a href="help/index.htm<?php echo $att_sid;?>" class="adminitem" target="body" onClick="MM_swapImage('ar1','','images/spacer.gif','ar2','','images/spacer.gif','ar3','','images/spacer.gif','ar4','','images/spacer.gif','ar5','','images/spacer.gif','ar6','','images/spacer.gif','ar7','','images/spacer.gif','ar8','','images/spacer.gif','ar9','','images/arrow1.gif',1)" >
      <?php echo $la_nav9; ?>
      </a></td>
    <td height="0" class="rightborder"><img src="images/spacer.gif" width="8" height="9" name="ar9"></td>
  </tr>
  <tr> 
    <td height="100%" background="images/back.gif" colspan="3" class="rightborder">&nbsp;</td>
  </tr>
</table>
</body>
</html>
