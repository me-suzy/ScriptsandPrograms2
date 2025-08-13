<?php php_track_vars?>
<?php

include "includes/config.inc";
include "includes/php-dbi.inc";
include "includes/functions.inc";
include "includes/validate.inc";
include "includes/connect.inc";

load_user_preferences ();
load_user_layers ();

if ( $id > 0 ) {
  $res = dbi_query ( "SELECT cal_date FROM webcal_entry WHERE cal_id = $id" );
  if ( $res ) {
    // date format is 19991231
    $row = dbi_fetch_row ( $res );
    $thisdate = $row[0];
  }
  dbi_query ( "DELETE FROM webcal_entry WHERE cal_id = $id" );
  dbi_query ( "DELETE FROM webcal_entry_user WHERE cal_id = $id" );
  dbi_query ( "DELETE FROM webcal_entry_repeats WHERE cal_id = $id" );
}

do_redirect ( "$STARTVIEW.php" .
  ( $thisdate > 0 ? "?date=$thisdate" : "" ) );
?>
