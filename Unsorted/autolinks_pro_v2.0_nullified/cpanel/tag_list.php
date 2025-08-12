<?
/////////////////////////////////////////////////////////////
// Program Name         : Autolinks Professional            
// Program Version      : 2.0                               
// Program Author       : ScriptsCenter                     
// Supplied by          : CyKuH [WTN] , Stive [WTN]         
// Nullified by         : CyKuH [WTN]                       
// Distribution         : via WebForum and Forums File Dumps
//                   (c) WTN Team `2002
/////////////////////////////////////////////////////////////

  include( "cp_initialize.php" );
  
  $res_tag = mysql_query( "SELECT * FROM al_tag ORDER BY name" );
  if( !mysql_num_rows($res_tag) ) fatalerr( "You haven't added any tag yet! <a href='tag_add.php'>Click here</a> to add one." );
  
  if( $special=="new" ) $notice = "Tag successfully added. Below, you can get the code to display it on your site(s).";
  if( $special=="delete" ) $notice = "Tag successfully deleted. If you haven't already done so, remove this tag from all the pages where you used it.";

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
      <table cellpadding='4' cellspacing='1' border='0' width='100%'>
        <tr>
          <td><font color="#FFFFFF" size="1">NAME</font></td>
          <td align="center"><font color="#FFFFFF" size="1">TYPE</font></td>
          <td align="center"><font color="#FFFFFF" size="1">ORDER BY</font></td>
          <td align="center"><font color="#FFFFFF" size="1">CODE TO USE</font></td>
          <td align="right"><font color="#FFFFFF" size="1">ACTIONS</font></td>
        </tr>
<?
  while( $tag = mysql_fetch_array($res_tag) ):

	// name the type
	switch( $tag['type'] )
	{
	  case 'text': $type = "Text Links"; break;
	  case 'banner': $type = "Banners"; break;
	  case 'button': $type = "Buttons"; break;
	  case 'thumb': $type = "Thumbs"; break;
	}

	// name the order
	switch( $tag['orderby'] )
	{
	  case 'hitsin': $orderby = "Hits In"; break;
	  case 'added': $orderby = "Added"; break;
	  case 'clicks': $orderby = "Clicks"; break;
	  case 'name': $orderby = "Name"; break;
	  case 'random': $orderby = "Random"; break;
	}
?>
        <tr bgcolor="#F5F5F5">
          <td><b>
            <? echo($tag['name']); ?>
            </b></td>
          <td align="center">
            <? echo($type); ?>
          </td>
          <td align="center"><? echo($orderby); ?></td>
          <td align="center">
            <input type="text" name="textfield" value="<? echo( "&lt;?php showtag({$tag[id]}); ?&gt"); ?>" size="23">
          </td>
          <td align="right"><a href="tag_edit.php?id=<? echo($tag['id']); ?>">Edit</a></td>
        </tr>

<? endwhile; ?>
		
      </table>
    </td>
  </tr>
</table>
</body>
</html>
