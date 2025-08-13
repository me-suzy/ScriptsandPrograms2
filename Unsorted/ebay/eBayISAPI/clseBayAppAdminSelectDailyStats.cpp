/*	$Id: clseBayAppAdminSelectDailyStats.cpp,v 1.5.396.1 1999/08/05 20:42:05 nsacco Exp $	*/
//
//	File:		clseBayAppAdminViewDailyStats.cpp
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 09/18/97 wen	- Created
//

#include "ebihdr.h"

void clseBayApp::AdminSelectDailyStats(CEBayISAPIExtension *pServer)
{
	time_t	Today = time(0);
	struct tm*	TmToday;
	char	cTime[10];

	SetUp();

	// default to a day before
	Today -= 24 * 60 * 60;
	TmToday = localtime(&Today);
	sprintf(cTime, "%02d/%02d/%d",  TmToday->tm_mon+1, 
									TmToday->tm_mday, 
									TmToday->tm_year);

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	"Daily Statistics Selection"
			  <<	"</title>"
					"</head><body>\n"
			  <<	"<h2>eBay Daily Statistics</h2>\n"
			  <<	"<form method=\"POST\" action=\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminViewDailyStats)
			  <<	"eBayISAPI.dll"
					"\">"
			  <<	"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"AdminViewDailyStats\">\n"
			  <<	"<h4>Please enter the dates (mm/dd/yy):</h4>\n"
					"<p>&nbsp;&nbsp;&nbsp;"
					"From: <input type=\"text\" name=\"startdate\" size=8 value=\""
			  <<	cTime
			  <<	"\"> To <input type=\"text\" name=\"enddate\" size=8 value=\""
			  <<	cTime
			  <<	"\"></p>\n"
					"<p>&nbsp;</p>"
/*
					"<h4>Statistics:</h4>\n"
					"<table border=0 cellspacing=6 cellpadding=4>\n"
					"<tr><td width="33%"></td>\n"
					"<td width=\"33%\" align=\"center\">New</td>\n"
					"<td width=\"34%\" align=\"center\">Completed</td></tr>\n"
					"<tr><td width=\"33%\">Totol Autions</td>\n"
					"<td width=\"33%\" align=\"center\"><input type=\"checkbox\" name=\"TotalNew\" value=1></td>\n"
					"<td width=\"34%\" align=\"center\"><input type=\"checkbox\" name=\"TotalDone\" value=1></td></tr>\n"
					"<tr><td width=\"33%\">Regular Auctions</td>\n"
					"<td width=\"33%\" align=\"center\"><input type=\"checkbox\" name=\"RANew" value=1></td>\n"
					"<td width=\"34%\" align=\"center\"><input type=\"checkbox\" name=\"RADone\" value=1></td></tr>\n"
					"<tr><td width=\"33%\">Dutch Auctions</td>\n"
					"<td width=\"33%\" align=\"center\"><input type=\"checkbox\" name=\"DANew\" value=1></td>\n"
					"<td width=\"34%\" align=\"center\"><input type=\"checkbox\" name=\"DADone\" value=1></td></tr>\n"
					"</table>\n"
*/
					"<p><input type=\"submit\" value=\"Submit\">"
					"<input type=\"reset\" value=\"Reset\">"
					"</p></form>\n"
			  <<	"<br>"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();

	return;

}
