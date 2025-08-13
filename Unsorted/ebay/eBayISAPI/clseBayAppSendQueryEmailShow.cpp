/*	$Id: clseBayAppSendQueryEmailShow.cpp,v 1.4.94.4.34.1 1999/08/01 03:01:29 barry Exp $	*/
//
//	File:	clseBayAppSendQueryEmailShow.cpp
//
//	Class:	clseBayApp
//
//	Author:	Vicki Shu (vicki@ebay.com)
//
//	Function:
//
//		Handle a send email to supprot request form
//
// Modifications:
//				- 03/01/99 vicki	- Created
//				- 07/08/99 mila		- Changed subject=deadbeat to subject=npb
//									  and added redirect for subject=deadbeat
//									  to go to subject-npb.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

bool clseBayApp::SendQueryEmailShow(CEBayISAPIExtension *pThis,
									char *pSubject,
									char *pRedirectURL)
							  
{
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" E-mail Question to Support"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=\"100%\" bgcolor=\"#99CCCC\">\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=4 color=\"#000000\">"
					"<b>";
	if (stricmp(pSubject, "npb") == 0)
	{
		*mpStream << "Non-Paying Bidder Appeal"
					"</b></font></td>\n"
					"</tr>\n"
					"</table></center>\n"
					"<p>To appeal a warning which you feel you have received in error, "
					"please provide the following information to eBay, using the message "
					"field on this form: \n"
					"<ul><li>The transaction number for the item\n" 
					"<li>Your UserID"
					"<li>The reason for your appeal\n "
					"<li>Any supporting information\n"
					"</ul>\n"; 
	}
	else if (stricmp(pSubject, "deadbeat") == 0)
	{
		// construct URL of npb appeals page
		strcpy(pRedirectURL, mpMarketPlace->GetCGIPath(PageSendQueryEmailShow));
		strcat(pRedirectURL, "eBayISAPI.dll?SendQueryEmailShow&subject=npb");

		// Just in case the redirect doesn't work, tell user where to go
		*mpStream << "<p>Click <b>refresh</b> or <b>reload</b> button on your browser now.";

		CleanUp();
		return true;
	}
	else
	{
		*mpStream <<"E-mail Question to Support"	
					"</b></font></td>\n"
					"</tr>\n"
					"</table></center>\n";
	}


	*mpStream	<<	"<form method=post action=\""			  
				<<	mpMarketPlace->GetCGIPath(PageSendQueryEmail)
				<<	"eBayISAPI.dll"					
				<<	"\">\n"		
				<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"SendQueryemail\">"
					"<table border=\"1\" width=\"590\" cellspacing=\"0\" cellpadding=\"4\">\n"
					"<tr>\n"
					"<td width=\"170\" bgcolor=\"#EFEFEF\">";

	if (stricmp(pSubject, "registering") == 0)
	{
		*mpStream	<<	"<font color=\"#006600\"><strong>Your Email Address</strong> <br></font></td>\n"
						"<td width=\"420\"><input type=\"text\" name=\"userid\" size=\"40\" maxlength=\"63\">\n"
						"</td>\n"
						"</tr>\n"
						"<input type=\"hidden\" name=\"subject\" value=\"registering\">\n";
	}
	else if (stricmp(pSubject, "npb") == 0)
	{
		*mpStream	<<	"<font color=\"#006600\"><strong>Your Email Address</strong> <br></font></td>\n"
						"<td width=\"420\"><input type=\"text\" name=\"userid\" size=\"40\" maxlength=\"63\">\n"
						"</td>\n"
						"</tr>\n"
						"<input type=\"hidden\" name=\"subject\" value=\"npb appeals\">\n";
	} 
	else
	{
		*mpStream	<<	"<font color=\"#006600\"><strong>Your "
					<<	mpMarketPlace->GetLoginPrompt()
					<<	"</strong> <br></font></td>\n"
						"<td width=\"420\"><input type=\"text\" name=\"userid\" size=\"40\" maxlength=\"63\">\n"
						"</td>\n"
						"</tr>\n"
						"<tr>\n"
						"<td width=\"170\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"><strong>Your "
					<<	mpMarketPlace->GetPasswordPrompt()
					<<	"</strong> <br>\n"
						"</font></td>"
						"<td width=\"420\"><input type=\"password\" name=\"pass\" size=\"40\" maxlength=\"63\"> </td>\n"
						"</tr>\n";

		//subject dropdown box
		*mpStream	<<  "<tr>\n"
						"<td width=\"170\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"><strong>Subject</strong> <br>\n"
						"</font></td>\n"
						"<td width=\"420\">";


		EmitDropDownList(mpStream,
						 "subject",
						 (DropDownSelection *)&QueryEmailSubject,
						 pSubject,
						 "default",
						 "Please select one");
						
		*mpStream	<<  "</td>\n"
						"</tr>";
	}
		
	*mpStream	<<		"<tr>"
						"<td width=\"170\" bgcolor=\"#EFEFEF\" valign=\"top\"><font size=\"3\"><font color=\"#006600\"><strong>Message</strong></font>\n"
						"</font></td>\n"
						"<td width=\"420\"><textarea name=\"message\" cols=\"50\" rows=\"8\"></textarea> </td>\n"
						"</tr>\n"
						"</table>\n"
						"<p><input type=\"submit\" value=\"Send Inquiry\"> &nbsp; &nbsp; <input type=\"reset\" "
						"value=\"Clear all data\"> </p>"
						"</form>\n";
	
				
	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return false;
}
