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

$default_charset="WINDOWS-1251";

$lang=array(
	"Icon" => "Èêîíêà",
	"CreateIcon" => "Ñîçäàòü èêîíêó",
	"Width" => "Øèðèíà",
	"Height" => "Âûñîòà",

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
	
		"Save" => "Ñîõðàíèòü",
		"Cancel" => "Îòìåíèòü",
		"Edit" => "Ðåäàêòèðîâàòü",
		"Edit_page" => "Ðåäàêòèðîâàòü",
		"Delete" => "Óäàëèòü", 
		"View_page" => "Ïðîñìàòðèâàòü ñòðàíèöó",
		"Structure" => "Ñòðóêòóðà",
		"Add_item" => "Äîáàâèòü ðàçäåë",
		"Create_subitem" => "Ñîçäàòü ïîäðàçäåë",
		"Logout" => "Âûõîä",
		"Template_select" => "Øàáëîí",
		"Helpdesk_window" => "Îêíî èíôîðìàöèîííîé ïîìîùè",
		"Close_window" => "Çàêðûòü îêíî",
		"NoUrl" => "Ñëåäóåò çàïîëíèòü ïîëå URL",
		"You_are_sure" => "Âû óâåðåíû",
		"Page_not_found" => "Íåâîçìîæíî íàéòè ñòðàíèöó",
		"Page_not_found_desc" => "Âîçìîæíî, ýòà ñòðàíèöà áûëà óäàëåíà, ïåðåèìåíîâàíà, èëè îíà âðåìåííî íåäîñòóïíà.",
		"Authorization_in_admin_area" => "Àâòîðèçàöèÿ â îáëàñòè àäìèíèñòðèðîâàíèÿ",
		"Insert_Picture" => "Âñòàâèòü èçîáðàæåíèå",
		"Browse_Picture_Source" => "Âûáðàòü ôàéë èçîáðàæåíèÿ",
		"Preview" => "Ïðîñìîòð",
		"Alternate_Text" => "Ñîïðîâîäèòåëüíûé òåêñò", 
		"Layout" => "Ðàçìåùåíèå",
		"Alignment" => "Âûðàâíèâàíèå",
		"Middle" => "Ñåðåäèíà",
		"Bottom" => "Âíèç",
		"Top" => "Ââåðõ",
		"Right" => "Ïðàâî",
		"Left" => "Ëåâî",
		"Image" => "Èçîáðàæåíèå",
		"Font" => "Øðèôò",
		"Justify" => "Öåíòðîâêà",
		"Can_not_save_uploaded_file" => "Íå óäàëîñü çàãðóçèòü ôàéë",
		"Border_Thickness" => "Òîëùèíà îáâîäêè",
		"Spacing" => "Îòñòóïû",
		"Horizontal" => "Ãîðèçîíòàëüíûé",
		"Vertical" => "Âåðòèêàëüíûé",
		"Cancel" => "Îòìåíà",
		"Insert_Picture" => "Âñòàâèòü èçîáðàæåíèå",
		"Insert_File" => "Âñòàâèòü ôàéë",
		"cleanHTML" => "Î÷èñòèòü HTML",
		"Indent" => "Îòñòóï",
		"List" => "Ñïèñîê",
		"File" => "Ôàéë",
		"Page" => "Ñòðàíèöà",
		"Show" => "Îòîáðàæàòü",
		"Link" => "Ññûëêà",
		"Insert_Link" => "Âñòàâèòü ññûëêó",
		"Link" => "Ññûëêà",
		"Page_name" => "Íàçâàíèå ñòðàíèöû",
		"Adding_item" => "Äîáàâëåíèå ðàçäåëà",
		"Creating_subitem" => "Ñîçäàíèå âëîæåííîãî ïîäðàçäåëà",
		"Is_it_necessary_fill_fileld" => "Ñëåäóåò çàïîëíèòü ïîëÿ ñâîéñòâ ñòðàíèöû, ñîõðàíèòü èçìåíåíèÿ è ïåðåéòè ê ðåäàêòèðîâàíèþ íîâîé ñòðàíèöû.",
		"XHTML_error" => "Êîíâåðòåð HTML2XHTML íå ñìîã îáðàáîòàòü ñîäåðæàíèå ñòðàíèöû. Ñòðàíèöà áóäåò óäàëåíà.",
		"send_message" => "Ñîîáùåíèå óñïåøíî äîñòàâëåíî",
		"no_send_message" => "Íå óäàëîñü îòïðàâèòü ñîîáùåíèå",

	
		"Not_found_content_pointer" => "Íå íàéäåíî íè îäíîãî êîíòåéíåðà ñîäåðæàíèÿ. Ïðîâåðüòå xslt-ôàéë íà ïðåäìåò ñëåäóþùèõ êîíñòðóêöèé: ",
		
		"Url" => array( "title"=> "Ïîëå URL äîêóìåíòà", "description" => "Ïåðåìåííàÿ èç HTTP-àäðåñà äîêóìåíòà. Íàïðèìåð, about" ),
		"Title" => array( "title"=> "Ïîëå ìåòà-çàãîëîâêà äîêóìåíòà", "description" => "Çàãîëîâîê îêíà äîêóìåíòà. Èìååò îñîáîå çíà÷åíèå äëÿ ïîèñêîâûõ ìàøèí."),
		"Template" => array("title"=> "Ïîëå øàáëîíà äîêóìåíòà", "description" => "Øàáëîí ïðåäñòàâëåíèÿ äàííûõ äîêóìåíòà â ôîðìàòå XSLT"),

);
?>
