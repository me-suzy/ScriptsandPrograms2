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
  $section = "browser";
  include( "initialize.php" );
  
  checklogged();

  
  /////////////////////////////////////
  // THE USER WANTS TO EMAIL
  /////////////////////////////////////
  
  if( $_POST['action']=="email" )
  {
    if( $demomode ) confirm( "No emails can be sent on the demo mode" );
  
    $uploads = $UPLOAD->get();
	
	$nummessages = 0;
	
	foreach( $uploads AS $upload )
	{
	  if( !isemail($upload['email']) ) continue;

	  sendemail( $upload['email'], $CONF->getval("adminname"), $CONF->getval("adminemail"), $_POST['title'], $_POST['content'] );

	  $nummessages++;
	}

    confirm( "$nummessages messages successfully sent", "browser.php" );
  }
  
  
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
	
	header( "Location: browser.php?page={$_POST['page']}&query={$_POST['query']}&$SID" );
  }
  
   
  /////////////////////////////////////
  // START SHOWING THE PAGE
  /////////////////////////////////////
  
  showheader( $section );

  // clear files, info and dirs
  clearfiles();
  cleardirs();
  clearinfos();
  
  $UPLOAD->sortdata( "uploaded", "desc" );
  
  if( $_GET['query'] )
  {
    // clean the query
    $query = strtolower( $_GET['query'] );
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
	  $uploads[] = $UPLOAD->getrow( $uploadid );
	}

    // count how many matches there is
	$nummatches = count( $uploads );

	showmessage( "Found $nummatches matches for your query \"{$_GET['query']}\"" );
  }
  elseif( $_GET['user'] )
  {
    $uploads = $UPLOAD->queryrows( $_GET['user'], "user" );
	
	showmessage( "Showing all uploads by user " . $USER->getval("name",$_GET['user']) );
  }
  else
  {
    $uploads = $UPLOAD->get();
  }

  $numperpage = 30; 
  if( !isset($_GET['page']) ) $_GET['page'] = 1;
  $start = ($_GET['page']-1) * $numperpage;
  
  // find out if we have to show the next/prev buttons
  $nextpage = false; $prevpage = false;
  if( count($uploads)>($start+$numperpage) ) $nextpage = true;
  if( $start>0 ) $prevpage = true;
  
  // take a slice of array depending on the page
  $uploads = array_slice( $uploads, $start, $numperpage );
  
  if( count($uploads)==0 && !$_GET['query'] ):
  
    echo( "No uploads found" );
  
  else:
  
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr align="center" class="header">
    <td align="left" colspan="2">Upload Name</td>
    <td>Num. Files</td>
    <td>Date</td>
	<td>Action</td>
  </tr>
  
<?
  $numemails = 0;

  foreach( $uploads AS $upload ):
  
    $numfiles = $FILE->getnumrows( $upload['id'], "upload" );
	
	if( isemail($upload['email']) ) $numemails++;
	
	// if no name is defined (older version), use the subdir value or ID
	if( !isset($upload['name']) || $upload['name']=="" )
	{
	  if( $upload['subdir']=="" )
	    $upload['name'] = "Upload #" . $upload['id'];
	  else
	    $upload['name'] = substr( $upload['subdir'], 0, (strlen($upload['subdir'])-1) );
	}
?>
  
  <tr align="center" class="altsecond" onMouseOver="this.className='altfirst'; this.style.cursor='hand';" onMouseOut="this.className='altsecond'" onClick="window.location.href='viewfiles.php?id=<?=$upload['id']?>&<?=$SID?>'">
    <td width="5"><a href="viewfiles.php?id=<?=$upload['id']?>&<?=$SID?>"><img src="images/folder.gif" border="0"></a></td>
    <td align="left"><b><a href="viewfiles.php?id=<?=$upload['id']?>&<?=$SID?>"><?=$upload['name']?></a></b></td>
	<td><?=$numfiles?> Files</td>
    <td><?=userdate( $upload['uploaded'] )?></td>
	<td width="120"><b><a href="viewfiles.php?id=<?=$upload['id']?>&<?=$SID?>">View</a></b> | <b><a href="delete.php?id=<?=$upload['id']?>&type=upload&<?=$SID?>">Delete</a></b></td>
  </tr>
  
<? endforeach; ?>
  
  <tr class="header">
    <td colspan="5">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  	  <form method="post" action="browser.php">
  	  <input type="hidden" name="action" value="browse">
	  <input type="hidden" name="page" value="<?=$_GET['page']?>">
 	  <? showsession(); ?>
	    <tr>
    	  <td align="left"><input type="text" name="query" size="20" value="<?=$_GET['query']?>">&nbsp;<input type="submit" name="search" value="Search"></td>
		  <td align="right">
		    <? if( $prevpage ): ?><input type="submit" name="prev" value="Previous"><? endif; ?>
			<? if( $nextpage ): ?><input type="submit" name="next" value="Next"><? endif; ?>
		  </td>
		</tr>
	  </form>
	  </table>
	</td>
  </tr>
</table>
	
<?
    showspace();

    if( $numemails>0 ):
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="browser.php">
<input type="hidden" name="action" value="email">
<? showsession(); ?>
  <tr class="header">
    <td colspan="2">Email All Uploaders (<?=$numemails?>)</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
	  <b>Title</b><br>
      The title of the message to send
	</td>
    <td>
      <input type="text" name="title" size="65">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
	  <b>Content</b><br>
      The content of the message to send
	</td>
    <td>
      <textarea name="content" cols="64" rows="10"></textarea>
    </td>
  </tr>
  <tr align="center" class="header">
    <td colspan="2">
      <input type="submit" name="edit" value="Email All Uploaders (<?=$numemails?>)">
    </td>
  </tr>
</form>
</table>

<?
    endif;

  endif;
  
  showfooter($section);
?>