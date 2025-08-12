<?php
function make_menu()
{
	global $l_menus, $userdata, $url_faqengine;
	
	echo "<script>\n";
	echo "oCMenu=new makeCM(\"oCMenu\");\n";
	echo "oCMenu.pxBetween=0\n";
	echo "oCMenu.fromLeft=0\n";
	echo "oCMenu.fromTop=0\n";
	echo "oCMenu.rows=1\n";
	echo "oCMenu.menuPlacement=0\n";
	echo "oCMenu.offlineRoot=\"file:///C|/Inetpub/wwwroot/faqengine/admin\"\n";
	echo "oCMenu.onlineRoot=\"$url_faqengine/admin/\"\n";
	echo "oCMenu.resizeCheck=1\n";
	echo "oCMenu.wait=1000\n";
	echo "oCMenu.fillImg=\"gfx/space.gif\"\n";
	echo "oCMenu.zIndex=0\n";
	echo "//Background bar properties\n";
	echo "oCMenu.useBar=0\n";
	echo "oCMenu.level[0]=new cm_makeLevel()\n";
	echo "oCMenu.level[0].width=\"8%\"\n";
	echo "oCMenu.level[0].height=35\n";
	echo "oCMenu.level[0].regClass=\"clLevel0\"\n";
	echo "oCMenu.level[0].overClass=\"clLevel0over\"\n";
	echo "oCMenu.level[0].borderX=0\n";
	echo "oCMenu.level[0].borderY=0\n";
	echo "oCMenu.level[0].borderClass=\"clLevel0border\"\n";
	echo "oCMenu.level[0].offsetX=0\n";
	echo "oCMenu.level[0].offsetY=0\n";
	echo "oCMenu.level[0].rows=0\n";
	echo "oCMenu.level[0].arrow=0\n";
	echo "oCMenu.level[0].arrowWidth=0\n";
	echo "oCMenu.level[0].arrowHeight=0\n";
	echo "oCMenu.level[0].align=\"bottom\"\n";
	echo "oCMenu.level[1]=new cm_makeLevel()\n";
	echo "oCMenu.level[1].width=\"20%\"\n";
	echo "oCMenu.level[1].height=35\n";
	echo "oCMenu.level[1].regClass=\"clLevel1\"\n";
	echo "oCMenu.level[1].overClass=\"clLevel1over\"\n";
	echo "oCMenu.level[1].borderX=1\n";
	echo "oCMenu.level[1].borderY=1\n";
	echo "oCMenu.level[1].borderClass=\"clLevel1border\"\n";
	echo "oCMenu.level[1].offsetX=25\n";
	echo "oCMenu.level[1].offsetY=-10\n";
	echo "oCMenu.level[1].rows=0\n";
	echo "oCMenu.level[1].arrow=\"gfx/menu_arrow.gif\"\n";
	echo "oCMenu.level[1].arrowWidth=10\n";
	echo "oCMenu.level[1].arrowHeight=10\n";
	echo "oCMenu.level[1].align=\"bottom\"\n";
	echo "oCMenu.level[2]=new cm_makeLevel()\n";
	echo "oCMenu.level[2].width=\"20%\"\n";
	echo "oCMenu.level[2].height=35\n";
	echo "oCMenu.level[2].regClass=\"clLevel2\"\n";
	echo "oCMenu.level[2].overClass=\"clLevel2over\"\n";
	echo "oCMenu.level[2].borderX=1\n";
	echo "oCMenu.level[2].borderY=1\n";
	echo "oCMenu.level[2].borderClass=\"clLevel2border\"\n";
	echo "oCMenu.level[2].offsetX=0\n";
	echo "oCMenu.level[2].offsetY=0\n";
	echo "oCMenu.level[2].rows=0\n";
	echo "oCMenu.level[2].arrow=0\n";
	echo "oCMenu.level[2].arrowWidth=0\n";
	echo "oCMenu.level[2].arrowHeight=0\n";
	echo "oCMenu.level[2].align=\"right\"\n";
	for($i=0;$i<count($l_menus);$i++)
	{
		if($l_menus[$i][0]["level"]<=$userdata["rights"])
		{
			echo "oCMenu.makeMenu('top$i','','".$l_menus[$i][0]["entry"]."','".do_url_session($l_menus[$i][0]["url"])."','')\n";
			for($j=1;$j<count($l_menus[$i]);$j++)
			{
				if(faqe_array_key_exists($l_menus[$i][$j],"submenu"))
				{
					$menuentries=$l_menus[$i][$j]["entries"];
					if($menuentries[0]["level"]<=$userdata["rights"])
						echo "  oCMenu.makeMenu('top2_$i".($j-1)."','top$i','".$menuentries[0]["entry"]."','".do_url_session($menuentries[0]["url"])."')\n";
					for($k=1;$k<count($menuentries);$k++)
					{
						if($menuentries[$k]["level"]<=$userdata["rights"])
							echo "    oCMenu.makeMenu('sub2_$i".($j-1).($k-1)."','top2_$i".($j-1)."','".$menuentries[$k]["entry"]."','".do_url_session($menuentries[$k]["url"])."')\n";
					}
				}
				else if($l_menus[$i][$j]["level"]<=$userdata["rights"])
					echo "  oCMenu.makeMenu('sub$i".($j-1)."','top$i','".$l_menus[$i][$j]["entry"]."','".do_url_session($l_menus[$i][$j]["url"])."')\n";
			}
		}
	}
	echo "oCMenu.construct()\n";
	echo "placeElements()\n";
	echo "oCMenu.onafterresize=\"placeElements()\"\n";
	echo "</script>\n";
}
?>