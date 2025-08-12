<html>
<head>
<title>Zomplog</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="style.css" type="text/css" />
<script src="editor.js"></script>
<script>
function verify(){
msg = "Are you sure you want to delete this entire record from the database?";
//all we have to do is return the return value of the confirm() method
return confirm(msg);
}
</script>

<script language="javascript" type="text/javascript">
function OpenLarge (c) {
    window.open(c,
                    'large',
                    'width=350,height=500,scrollbars=yes,status=yes');
}
</script> 

</head>
<body bgcolor="#FFFFFF" background="images/back.gif">
<? 
include("loadsettings.php");
include("../language/$settings[language].php"); 

?>
<table width="100" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF"> 
<tr>
    <td><img src="images/spacer.gif" width="15" height="15"></td>
    <td height="10">&nbsp;</td>
    <td><img src="images/spacer.gif" width="15" height="15"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>


<table width="100" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
      <tr> 
        <td colspan="2"><a href="members.php"><img src="images/head.jpg" width="750" height="132" border="0"></a></td>
      </tr>
      <tr> 
        <td class="text"><a href="members.php"><? echo "$lang_members_area"; ?></a> | <a href="../index.php"><? echo "$lang_view_site"; ?></a></td>
        <td class="text"><div align="right">
            <? checkLoggedIn("yes");
echo "<a href=\"logout.php?".session_name()."=".session_id()."\">$lang_logout</a> <b>".$_SESSION["login"]."</b>"; ?>
          </div></td>
      </tr>
      <tr>
        <td colspan="2" class="text"><a href="members.php"><img src="images/head_bottom.jpg" width="750" height="11" border="0"></a></td>
      </tr>
      <tr>
        <td class="text">&nbsp;</td>
        <td class="text">&nbsp;</td>
      </tr>
    </table>