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

  if( isset($i) )
  {
    include( "in.php" );
  }
  elseif( isset($o) )
  {
    include( "out.php" );
  }
  else
  {
    header( "Location: signin.php" );
  }

?>