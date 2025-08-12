<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="javascript">
// constants
var initX       = 85; // x-coordinate of top left corner of dropdown menu
var initY       = 92; // y-coordinate of top left corner of dropdown menu
var backColor   = ''; // the background color of dropdown menu, set empty '' for transparent
var borderColor = 'black'; // the color of dropdown menu border
var borderSize  = '1'; // the width of dropdown menu border
var itemHeight  = 20;
var xOverlap    = 5;
var yOverlap    = 10;
//

// Don't change these parameters
var delay        = 500; /////
var menuElement  = new Array ();
var usedWidth    = 0;
var numOfMenus   = 0;
/// ----------------------------

var menuContent     = new Array ();

<?php
for($i=0;$i<count($l_menus);$i++)
{
	echo "menuContent [$i] = new Array (\n";
	echo "-1, // the id of parent menu, -1 if this is a first level menu\n";
	echo "-1, // the number of line in parent menu, -1 if this is a first level menu\n";
	echo "83, // the width of current menu list\n";
	echo "-1, // x coordinate (absolute) of left corner of this menu list, -1 if the coordinate is defined from parent x-coordinate\n";
	echo "-1, // y coordinate (absolute) of left corner of this menu list, -1 if the coordinate is defined from parent y-coordinate\n";
	echo "new Array (";
	for($j=1;$j<count($l_menus[$i]);$j++)
	{
		if($l_menus[$i][$j]["level"]<=$userdata["rights"])
		{
			if($j>1)
				echo ",\n";
			$menuurl=do_url_session($l_menus[$i][$j]["url"]);
			echo "'".$l_menus[$i][$j]["entry"]."', '".$menuurl."'";
		}
	}
	echo "\n));\n";
}
?>
</script>
<script type="text/javascript" language="JavaScript" src="./js/menu.js"></script>
