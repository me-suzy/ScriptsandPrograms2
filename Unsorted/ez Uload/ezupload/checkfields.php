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
  include( "initialize.php");
  
  include( "lang/" . $CONF->getval("language_pack") );
?>

  function isemail( string )
  {
    var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
    return pattern.test( string );
  }
  
  function checkbox_one_checked( form, fname )
  {
    var el, grp, e=0;
	
    while( el = form.elements[e++] )
    {
	  if( el.type=='checkbox' && el.name && el.name==fname )
	  {
	    grp = form[el.name];

	    for( var i=0; i<grp.length; ++i ) if( grp[i].checked ) break;

        if (i == grp.length) return false;
		
		e += grp.length - 1;
	  }
	}

	return true;
  }

  function radio_one_checked( form, fname )
  {
    for( i=0; i<form.f9.length; i++ )
    {
      if( form.f9[i].checked==true ) return true;
    }

    return false;
  }
  
  function getextension( filename )
  {
    filename = filename.toLowerCase();
	var farray = filename.split( "." );
	
	if( farray.length==1 ) return false; 
	
	return farray[ farray.length-1 ];
  }
  
  function checkextension( extension )
  {
    if( !extension ) return false;
  
    <? if( $CONF->getval("extmode")=="all" ): ?>
	
	return true;
	
	<?
	  else:
	
	    $extarray = explode( " ", trim($CONF->getval("extensions")) );
	    $extstring = "\"" . implode( "\", \"", $extarray ) . "\"";
	?>
  
    var extlist = new Array( <?=$extstring?> );
  
    <? 
	    if( $CONF->getval("extmode")=="except" ):
	?>
  
	for( var i=0; i<extlist.length; i++ )
	{
	  if( extlist[i]==extension ) return false;
	}
	
	return true;
	
	<?  elseif( $CONF->getval("extmode")=="only" ): ?>

	for( var i=0; i<extlist.length; i++ )
	{
	  if( extlist[i]==extension ) return true;
	}
	
	return false;
	
	<?
	    endif; 
	
	  endif; 
	?>
  }

  function checkfields( form )
  {
    var missingfields = "";

<? 
  if( $CONF->getval("js_detection") ):

    $FIELD->sortdata( "order", "asc" );

    $fields = $FIELD->get();
 
    foreach( $fields AS $field ):
  
      if( $CONF->getval("emailfield")==$field['id'] ): 
?>

	if( form.f<?=$field['id']?>.value!="" )
	{	
	  if( !isemail(form.f<?=$field['id']?>.value) )
	  {
	    missingfields += "\n- <?=$field['name']?> (<?=$_LANG['invalid_address']?>)";
	  }
    }
<?
	    if( $field['required'] ):
?>
	else
	{
	  missingfields += "\n- <?=$field['name']?> (<?=$_LANG['missing_field']?>)";
	}
	
<?
        endif;
		
      elseif( $field['type']=="checkbox" && $field['required'] ):
?>

	if( !checkbox_one_checked(form, 'f<?=$field['id']?>[]') )
	{
	  missingfields += "\n- <?=$field['name']?> (<?=$_LANG['no_option_checked']?>)";
	}

<?
      elseif( $field['type']=="radio" && $field['required'] ):
?>

	if( !radio_one_checked(form, 'f<?=$field['id']?>') )
	{
	  missingfields += "\n- <?=$field['name']?> (<?=$_LANG['no_option_checked']?>)";
	}

<?
	  elseif( $field['type']=="file" ):
?>

	if( form.f<?=$field['id']?>.value != "" )
	{
	  extension = getextension( form.f<?=$field['id']?>.value );

	  if( !checkextension( extension ) )
	  {
	    missingfields += "\n- <?=$field['name']?> (<?=$_LANG['invalid_extension']?>)";
	  }
	}
<?
		if( $field['required'] ):
?>
	else
	{
	  missingfields += "\n- <?=$field['name']?> (<?=$_LANG['missing_field']?>)";
	}
	
<?
		endif;

	  elseif( isset($field['minchars']) && $field['minchars']!="" && $field['minchars']!=0 ):
?>

	if( form.f<?=$field['id']?>.value!="" )
	{
	  if( form.f<?=$field['id']?>.value.length < <?=$field['minchars']?> )
	  {
	    missingfields += "\n- <?=$field['name']?> (<?=$_LANG['min_chars']?>: <?=$field['minchars']?>)";
	  }
<?
		if( $field['type']=="textarea" && $field['maxchars']!="" ):
?>
	  else if( form.f<?=$field['id']?>.value.length > <?=$field['maxchars']?> )
	  {
	    missingfields += "\n- <?=$field['name']?> (<?=$_LANG['max_chars']?>: <?=$field['maxchars']?>)";
	  }
<?
		endif;
?>
	}
<?
		if( $field['required'] ):
?>
	else
    {
	  missingfields += "\n- <?=$field['name']?> (<?=$_LANG['missing_field']?>)";
	}
	
<?
        endif;
		
      elseif( $field['required'] ):
?>

    if( form.f<?=$field['id']?>.value=="" )
	{
	  missingfields += "\n- <?=$field['name']?> (<?=$_LANG['missing_field']?>)";
	}

<?
	  endif;
	  
    endforeach; 

  endif;
?>

    if( missingfields != "" )
    {
      alert( "<?=$_LANG['js_error']?>:\n" + missingfields );
	  return false;
    }
    else
	{
	  var accepted = true;
	
	  <? 
	    if( $CONF->getval("display_warning") ):
	  
	      //$warning_message = $_LANG['large_files_warning'];
	      $warning_message = wordwrapnew( $_LANG['large_files_warning'], 40, "", '\n' );
	  ?>
	  
	    accepted = confirm( "<?=$warning_message?>" );
	  
	  <? endif; ?>
	  
	  if( accepted == true )
	  {
	    form.submit.disabled = true;
      }
	  
	  return accepted;
	}
  }