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
 
  /////////////////////////////////////
  // THE USER WANTS TO CHANGE PAGE
  /////////////////////////////////////
  
  if( $_POST['action']=="browse" )
  {
    if( $_POST['next'] )
    {
      $_POST['page']++;
    }
    elseif( $_POST['prev'] )
    {
      $_POST['page']--;
    }
  }
  
   
  /////////////////////////////////////
  // START SHOWING THE PAGE
  /////////////////////////////////////
  
  // clear files, info and dirs
  clearfiles();
  cleardirs();
  clearinfos();
  
  $UPLOAD->sortdata( "uploaded", "desc" );


  if( $_POST['query'] )
  {
    // clean the query
    $query = strtolower( $_POST['query'] );
    $query = str_replace( "\"", "", $query );
    $query = str_replace( "'", "", $query );
    $query = str_replace( "AND", "", $query );
    $query = str_replace( "OR", "", $query );
    $query = str_replace( "+", "", $query );
    $query = str_replace( "-", "", $query );
    $query = str_replace( "  ", " ", $query );
  
    // get an array with the matches for the three arrays
    // sorted by nummatches and grouped by the uploadid
    $MATCHES = array();
    $UPLOAD->search( $query, $MATCHES, "id" );
    $UPLOADINFO->search( $query, $MATCHES, "upload" );
    $FILE->search( $query, $MATCHES, "upload" );

    // retrieve the matches uploads
    $uploads = array();
    while( list($uploadid,$nummatches) = each($MATCHES) )
    {
      if( $CONF->getval("formprotect")!="user" )
      {
	$uploads[] = $UPLOAD->getrow( $uploadid );
      }
      else
      {
        $matchingupload = $UPLOAD->getrow( $uploadid );

        if( $matchingupload['user'] == $HTTP_SESSION_VARS['userid'] )
          $uploads[] = $matchingupload;   
      }
    }
  }
  else
  {
    if( $CONF->getval("formprotect")!="user" )
      $uploads = $UPLOAD->get();
    else
      $uploads = $UPLOAD->queryrows( $HTTP_SESSION_VARS['userid'], "user" );
  }

  $numperpage = 20; 
  if( !isset($_POST['page']) ) $_POST['page'] = 1;
  $start = ($_POST['page']-1) * $numperpage;
  
  // find out if we have to show the next/prev buttons
  $nextpage = false; $prevpage = false;
  if( count($uploads)>($start+$numperpage) ) $nextpage = true;
  if( $start>0 ) $prevpage = true;
  
  // take a slice of array depending on the page
  $uploads = array_slice( $uploads, $start, $numperpage );
  
  if( count($uploads)==0 && !$_POST['query'] ):
  
    echo( "No uploads found" );
  
  else:
  
?>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr align="center">
    <td align="left" colspan="2"><b><font <?=$stylel?>>Upload Name</font></b></td>
    <td><b><font <?=$stylel?>>Num. Files</font></b></td>
    <td><b><font <?=$stylel?>>Date</font></b></td>
    <td width="60"><b><font <?=$stylel?>>Action</font></b></td>
  </tr>
  
<?
  foreach( $uploads AS $upload ):
  
    $numfiles = $FILE->getnumrows( $upload['id'], "upload" );
	
	// if no name is defined (older version), use the subdir value or ID
	if( !isset($upload['name']) || $upload['name']=="" )
	{
	  if( $upload['subdir']=="" )
	    $upload['name'] = "Upload #" . $upload['id'];
	  else
	    $upload['name'] = substr( $upload['subdir'], 0, (strlen($upload['subdir'])-1) );
	}
?>
  
  <tr align="center">
    <td width="5"><a href="<?=$_SERVER['PHP_SELF']?>?mode=viewupload&id=<?=$upload['id']?>&<?=$SID?>"><img src="<?=$sitepath?>images/folder.gif" border="0"></a></td>
    <td align="left"><b><font <?=$stylel?>><a href="<?=$_SERVER['PHP_SELF']?>?mode=viewupload&id=<?=$upload['id']?>&<?=$SID?>"><?=$upload['name']?></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><font <?=$stylel?>><?=$numfiles?> Files</font></td>
    <td><font <?=$stylel?>><?=userdate( $upload['uploaded'] )?></font></td>
    <td><b><font <?=$stylel?>><a href="<?=$_SERVER['PHP_SELF']?>?mode=viewupload&id=<?=$upload['id']?>&<?=$SID?>">View</a></font></b></td>
  </tr>
  
<? endforeach; ?>
  
  <tr>
    <td colspan="5">
      <table width="100%" height="35" border="0" cellspacing="0" cellpadding="0">
  	<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
  	<input type="hidden" name="action" value="browse">
	<input type="hidden" name="mode" value="browse">
	<input type="hidden" name="page" value="<?=$_POST['page']?>">
 	<? showsession(); ?>
	  <tr>
    	    <td align="left" valign="bottom">
              <input <?=$stylel?> type="text" name="query" size="20" value="<?=$_POST['query']?>">
              <input <?=$stylel?> type="submit" name="search" value="Search">
            </td>
	    <td align="right" valign="bottom">
	      <? if( $prevpage ): ?><input <?=$stylel?> type="submit" name="prev" value="Previous"><? endif; ?>
	      <? if( $nextpage ): ?><input <?=$stylel?> type="submit" name="next" value="Next"><? endif; ?>
	    </td>
	  </tr>
	</form>
      </table>
    </td>
  </tr>
</table>

<? endif; ?>