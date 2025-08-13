<?

/*
 * $Id: login.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./admin/config.php");
include("$include_path/functions.php");
include("$include_path/session.php");
include("$include_path/common.php");

$sql = "
	select
		*
	from
		$tb_users
	where
		username = '$UN'
	and
		password = password('$PW')
";

$query = sql_query($sql);

if(sql_num_rows($query) == 1){
	if($array = sql_fetch_array($query)){
		$username = $array["username"];
		session_register("username");
		$userid = $array["id"];
		session_register("userid");
	}
}

header("Location: index.php?$sn=$sid");

/*
 * $Id: login.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>