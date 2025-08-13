/*	$Id: clseBayAppShowEmailAuctionToFriend.cpp,v 1.7.236.4.44.1 1999/08/01 03:01:30 barry Exp $	*/
//
//	File:		clseBayAppShowEmailAuctionToFriend.cpp
//
//	Class:		clseBayAppShowEmailAuctionToFriend
//
//	Author:		Craig Huang (chuang@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 02/25/98 Craig	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"



//
// AcceptBid
//
void clseBayApp::ShowEmailAuctionToFriend(CEBayISAPIExtension *pThis,
							  int item)
							  
{
	char *cleanTitle = NULL;

	SetUp();

	// Get the item and check it
	if (!GetAndCheckItem(item))
	{
		CleanUp();
		return;
	}	
	

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

		// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" E-mail Auction To Friend"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());
	*mpStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=\"100%\" bgcolor=\"#99CCCC\">\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=4 color=\"#000000\">"
					"<b>"
			  <<	"E-mail auction item #"	
			  <<	mpItem->GetId()			  
			  <<	" to a friend"	
			  <<	"</b></font></td>\n"
					"</tr>\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=3 color=\"#000000\">"
					"<b>"					
			  <<	cleanTitle
			  <<	"</b></font></td>\n"
					"</tr>\n"
					"</table></center>\n";
	delete [] cleanTitle;

	*mpStream	<<	"<form method=post action=\""			  
				<<	mpMarketPlace->GetCGIPath(PageEmailAuctionToFriend)
				<<	"eBayISAPI.dll"					
				<<	"\">\n"		
				<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"EmailAuctionToFriend\"><input "
				<<	" type=\"hidden\" name=\"item\" value=\""
				<<	item
				<<	"\"><table border=\"0\" cellpadding=\"2\" cellspacing=\"0\""
				<<	" width=\"590\">"
				<<	"<tr>"
				<<	"<td width=\"270\" valign=\"top\"><input type=\"text\" name=\"userid\" size=\"30\" maxlength=\"64\"><br>"
				<<	"<font size=\"2\">Your <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/myinfo/userid.html\">"
				<<	" User ID</a></font>"
				<<	"<p><input type=\"password\" name=\"password\" size=\"30\" maxlength=\"64\"><br>"
				<<	"<font size=\"2\">"
				<<	mpMarketPlace->GetPasswordPrompt()
				<<  "</font></p>"
				<<	"<p><input type=\"text\" name=\"email\" size=\"30\""
				<<	"maxlength=" << EBAY_MAX_PASSWORD_SIZE << "><br>"
				<<	"<font size=\"2\">Your friend's e-mail address (will not be used for"
				<<	" promotional purposes nor disclosed to a third party).</font>"
//				<<	"</p>"
//				<<	"<p><input type=\"text\" name=\"friendname\" size=\"30\" maxlength=\"64\"><br>"
//				<<	"<font size=\"2\">Your friend's name <font color=\"#FF0000\">(optional)</font></font>"
				<<	"</td>"
				<<	"<td width=\"320\" valign=\"top\"><table border=\"1\" cellpadding=\"4\" cellspacing=\"0\""
				<<	" width=\"100%\">"
				<<	"<tr>"
				<<	"<td width=\"100%\" bgcolor=\"#FFFFCC\"><font size=\"2\">eBay has taken a strong public stance"
				<<	" against the practice of sending unsolicited commercial e-mails, also known as"
				<<	" &quot;spam.&quot; Please send these e-mails only to people you know who would be"
				<<	" interested in the item. If someone asks you not to send these e-mails to them, please"
				<<	" comply.<br>"
				<<	"<br>"
				<<	"<strong>Sellers</strong>: If you use this service to advertise an item that you are"
				<<	" selling, and a recipient complains to eBay, your registration may be suspended."
				<<	" &quot;Spamming&quot; by our sellers is a suspendible offense (See our <a"
				<<	" href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/community/png-comm.html\">Guidelines</a>).</font></td>"
				<<	"</tr>"
				<<	"</table>"
				<<	"</td>"
				<<	"</tr>"
				<<	"</table>"
				<<	"<p><textarea NAME=\"message\" ROWS=\"5\" COLS=\"50\">I saw this item for sale at "
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	", the world's largest personal trading community, and "
				<<	"thought that you might be interested.</textarea><br>"
				<<	"<font size=\"2\">Personal message to your friend <font color=\"#FF0000\">(optional)</font></font></p>"
				<<	"<font size=\"2\"><div align=\"left\"><p><input name=\"htmlenable\" type=\"CHECKBOX\" value=\"ON\">"
				<<	"Click here if your friend uses a browser to read e-mail (if you don't know, just leave"
				<<	" this blank)</font><br>"
				<<	"<br>"
				<<	"&nbsp; <input type=\"submit\" value=\"send it\">&nbsp;&nbsp;&nbsp; &nbsp; <input type=\"reset\""
				<<	" value=\"clear form\"> </p>"
				<<	"</div>"
				<<	"</form>";
				
	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";
	CleanUp();
	return;
}
