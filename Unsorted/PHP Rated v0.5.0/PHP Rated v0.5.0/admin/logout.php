<?

/*
 * $Id: logout.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");
session_destroy();
header("Location: index.php");

/*
 * $Id: logout.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>