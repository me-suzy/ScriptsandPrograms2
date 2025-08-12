/*******************************************************
*	(c) Ger Versluis 2000 version 13.10 August 1, 2004 *
*	You may use this script on non commercial sites.   *
*	www.burmees.nl/menu                                *
*	You may remove all comments for faster loading     *
*******************************************************/

	var NoOffFirstLineMenus=8;			// Number of main menu  items
										// Colorvariables:
										// Color variables take HTML predefined color names or "#rrggbb" strings
										// For transparency make colors and border color ""
	var LowBgColor="#ECE9D8";			// Background color when mouse is not over
	var HighBgColor="#93A070";			// Background color when mouse is over
	var FontLowColor="#000000";			// Font color when mouse is not over
	var FontHighColor="#FFFFFF";		// Font color when mouse is over
	var BorderColor="#000000";			// Border color
	var BorderWidthMain=1;				// Border width main items
	var BorderWidthSub=1;				// Border width sub items
 	var BorderBtwnMain=0;				// Border width between elements main items
	var BorderBtwnSub=0;				// Border width between elements sub items
	var FontFamily="arial";				// Font family menu items
	var FontSize=11;					// Font size menu items
	var FontBold=1;						// Bold menu items 1 or 0
	var FontItalic=0;					// Italic menu items 1 or 0
	var MenuTextCentered="left";		// Item text position left, center or right
	var MenuCentered="left";			// Menu horizontal position can be: left, center, right
	var MenuVerticalCentered="bottom";	// Menu vertical position top, middle,bottom or static
	var ChildOverlap=1;					// horizontal overlap child/ parent
	var ChildVerticalOverlap=0;			// vertical overlap child/ parent
	var StartTop=0;						// Menu offset x coordinate
	var StartLeft=-1;					// Menu offset y coordinate
	var VerCorrect=0;					// Multiple frames y correction
	var HorCorrect=0;					// Multiple frames x correction
	var DistFrmFrameBrdr=2;				// Distance between main menu and frame border
	var LeftPaddng=5;					// Left padding
	var TopPaddng=2;					// Top padding. If set to -1 text is vertically centered
	var FirstLineHorizontal=1;			// Number defines to which level the menu must unfold horizontal; 0 is all vertical
	var MenuFramesVertical=0;			// Frames in cols or rows 1 or 0
	var DissapearDelay=500;				// delay before menu folds in
	var UnfoldDelay=100;				// delay before sub unfolds	
	var TakeOverBgColor=1;				// Menu frame takes over background color subiten frame
	var FirstLineFrame="bar";			// Frame where first level appears
	var SecLineFrame="left";			// Frame where sub levels appear
	var DocTargetFrame="left";			// Frame where target documents appear
	var TargetLoc="left";				// span id for relative positioning
	var MenuWrap=1;						// enables/ disables menu wrap 1 or 0
	var RightToLeft=0;					// enables/ disables right to left unfold 1 or 0
	var BottomUp=0;						// enables/ disables Bottom up unfold 1 or 0
	var UnfoldsOnClick=1;				// Level 1 unfolds onclick/ onmouseover
	var BaseHref="";					// BaseHref lets you specify the root directory for relative links. 

	var Arrws=[];
						// Arrow source, width and height.
						// If arrow images are not needed keep source ""

	var MenuUsesFrames=1;				// MenuUsesFrames is only 0 when Main menu, submenus,
										// document targets and script are in the same frame.
										// In all other cases it must be 1

	var RememberStatus=0;				// RememberStatus: When set to 1, menu unfolds to the presetted menu item. 
										// When set to 2 only the relevant main item stays highligthed
										// The preset is done by setting a variable in the head section of the target document.
										// <head>
										//	<script type="text/javascript">var SetMenu="2_2_1";</script>
										// </head>
										// 2_2_1 represents the menu item Menu2_2_1=new Array(.......

										// Below some pretty useless effects, since only IE6+ supports them
										// I provided 3 effects: MenuSlide, MenuShadow and MenuOpacity
										// If you don't need MenuSlide just leave in the line var MenuSlide="";
										// delete the other MenuSlide statements
										// In general leave the MenuSlide you need in and delete the others.
										// Above is also valid for MenuShadow and MenuOpacity
										// You can also use other effects by specifying another filter for MenuShadow and MenuOpacity.
										// You can add more filters by concanating the strings
	var BuildOnDemand=0;				// 1/0 When set to 1 the sub menus are build when the parent is moused over
	var BgImgLeftOffset=5;				// Only relevant when bg image is used as rollover
	var ScaleMenu=0;					// 1/0 When set to 0 Menu scales with browser text size setting

	var HooverBold=0;					// 1 or 0
	var HooverItalic=0;					// 1 or 0
	var HooverUnderLine=0;				// 1 or 0
	var HooverTextSize=0;				// 0=off, number is font size difference on hoover
	var HooverVariant=0;				// 1 or 0

	var MenuSlide="";
	var MenuSlide="";
	var MenuSlide="";

	var MenuShadow="";
	var MenuShadow="";
	var MenuShadow="";

	var MenuOpacity="";
	var MenuOpacity="";

	function BeforeStart(){return}
	function AfterBuild(){return}
	function BeforeFirstOpen(){return}
	function AfterCloseAll(){return}

// Menu tree:
// MenuX=new Array("ItemText","Link","background image",number of sub elements,height,width,"bgcolor","bghighcolor",
//	"fontcolor","fonthighcolor","bordercolor","fontfamily",fontsize,fontbold,fontitalic,"textalign","statustext");
// Color and font variables defined in the menu tree take precedence over the global variables
// Fontsize, fontbold and fontitalic are ignored when set to -1.
// For rollover images ItemText or background image format is:  "rollover?"+BaseHref+"Image1.jpg?"+BaseHref+"Image2.jpg" 


Menu1=new Array("Files","","",11,20,38,"","","","","","",-1,-1,-1,"","Left Panel");
	Menu1_1=new Array("Change Attributes","javascript:parent.extra.doAction('chmod');","",0,20,115,"","","","","","",-1,-1,-1,"","Change Attributes");
	Menu1_2=new Array("Pack","javascript://parent.extra.doAction('pack');","",0,20,115,"","","","","","",-1,-1,-1,"","Pack");
	Menu1_3=new Array("UnPack","javascript://parent.extra.doAction('unpack');","",0,20,115,"","","","","","",-1,-1,-1,"","UnPack");
	Menu1_4=new Array("Properties","javascript://parent.extra.doAction('properties');","",0,20,115,"","","","","","",-1,-1,-1,"","Properties");
	Menu1_5=new Array("Print","javascript://parent.extra.doAction('print');","",0,20,115,"","","","","","",-1,-1,-1,"","Print");
	Menu1_6=new Array("<hr>","","",0,20,100,"","#ECE9D8","","","","",-1,-1,-1,"","");
	Menu1_7=new Array("Split File","javascript://parent.extra.doAction('split');","",0,20,115,"","","","","","",-1,-1,-1,"","Split files");
	Menu1_8=new Array("Combine Files","javascript://parent.extra.doAction('combine');","",0,20,115,"","","","","","",-1,-1,-1,"","Combine files");
	Menu1_9=new Array("<hr>","","",0,20,100,"","#ECE9D8","","","","",-1,-1,-1,"","");
	Menu1_10=new Array("Logout","javascript:parent.location='index.php?logout=1'","",0,20,115,"","","","","","",-1,-1,-1,"","Quit");
	Menu1_11=new Array("Quit","javascript:parent.extra.doAction('quit');","",0,20,115,"","","","","","",-1,-1,-1,"","Quit");

Menu2=new Array("Mark","","",4,20,40,"","","","","","",-1,-1,-1,"","Mark");
	Menu2_1=new Array("Select all","javascript:parent.curent_panel.SetChecked(parent.curent_panel.isChecked1, 'files[]');parent.curent_panel.isChecked1++;parent.curent_panel.isChecked1 %= 2;javascript:parent.curent_panel.SetChecked(parent.curent_panel.isChecked2, 'dirs[]');parent.curent_panel.isChecked2++;parent.curent_panel.isChecked2 %= 2;","",0,20,100,"","","","","","",-1,-1,-1,"","Select all");
	Menu2_2=new Array("Unselect all","javascript:parent.curent_panel.SetChecked(parent.curent_panel.isChecked1, 'files[]');parent.curent_panel.isChecked1++;parent.curent_panel.isChecked1 %= 2;javascript:parent.curent_panel.SetChecked(parent.curent_panel.isChecked2, 'dirs[]');parent.curent_panel.isChecked2++;parent.curent_panel.isChecked2 %= 2;","",0,20,100,"","","","","","",-1,-1,-1,"","Unselect all");
	Menu2_3=new Array("Select folders","javascript:parent.curent_panel.SetChecked(parent.curent_panel.isChecked2, 'dirs[]');parent.curent_panel.isChecked2++;parent.curent_panel.isChecked2 %= 2;","",0,20,100,"","","","","","",-1,-1,-1,"","Select folders");
	Menu2_4=new Array("Select files","javascript:parent.curent_panel.SetChecked(parent.curent_panel.isChecked1, 'files[]');parent.curent_panel.isChecked1++;parent.curent_panel.isChecked1 %= 2;","",0,20,100,"","","","","","",-1,-1,-1,"","Select files");

Menu3=new Array("Commands","","",8,20,75,"","","","","","",-1,-1,-1,"","Commands");
	Menu3_1=new Array("Search","","",0,20,120,"","","","","","",-1,-1,-1,"","Search");
	Menu3_2=new Array("System information","","",0,20,120,"","","","","","",-1,-1,-1,"","System information");
	Menu3_3=new Array("Go back","javascript:history.go(-1);","",0,20,120,"","","","","","",-1,-1,-1,"","Go back");
	Menu3_4=new Array("<hr>","","",0,20,120,"","#ECE9D8","","","","",-1,-1,-1,"","");
	Menu3_5=new Array("Run Command","javascript:parent.extra.doAction('copy')","",0,20,120,"","","","","","",-1,-1,-1,"","Run command");
	Menu3_6=new Array("<hr>","","",0,20,120,"","#ECE9D8","","","","",-1,-1,-1,"","");
	Menu3_7=new Array("Target = Source","","",0,20,120,"","","","","","",-1,-1,-1,"","Target = Source");
	Menu3_8=new Array("Switch panels","","",0,20,120,"","","","","","",-1,-1,-1,"","Source < > Target");
Menu4=new Array("Show","","",11,20,40,"","","","","","",-1,-1,-1,"","Show");
	Menu4_1=new Array("Show all","","",0,20,125,"","","","","","",-1,-1,-1,"","Show all");
	Menu4_2=new Array("Show custom","","",0,20,125,"","","","","","",-1,-1,-1,"","Show custom");
	Menu4_3=new Array("<hr>","","",0,20,125,"","#ECE9D8","","","","",-1,-1,-1,"","");
	Menu4_4=new Array("Refresh left panel","javascript:parent.left.location.reload();","",0,20,125,"","","","","","",-1,-1,-1,"","Show all");
	Menu4_5=new Array("Refresh right panel","javascript:parent.right.location.reload();","",0,20,125,"","","","","","",-1,-1,-1,"","Show custom");
	Menu4_6=new Array("<hr>","","",0,20,125,"","#ECE9D8","","","","",-1,-1,-1,"","");
	Menu4_7=new Array("Sort by Name","","",0,20,125,"","","","","","",-1,-1,-1,"","Sort by Name");
	Menu4_8=new Array("Sort by Extension","","",0,20,125,"","","","","","",-1,-1,-1,"","Sort by Extension");
	Menu4_9=new Array("Sort by Size","","",0,20,125,"","","","","","",-1,-1,-1,"","Sort by Size");
	Menu4_10=new Array("Sort by Date","","",0,20,125,"","","","","","",-1,-1,-1,"","Sort by Date");
	Menu4_11=new Array("Sort by Attributes","","",0,20,125,"","","","","","",-1,-1,-1,"","Sort by Attributes");
Menu5=new Array("Configuration","","",2,20,90,"","","","","","",-1,-1,-1,"","Configuration");
	Menu5_1=new Array("User Management","usermanagement.php?panel=left","",0,20,110,"","","","","","",-1,-1,-1,"","User Management");
	Menu5_2=new Array("Script configure","configure.php?panel=left","",0,20,110,"","","","","","",-1,-1,-1,"","User Management");
Menu6=new Array("Plugin","","",2,20,50,"","","","","","",-1,-1,-1,"","Plugin");
	Menu6_1=new Array("File","javascript:load_plugin('files');","",0,20,100,"","","","","","",-1,-1,-1,"","Files");
	Menu6_2=new Array("FTP","javascript:load_plugin('ftp');","",0,20,100,"","","","","","",-1,-1,-1,"","FTP");
Menu7=new Array("Help","","",5,20,40,"","","","","","",-1,-1,-1,"","Help");
	Menu7_1=new Array("Help","javascript:help();","",0,20,100,"","","","","","",-1,-1,-1,"","Help");
	Menu7_2=new Array("<hr>","","",0,20,100,"","#ECE9D8","","","","",-1,-1,-1,"","");
	Menu7_3=new Array('<a target="_blank" href="http://www.protung.ro"><span style="text-decoration: none; color: #000000;">Visit PHPWC Site</span></a>',"","",0,20,100,"","","","","","",-1,-1,-1,"","Visit PHP Web Commander web site");
	Menu7_4=new Array("<hr>","","",0,20,100,"","#ECE9D8","","","","",-1,-1,-1,"","");
	Menu7_5=new Array("About","javascript:about();","",0,20,100,"","","","","","",-1,-1,-1,"","About");
Menu8=new Array("","","",0,20,1000,"","#ECE9D8","","","","",-1,-1,-1,"","");