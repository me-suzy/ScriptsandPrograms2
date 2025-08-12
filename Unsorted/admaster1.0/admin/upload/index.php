<?
include_once "sys/Conf.inc";

function displayUploadedList ()
{
	global $UPLOAD_PATH, 
		   $UPLOAD_NUM,
		   $HTTP_POST_FILES;
		   
	
	$buff = '';
	$error = 0;
	for ($i = 0; $i < $UPLOAD_NUM; $i++)
	{
		$name = $HTTP_POST_FILES["Image$i"]['name'];
		$tmp  = $HTTP_POST_FILES["Image$i"]['tmp_name'];
		
		if (!is_uploaded_file ($tmp))
			continue;
		
		$buff .= "<li><font class=Mail>".$name."</font>";
		
		if (!move_uploaded_file($tmp, $UPLOAD_PATH."/".$name))
			$error = 1;
	}
	
	if (strlen ($buff) == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Select at least one image to upload.</font></font></font></p>	
		<?	
		return false;
	}
	else if ($error)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="red"><b><font size="3"><u>Error</u>:<font color="#333333"> 
  </font></font></b><br><font size="3" color="#333333"><font size="2">Some of selected images have not been uploaded.<br>
  Check existence of upload directory: <b><?=$UPLOAD_PATH?></b> and your permissions.</font></font></font></p>	
		<?	
		return false;
	}
	
	?>
	<p><font class=Dialog>The following images have been uploaded</font></p>
	<ul><?=$buff?></ul><br>
	<?
	
	return true;
}
function displayUploadForm ()
{
	global $UPLOAD_NUM;

	$tablePre = "
				<table border=0 cellspacing=0 cellpadding=0>
              	<tr>
                <td bgcolor=#C0C0C0 align=left valign=top>
                <table border=0 cellspacing=1 cellpadding=3>
                <tr> 
                 <td class=TableHeader align=left valign=middle colspan=8 bgcolor=#C0C0C0>Upload form </td>
                      </tr>
                      <tr bgcolor=#FFFFFF> 
                        <td class=TableHeader align=left valign=middle colspan=8 height=1></td>
                      </tr>
                      ";               

	$displayed = '';
	for ($i = 0; $i < $UPLOAD_NUM; $i++)
	{
		$displayed .= "<td  class=TableElement bgcolor=#FFFFFF><input type=file name=Image$i length=25></td></tr>";
    }                      
                      
	$tableSuf = "
                    </table>
                </td>
              </tr>
            </table>
              <p><input type=submit name=Upload value=\"Upload\" style=\"font-size: 10pt;\"></p>";
              
	print $tablePre.$displayed.$tableSuf;
}
?>
<html>
<head>
<title>Admin | Users | User info</title>
<link rel=stylesheet type=text/css href=./admin.css>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height=100% border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" bgcolor="#C0C0C0">
      <table width="100%" height=100% border="0" cellspacing="1" cellpadding="5">
        <tr>
          <td bgcolor="#C0C0C0" width="25" height=20>&nbsp;</td>
          <td bgcolor="#FFFFFF" colspan=2>
          <?
          		print "<font class=Mail>image upload</font>";
          ?>
            </td>
        </tr>
        <tr> <form method=POST enctype="multipart/form-data" action="./index.php" >
          <td align="left" valign="top" bgcolor="#FFFFFF" width="25">&nbsp; </td>
            <td align="left" valign="top" bgcolor="#FFFFFF" height=100%> 
			<?			
				displayUploadForm ();
			?>
            </td>
            <td align="left" valign="top" bgcolor="#FFFFFF" width="50%">
            <?
            
            if (isset ($Upload))
            	displayUploadedList ();
            ?>
            </td>
          </form>
        </tr>
        <tr>
          <td bgcolor="#C0C0C0" colspan="2" height=20><font class=Mail>Comments to: <a href=mailto:locihome@yahoo.com>locihome@yahoo.com</a></font></td>
        </tr>
      </table>
  </tr>
</table>
</body>
</html>
