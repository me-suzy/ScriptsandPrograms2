<?

require("config.php");
require("functions.php");

if ($action == "rand")
{
	global $id;
	random_site();
}

if ($action == "next")
{
	global $id;
	go_next($id);
}

if ($action == "prev")
{
	global $id;
	go_prev($id);
}

if ($action == "all")
{
	list_all();
}

?>