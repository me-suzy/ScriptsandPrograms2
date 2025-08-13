/*	$Id: clseBayAppAdminAnnouncement.cpp,v 1.5.382.1 1999/08/01 02:51:39 barry Exp $	*/
//
//	File:		clseBayAppAdminAnnouncement.cc
//
//	Class:		clseBayAppAdminAnnouncement
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 11/04/97 tini	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"


// common form to add new or update announcement
void clseBayApp::AnnouncementEntry(clsAnnouncement *pAnnounce, int SiteId, int PartnerId)
{
	// allow entering of new announcement here
	// Begin the form	
	char *pTemp;	

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAddAnnouncement)
			  <<	"eBayISAPI.dll?AddAnnouncement"
			  <<	"\""
					">\n";

	// Row for Site Id and partner id
	*mpStream <<	"<input type=hidden name=siteid value=\"";
	if (pAnnounce)
	{
		*mpStream <<	pAnnounce->GetSiteId();
	}
	else
	{
		*mpStream <<	SiteId;
	}
	*mpStream	  <<	"\">\n";

	*mpStream <<	"<input type=hidden name=partnerid value=\"";
	if (pAnnounce)
	{
		*mpStream <<	pAnnounce->GetPartnerId();
	}
	else
	{
		*mpStream <<	PartnerId;
	}
	*mpStream	  <<	"\">\n";


	// Make a table to hold the first five fields
	*mpStream <<	"<table border=0 cellpadding=0 width=100%>\n";

	// Row for email
	*mpStream <<	"<tr>\n"
					"<td width=35%><font size=2>"
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	"</font></td>"
					"<td width=65%>"
					"<input type=text name=userid ";

	*mpStream <<	"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_USERID_SIZE << ">"
			  <<	"</td>\n"
					"</tr>\n";

	// Row for password
	*mpStream <<	"<tr>\n"
					"<td width=35%><font size=2>"
			  <<	mpMarketPlace->GetPasswordPrompt()
			  <<	"</font></td>"
					"<td width=65%>"
					"<input type=password name=pass "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_PASSWORD_SIZE << ">"
			  <<	"</td>\n"
					"</tr>\n";


	// Row for announcement id
	*mpStream <<	"<tr>\n"
					"<td width=35%><font size=2>"
			  <<	"Announcement Id "
			  <<	"</font></td>"
					"<td width=65%>"
					"<input type=text name=id size = 6 ";
	if (pAnnounce)
	{
		*mpStream <<	"value=\""
				  <<	pAnnounce->GetId()
				  <<	"\" ";
	}

	*mpStream	  <<	">"
						"</td>\n"
						"</tr>\n";


	// Row for location with radio button header/footer
	*mpStream <<	"<tr>\n"
					"<td width=35%><font size=2>"
			  <<	"location"
			  <<	"</font></td>"
					"<td width=65%>"
					"<INPUT TYPE=\"RADIO\" "
					<<  "NAME=\"loc\" "
					<<	"VALUE=\"1\" ";

	if ((pAnnounce) && (pAnnounce->GetLocation() == Header))
	{	
		*mpStream	<< " checked";
	}
	*mpStream	<<	">Header ";

	// footer
	*mpStream	<<	"<INPUT TYPE=\"RADIO\" "
					<<  "NAME=\"loc\" "
					<<	"VALUE=\"2\" ";

	if ((pAnnounce) && (pAnnounce->GetLocation() == Footer))
	{	
		*mpStream	<< " checked";
	}
	*mpStream	<<	">Footer ";

	*mpStream <<	"</td>\n"
					"</tr>\n";					

	// Row for announcement code
	*mpStream <<	"<tr>\n"
					"<td width=35%><font size=2>"
			  <<	"Where? "
			  <<	"</font></td>"
					"<td width=65%>"
					"<input type=text name=code size = 20 maxlength = 20 ";
	if (pAnnounce)
	{
		*mpStream <<	"value=\""
				  <<	pAnnounce->GetCode()
				  <<	"\" ";
	}
	*mpStream	  <<	">"
						"</td>\n"
						"</tr>\n";

	// End table
	*mpStream <<	"</table>\n";

	// Make a table for description 
	*mpStream <<	"<table border=0 cellpadding=0 width=100%>\n";
					

	// Row for description
	*mpStream <<	"<tr>\n"
					"<td width=35%><font size=2>"
			  <<	"Announcement Text "
			  <<	"</font></td>"
					"<td width=65%>"
					"<textarea name=desc cols=60 rows=8>";
	if (pAnnounce)
	{		
		pTemp = clsUtilities::StripHTML(pAnnounce->GetDesc());
		if (pTemp)
		{
//			char *transformed = TransformInput(mpItem->GetDescription(), TRUE);
//			*mpStream <<	transformed;
//			delete [] transformed;
			*mpStream <<	pTemp;
			delete pTemp;
		}
		else
		{
			*mpStream <<	"none";
		}
	}
	*mpStream <<	"</textarea>"
			  <<	"</td>\n"
					"</tr>\n";	

	// End table for description and picture url
	*mpStream <<	"</table>\n";

		// Row for Submit
	*mpStream <<	"<tr>\n"
					"<td width=35%><font size=2>"
			  <<	"<b>Submit your announcement.</b>"
			  <<	"</font></td>"
					"<td width=65%>"
					"<input type=submit value=\"submit\">"
			  <<	"</td>\n"
					"</tr>\n";	

}

void clseBayApp::AdminAnnouncement(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel, int SiteId, int PartnerId)
{
	AnnouncementVector vAnnouncements;
	AnnouncementVector::iterator	i;
	char*	pTemp;
	
	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// We'll need a title here
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Announcement Administration"
			  <<	"</TITLE></head>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Spacer
	*mpStream <<	"<br>";

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// Title using black on darkgrey table
	*mpStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=100% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>"
					"eBay Announcement Administration"
					"</strong></font></td>\n"
					"</tr>\n"
					"</table></center>\n";
	// Spacer
	*mpStream <<	"<br>";

	*mpStream <<	"<p>This page is used to administer announcements in all of eBay System."
					"To update announcements, go to the announcement edit link."
					"To add a new announcement, please enter the appropriate fields."
					"You must have proper authorization to enter or update announcements. \n";
		
	// Spacer
	*mpStream <<	"<br>";
	*mpStream <<	"<br>";

	// emit all announcements
	mpAnnouncements->GetAllAnnouncementsBySiteAndPartner(&vAnnouncements, SiteId, PartnerId);

	for (i = vAnnouncements.begin();
	     i != vAnnouncements.end();
	     i++)
	{
		// emit the announcement with formatting
		*mpStream << "<table border=1 cellspacing=0 "
					"width=80% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=left width=100%>"
					"<font size=3>&nbsp;";
		*mpStream << "ID: "
				  << (*i)->GetId();
		*mpStream << "&nbsp;&nbsp;&nbsp;&nbsp;";

		*mpStream << "Location: ";
		*mpStream << (*i)->GetCode();

		*mpStream << "&nbsp;&nbsp;&nbsp;&nbsp;";

		*mpStream << "(";
		if ((*i)->GetLocation() == 1)
			*mpStream << "Header";
		else
			*mpStream << "Footer";
		*mpStream << ")";
		*mpStream << "&nbsp;&nbsp;&nbsp;&nbsp;";

		*mpStream << "Last modified: ";
		*mpStream << (*i)->GetModDateAsString();

		*mpStream << "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

		// edit link
		*mpStream <<	"<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageUpdateAnnouncement)
				  <<	"eBayISAPI.dll?UpdateAnnouncement&id="
				  <<	(*i)->GetId()
				  <<	"&loc="
				  <<	(*i)->GetLocation()
				  <<	"&siteid="
				  <<	(*i)->GetSiteId()
				  <<	"&partnerid="
				  <<	(*i)->GetPartnerId()
				  <<	"\""
						">"
						"edit"
						"</a>";

		*mpStream <<	"</font></td>\n"
						"</tr>\n"
						"</table></center>\n";

		// don't show html view if it is HeaderWidgetPrefix or HeaderWidgetSuffix
		if ((*i)->GetId() != HeaderWidgetPrefix && (*i)->GetId() != HeaderWidgetSuffix)
		{
			// Row for description  in both html and text
			*mpStream <<	"<p>HTML View:\n"
							"<br>";

			*mpStream <<	"<blockquote>"
							"\n";

			// 
			*mpStream <<   (*i)->GetDesc();

			// Clean up after the user's potentially naughty HTML
			*mpStream <<	"\n"
							"</blockquote>"
							"</blockquote>"
							"</center>"
							"</center>"
							"</strong>"
							"</pre>"
							"</em>"
							"</font>"
							"</dl>"
							"</ul>"
							"</li>"
							"</h1>"
							"</h2>"
							"</h3>"
							"</h4>"
							"</h5>"
							"</h6>"
							"\n";
		}

		// Row for description  in both html and text
		*mpStream <<	"<p>Text View:\n"
						"<br>";

		*mpStream <<	"<pre>"
						"\n";

		// 
		pTemp = clsUtilities::StripHTML((*i)->GetDesc());
		*mpStream << pTemp;
		delete pTemp;

		// Clean up after the user's potentially naughty HTML
		*mpStream <<	"\n"
						"</pre>";

		// Delete the announcement
		delete	(*i);
	}

	vAnnouncements.erase(vAnnouncements.begin(), vAnnouncements.end());

	// New announcement section using black on darkgrey table
	*mpStream <<	"<table border=1 cellspacing=0 "
					"width=80% bgcolor=#CCCCCC>\n"
					"<tr>\n"
					"<td align=left width=100%>"
					"<font size=3>"
					"<center>New Announcement Entry</center>"
					"</font></td>\n"
					"</tr>\n"
					"</table>\n";
	// Spacer
	*mpStream <<	"<br>";

	AnnouncementEntry(0, SiteId, PartnerId);

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;
		

	CleanUp();
}

//void clseBayApp::Trace(char *pFormat, ...)
//{
//	// We always do this..
//	va_list args;
//  	va_start(args, pFormat);

//	ISAPITRACE((LPCTSTR)pFormat, args);

//	return;
//}

