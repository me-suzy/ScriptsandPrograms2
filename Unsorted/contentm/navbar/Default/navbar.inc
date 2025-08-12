<?php
	function Recurse ($id)
		{
		global $IW;
		$sublinks=$IW->query("select * from ".IWARE_DOCS." where parent_id='".$id."' and is_hidden='0' order by nav_order");
		for($j=0;$j<$IW->countResult($sublinks);$j++)
			{
			echo "<dir>";
			echo "<a href=\"?D=".$IW->result($sublinks,$j,"id")."\" class=\"navbar\">".$IW->result($sublinks,$j,"link_text")."</a><br />";
			Recurse ($IW->result($sublinks,$j,"id"));
			echo "</dir>";
			}
		$IW->freeResult($sublinks);	
		}

	function TopLevel ()
		{
		global $IW;
		$result=$IW->query("select * from ".IWARE_DOCS." where parent_id='0' and is_hidden='0' order by nav_order");
		for($i=0;$i<$IW->countResult($result);$i++)
			{
			echo "<a href=\"?D=".$IW->result($result,$i,"id")."\" class=\"navbar\">".$IW->result($result,$i,"link_text")."</a><br />";
			if($IW->Nav_HasSubNav($IW->result($result,$i,"id"))){Recurse ($IW->result($result,$i,"id"));}
			}
		$IW->freeResult($result);
		}

	TopLevel ();
?>