<?
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  include( "initialize.php" );
?>

<html>
<head>
<title>ezUpload Pro Control Panel</title>
<link rel="stylesheet" href="cpanel.css">

<? if( isset($_GET['url']) && $_GET['url']!="" ): ?>
<meta http-equiv="refresh" content="2;URL=<?=$_GET['url']?>">
<? endif; ?>

</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr>
    <td>
      <table width="400" border="1" cellspacing="0" cellpadding="10" align="center" bordercolor="#000000">
        <tr>
          <td bgcolor="#FFFFFF" bordercolor="#FFFFFF" align="center">
            <?=$_GET['msg']?>
			
			<? if( isset($_GET['url']) && $_GET['url']!="" ): ?>
			<br><font size="1"><a href="<?=$_GET['url']?>">Click here if you are not being redirected</a></font>
            <? endif; ?>
		  
		  </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
