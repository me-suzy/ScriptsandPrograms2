<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="JavaScript1.2" src="./js/coolmenus4.js">
/*****************************************************************************
This site uses the coolMenus. You can get it for your own site by
going to http://www.dhtmlcentral.com/projects/coolmenus
******************************************************************************/
</script>
<script type="text/javascript" language="JavaScript1.2" src="./js/cm_addins.js">
</script>
<?php
echo "<script type=\"text/JavaScript\" language=\"JavaScript\">\n";
echo "function findPos(num)\n";
echo "{\n";
echo "	if(bw.ns4)\n";
echo "	{	//Netscape 4\n";
echo "		x = document.layers[\"layerMenu\"+num].pageX;\n";
echo "		y = document.layers[\"layerMenu\"+num].pageY;\n";
echo "	}\n";
echo "	else\n";
echo "	{	//other browsers\n";
echo "		x=0;\n";
echo "		y=0;\n";
echo "		var el,temp;\n";
echo "		el = bw.ie4?document.all[\"divMenu\"+num]:document.getElementById(\"divMenu\"+num);\n";
echo "		if(el.offsetParent)\n";
echo "		{\n";
echo "			temp = el;\n";
echo "			while(temp.offsetParent)\n";
echo "			{	//Looping parent elements to get the offset of them as well\n";
echo "				temp=temp.offsetParent;\n";
echo "				x+=temp.offsetLeft;\n";
echo "				y+=temp.offsetTop;\n";
echo "			}\n";
echo "		}\n";
echo "		x+=el.offsetLeft;\n";
echo "		y+=el.offsetTop;\n";
echo "	}\n";
echo "	//Returning the x and y as an array\n";
echo "	return [x,y];\n";
echo "}\n";
echo "function placeElements(){\n";
for($i=0;$i<count($l_menus);$i++)
{
	if($l_menus[$i][0]["level"]<=$userdata["rights"])
	{
		echo "	pos = findPos($i);\n";
		echo "	oCMenu.m[\"top$i\"].b.moveIt(pos[0],pos[1]);\n";
	}
}
echo "	oCMenu.fromTop = pos[1];\n";
echo "}\n";
echo "</script>\n";
?>