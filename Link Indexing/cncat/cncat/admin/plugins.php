<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/
error_reporting(E_ALL & ~E_NOTICE);
$ADLINK="";

include "auth.php";
include "_top.php";

print "<h1>".$LANG["plugins"]."</h1>";

print "<UL>\n";
$d=dir("./plugins/");
while ($e=$d->read()) {
	if (is_file("./plugins/".$e) && strpos($e,".php")) {
		$fr=fopen("./plugins/".$e,"rt");
		while (!feof($fr)) {
			$s=fgets($fr,256);
			if (ereg("/\* PLUGIN: (.*); \*/.*",$s,$regs))
				print "<LI><a href=plugins/".$e.">".$regs[1]."</a>\n";
			}
		}
	}

print "</UL>\n";
print "<P>".$LANG["plugin_example"]."</P>";

include "_bottom.php";
?>
