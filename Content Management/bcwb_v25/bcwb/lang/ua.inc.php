<?PHP
// vim: set expandtab tabstop=4 shiftwidth=4: 
// +----------------------------------------------------------------------+
// | BCWB: Business Card Web Builder                                      |
// +----------------------------------------------------------------------+
// | Author:  Dmitry Sheiko <sheiko@cmsdevelopment.com>	                  |
// | Copyright (c) 2004 Dmitry Sheiko                                     |
// | http://bcwb.cmsdevelopment.com                                       |
// +----------------------------------------------------------------------+
// | This source file is free software; you can redistribute it and/or    |
// | modify it under the terms of the GNU Lesser General Public           |
// | License as published by the Free Software Foundation; either         |
// | version 2.1 of the License, or (at your option) any later version.   |
// |                                                                      |
// | This source file is distributed in the hope that it will be useful,  |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU    |
// | Lesser General Public License for more details.                      |
// +----------------------------------------------------------------------+
// Release: 23.02.04 (d/m/y)
// $Id$

$default_charset="UTF-8";


$lang=array(
	"Icon" => "Icon",
	"CreateIcon" => "Create icon",
	"Width" => "Width",
	"Height" => "Height",

	"Need_set_permissions" => "Òðåáóåòñÿ íàçíà÷èòü àòðèáóòû ôàéëàì/ïàïêàì",
	"Run" => "Âûïîëíèòü",
	"Archive" => "Àðõèâ",
	"Moved" => "Ïåðåìåùåíû",
	"Restore_backup" => "Âîññòàíîâèòü ðåçåðâíóþ êîïèþ",
	"Create_backup" => "Ñîçäàòü ðåçåðâíóþ êîïèþ",
	"Need_authorization" => "Òðåáóåòñÿ àâòîðèçàöèÿ",
	"System_instaled" => "Ñèñòåìà áûëà óñïåøíî èíñòàëèðîâàííà",
	"Reports" => "Îò÷åòû",
	"Time" => "Âðåìÿ",
	"Date" => "Äàòà",
	"Reffer" => "Ññûëàþùèåñÿ ñòðàíèöû",	
	"Visitor" => "Ïîñåòèòåëü",
	"Statistic" => "Ñòàòèñòèêà",
	"Status" => "Ñîñòîÿíèå",
	"Installations" => "Óñòàíîâêè",
	"Enable" => "Åñòü",
	"Disable" => "Íåò (ðåêîìåíäóåòñÿ)",
	"URI" => "URI (HTTP-àäðåñ)",
	"Admin_area_folder" => "Ïàïêà äëÿ îáëàñòè àäìèíèñòðèðîâàíèÿ",
	


  "Save" => "Çáåð³ãòè",

  "Cancel" => "Â³äì³íèòè",

  "Edit" => "Ðåäàêòóâàòè",

  "Edit_page" => "Ðåäàêòóâàòè ñòîð³íêó",

  "Delete" => "Âèäàëèòè",

  "View_page" => "Ïðîãëÿäàòè ñòîð³íêó",

  "Structure" => "Ñòðóêòóðà",
  "Add_item" => "Äîáàâèòè ðîçä³ë ",

  "Create_subitem" => "Ñòâîðèòè ï³äðîçä³ë ",

  "Logout" => "Âèõ³ä ",

  "Template_select" => "Øàáëîí",
  "Helpdesk_window" => "Â³êíî ³íôîðìàö³éíî¿ äîïîìîãè ",

  "Close_window" => "Çàêðèòè â³êíî ",

  "NoUrl" => "Ïîòð³áíî çàïîâíèòè ïîëå URL ",

  "You_are_sure" => "Âè âïåâíåí³ ",

  "Page_not_found" => "Íåìîæëèâî íàéòè ñòîð³íêó ",

  "Page_not_found_desc" => "Ìîæëèâî öÿ ñòîð³íêà áóëà ïåðå³ìåíîâàíà, àáî âîíà òèì÷àñîâî íåäîñòóïíà.",

  "Authorization_in_admin_area" => "Àâòîðèçàö³ÿ â îáëàñò³ àäì³í³ñòðóâàííÿ ",

  "Insert_Picture" => "Âñòàâèòè çîáðàæåííÿ ",

  "Browse_Picture_Source" => "Âèáðàòè ôàéë çîáðàæåííÿ ",

  "Preview" => "Ïðîãëÿäàííÿ ",

  "Alternate_Text" => "Ñóïðîâîäæóâàëüíèé òåêñò ",

  "Layout" => "Ðîçì³ùåííÿ",
  "Alignment" => "Âèð³âíþâàííÿ ",

  "Middle" => "Ñåðåäèíà ",
  "Bottom" => "Âíèç",
  "Top" => "Ââåðõ",
  "Right" => "Ïðàâî",
  "Left" => " Ë³âî ",

  "Image" => "Çîáðàæåííÿ ",

  "Font" => "Øðèôò",
  "Justify" => "Öåíòðóâàííÿ ",

  "Can_not_save_uploaded_file" => "Íå âäàëîñü çàãðóçèòè ôàéë ",

  "Border_Thickness" => "Òîâùèíà îáâîäêè ",

  "Spacing" => "Â³äñòóïè ",

  "Horizontal" => "Ãîðèçîíòàëüíèé ",

  "Vertical" => "Âåðòèêàëüíèé ",

  "Cancel" => "Â³äì³íà ",

  "Insert_Picture" => "Âñòàâèòè çîáðàæåííÿ ",

  "Insert_File" => "Âñòàâèòè ôàéë ",

  "cleanHTML" => "Î÷èñòèòè  HTML ",

  "Indent" => "Â³äñòóï",
  "List" => "Ñïèñîê",
  "File" => "Ôàéë",
  "Page" => "Ñòîð³íêà ",

  "Show" => "Â³äîáðàæàòè ",

  "Link" => "Ïîñèëàííÿ",
  "Insert_Link" => "Âñòàâèòè ïîñèëàííÿ",
  "Link" => "Ïîñèëàííÿ ",
  "Page_name" => "Íàçâà ñòîð³íêè ",
  "Adding_item" => "Äîäàâàííÿ ðîçä³ëó ",
		"send_message" => "Ñîîáùåíèå óñïåøíî äîñòàâëåíî",
		"no_send_message" => "Íå óäàëîñü îòïðàâèòü ñîîáùåíèå",


  "Creating_subitem" => "Ñòâîðåííÿ âêëàäåíîãî ï³äðîçä³ëó",
  "Is_it_necessary_fill_fileld" => "Âàðòî çàïîâíèòè ïîëÿ âëàñòèâîñòåé ñòîð³íêè, çáåðåãòè çì³íè ³ ïåðåéòè äî ðåäàãóâàííÿ íîâî¿ ñòîð³íêè.",
  "XHTML_error" => "Êîíâåðòåð HTML2XHTML íå çì³ã îïðàöþâàòè çì³ñò ñòîð³íêè. Ñòîð³íêà áóäå âèäàëåíà.",
  "Not_found_content_pointer" => "Íå çíàéäåíî æîäíîãî êîíòåéíåðà çì³ñòó. Ïðîâ³ðòå xslt -ôàéë íà ïðåäìåò ñë³äóþ÷èõ êîíñòðóêö³é: ", 

  "Url" => array( "title"=> "Ïîëå URL äîêóìåíòà", "description" => "Ïåðåì³ííà ç HTTP-àäðåñè äîêóìåíòà. Íàïðèêëàä, about " ),
  "Title" => array( "title"=> "Ïîëå ìåòà-çàãîëîâêà äîêóìåíòà ", "description"=> "Çàãîëîâîê â³êíà äîêóìåíòà. Ìàº îñîáëèâå çíà÷åííÿ äëÿ ïîøóêîâèõ ìàøèí "),
  "Template" => array("title"=> "Ïîëå øàáëîíà äîêóìåíòà", "descr³pt³on" => "Øàáëîí ïðåäñòàâëåííÿ äàíèõ äîêóìåíòà ó ôîðìàò³ XSLT"),

);
?>
