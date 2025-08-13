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
  if( count($errors)>0 )
  {
	$result = $_LANG['upload_error'];
	$prevtext = $_LANG['try_again'];
  }
  else
  {
    $result = $CONF->getval( "thankyoumsg" );
	$prevtext = $_LANG['upload_more'];
	
	// do we want to clear the session infos?
	if( $CONF->getval("moreuploads")==2 )
	{
	  // clear all session info except password/user
	  while( list($sessname,$sessvalue) = each($HTTP_SESSION_VARS) )
	  {
	    if( $sessname=="formpass" || $sessname=="userid" || $sessname=="userpass" ) continue;
		
		unset( $HTTP_SESSION_VARS[$sessname] );
		@session_unregister( $sessname );
	  }
	}
  }
  
  $result = nl2br( wordwrapnew( $result, 60 ) );
  
  $shownext = false;
  $showprev = false;
  
  if( count($errors)==0 && $CONF->getval("redirecturl")!="http://" && $CONF->getval("redirecturl")!="" )
  {
    $shownext = true;
  }
  
  if( count($errors)>0 || ( $CONF->getval("moreuploads") && $FIELD->getnumrows("file", "type") ) )
  {
    $showprev = true;
  }
  
?>

<table border="0" cellspacing="0" cellpadding="2">
  <tr> 
    <td colspan="2"> 
	  <font <?=$stylel?>><?=$result?></font>
    </td>
  </tr>

<? if( count($errors)>0 ): ?>

  <tr> 
    <td colspan="2" height="15"></td>
  </tr>
  <tr>
    <td align="center" colspan="2">
	  <table border="0" cellspacing="0" cellpadding="7" align="center" bgcolor="#E9E9E9">
 		<tr>
		  <td>
			<font <?=$stylel?>>
	
<?						  
  while( list($fieldid, $message) = each($errors) )
  {
    echo( "<b>" . $FIELD->getval("name",$fieldid) . ":</b> $message<br>" );
  }
?>
							
			</font>
		  </td>
		</tr>
	  </table>
    </td>
  </tr>

<?
  endif; 
  
  if( $showprev || $shownext ): 
?>

  <tr> 
    <td colspan="2" height="15"></td>
  </tr>
  <tr>
  <form method="post" action="<?=$_SERVER['PHP_SELF']?>">
  <input type="hidden" name="mode" value="show">
  <? showsession(); ?>  
    <td align="left">
				
<?
  if( $showprev ): 

	echo( "<input type='submit' name='prev' value='< $prevtext' $stylel>" );

  else:
  
    echo( "&nbsp;" );
  
  endif;
?>
				
	</td>
  </form>
  <form method="post" action="<?=$sitepath?>redirect.php">
  <input type="hidden" name="uploadid" value="<?=$uploadid?>">
	<td align="right">

<?
  if( $shownext )
  {
    echo( "<input type='submit' name='next' value='{$_LANG['continue']}' $stylel>" );
  }
  else
  {
    echo( "&nbsp;" );
  }
?>


	</td>
  </form>
  </tr>
					
<? endif; ?>
					
</table>