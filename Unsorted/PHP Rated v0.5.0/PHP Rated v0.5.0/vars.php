<?

/*
 * $Id: vars.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./admin/config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(do_settings()) header("Location: index.php?$sn=$sid");
else header("Location: index.php");

/*
 * $Id: vars.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>