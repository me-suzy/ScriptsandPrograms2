<?
   require "conf/sys.conf";
   require "lib/mysql.lib";
   require "lib/group.lib";
   $db = c();

   include "tpl/top.ihtml";

	if($action == unsubscribe || $action == subscribe)
	{
		if($action == unsubscribe)
		{
			include "src/unsubscribe";
		}
		else
		{
			include "src/subscribe";
		}
	}
	else
	{
   	if($forward == "top100")
		{
			include "src/top100";
		}
   	else
		{
			if($src == "groups")
			{
		     	include "src/index_groups";
   		  	if ($exchange["ppc"]) include "src/index_ppcsites";
   		  	include "src/index_sites";
   		  	if ($exchange["ppc"]) include "src/index_other";

	   	   if($pid == "" || $pid == -1)
		   	{
	   			include "src/top25";
			   }
			}
			else
			{
				include "src/default";
			}
   	}
	}

   include "tpl/bottom.ihtml";
   d($db);
?>
