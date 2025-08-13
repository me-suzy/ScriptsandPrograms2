<? 
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.0                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : Stive [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2002
/////////////////////////////////////////////////////////////
include( "initialize.php"); 
?>

  function isemail( string )
  {
    var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
    return pattern.test( string );
  }

  function checkfields( form )
  {
    var missingfields = "";
<? 
  $FIELD->sortdata( "order", "asc" );

  $fields = $FIELD->get();
 
  foreach( $fields AS $field ):
?>
    switch( true )
    {
	
<? if( $field['required'] ): ?>

      case( form.f<?=$field['id']?>.value=="" ):
	    missingfields += "\n<?=$field['name']?> (Missing field)";
        break;

<? endif; if( $CONF->getval("emailfield") == $field['id'] ): ?>
		
	  case( !isemail(form.f<?=$field['id']?>.value) ):
	    missingfields += "\n<?=$field['name']?> (Invalid address)";
		break;

<? endif; if( $field['type']=="textarea" ): ?>

	  case( form.f<?=$field['id']?>.value.length > <?=$field['maxchars']?> ):
	    missingfields += "\n<?=$field['name']?> (Max chars: <?=$field['maxchars']?>)";
		break;
		
<? endif; if( isset($field['minchars']) && $field['minchars']!="" ): ?>

	  case( form.f<?=$field['id']?>.value.length < <?=$field['minchars']?> && form.f<?=$field['id']?>.value!="" ):
	    missingfields += "\n<?=$field['name']?> (Min chars: <?=$field['minchars']?>)";
		break;

<? endif; ?>
	
	  default:
	    break;
    }
	
<? endforeach; ?>

    if( missingfields != "" )
    {
      alert( "The following fields are incorrect:\n" + missingfields ); return false;
    }

    return true;
  }