<?

/*
 * $Id: logout.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./admin/config.php");
include("$include_path/functions.php");
include("$include_path/session.php");
include("$include_path/common.php");

session_unregister("userid");
header("Location: index.php?$sn=$sid");

/*
 * $Id: logout.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>