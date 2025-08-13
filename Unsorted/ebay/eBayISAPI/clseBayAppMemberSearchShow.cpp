/*	$Id: clseBayAppMemberSearchShow.cpp,v 1.1.6.7.14.2 1999/08/05 18:58:57 nsacco Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		vicki (vicki@ebay.com)
//
//	Function:
//
//				Display pages that user can request other's information
//
//	Modifications:
//				- 04/21/99 vicki	- Created
//				- 06/23/99 jennifer - Removed color border and added grey backgourd
//									  for UI Phase I.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clseBayApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsUserValidation.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.

#include "stdafx.h"
#include <AFXISAPI.H>

extern bool ShowRememberMe;

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
const char MsgCookie[] = 
"<font size=\"2\">By choosing this option, you\'ll get a temporary \"cookie\" that enables you "
"to skip this step if you request another user's email address during this "
"browser session.  When you turn your browser off, the cookie will "
"disappear. For more information about cookies, click "
"<A HREF=\"http://pages.ebay.com/help/myinfo/cookies.html\">here</A>.<br></font>";
*/



const char AnnouncementAndSearchHeading1 [] =
"<FORM ACTION=\"http://search.ebay.com/cgi-bin/texis/ebay/results.html\" METHOD=\"GET\">\n"
"  <INPUT TYPE=\"hidden\" NAME=\"maxRecordsReturned\" VALUE=\"300\">"
"	<INPUT TYPE=\"hidden\" NAME=\"maxRecordsPerPage\" VALUE=\"50\">"
"	<INPUT TYPE=\"hidden\" NAME=\"SortProperty\" VALUE=\"MetaEndSort\">"
"	<INPUT TYPE=\"hidden\" NAME=\"ht\" value=\"1\">\n"
"<!-- table for search and announcements -->"
"<TABLE border=\"0\" cellspacing=\"0\" width=\"600\" cellpadding=\"2\" bgcolor=\"#FFFFCC\">"
" <TR>"
"	<!-- search box -->"
"	<TD width=\"35%\">"
"	<input NAME=\"query\" SIZE=\"12\" MAXLENGTH=\"100\"> <input TYPE=\"SUBMIT\" VALUE=\"Search\"> "
"<font size=\"2\">";

const char AnnouncementAndSearchHeading2 [] =
"</TD>"
"	<!-- announcements -->"
"	<TD width=\"65%\">\n"
"		<font size=\"2\">"
"		<strong><font color=\"red\">&gt;&gt;</font></strong> Join in the effort "
"to help the Kosovo Refugees -- click ";

const char AnnouncementAndSearchHeading3 [] =
" to learn more.<br>"
"<strong><font color=\"red\">&gt;&gt;</font></strong> You're insured! ";

const char AnnouncementAndSearchHeading4 [] =
"about the free insurance for eBay members."
"</font>"
"<!-- end of announcements -->\n"
"</TD></TR></TABLE>\n"
"<TABLE border=\"0\" cellspacing=\"0\" width=\"600\" cellpadding=\"0\">"
"<tr><td><hr></td></tr></TABLE></FORM>\n";

const char ContactInfoMsg1 [] =
"<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\">\n"
"<tr><td width=\"75%\">"
"<font size=2>Use this form to request contact information for another eBay "
"registered user. You will be sent an e-mail message with the "
"information. The information you request is sent via e-mail "
"in order to prevent abuse by non-registered users. "
"<p>Keep in mind that the user whose information you are "
"requesting will <strong>also receive your "
"information.</strong> And, this information can only be used "
"in matters regarding eBay. Any other use is specifically prohibited.<br></font>\n "
"</td>"
"<td valign=top width=\"25%\">"
"<table border=\"0\" cellspacing=\"0\" bgcolor=\"#EFEFEF\" cellpadding=\"4\">\n"
"<tr><td>"
"<font size=\"2\">If you would like to read a full explanation of our privacy policy, "
"click on the TRUSTe button below:</font><p align=center>";

const char ContactInfoMsg2 [] =
"</p></td></tr></table>"
"</td></tr></table>"
"<br>"
"<strong>Any abuse of this information will not be tolerated.</strong>";


void clseBayApp::MemberSearchShow(CEBayISAPIExtension *pServer)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Member Search Show"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;
/*
	// search and Announcement
	*mpStream	<<	AnnouncementAndSearchHeading1
				<<	"<a href=\""
				<<   mpMarketPlace->GetHTMLPath()
				<<	"help/sellerguide/selling-tips.html\">tips</a></font> "
				<<	AnnouncementAndSearchHeading2
				<<	"<a HREF=\""
				<<	mpMarketPlace->GetMembersPath()
				<<	"aboutme/kosovo-relief/\">here</a> "
				<<	AnnouncementAndSearchHeading3
				<<	"<a HREF=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/safeharbor/safeharbor-insurance.html\">"
				<<	"Learn more</a> "
				<<	AnnouncementAndSearchHeading4;
*/

	//heading 
	*mpStream	<<	"<table border=\"0\" width=\"600\">\n"
					"<tr>\n"
					"<td>"
					"<font face=\"Verdana, Arial, Helvetica, sans-serif\" "
					"size=\"+2\"><b>Find Members</b></font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n";
	// find menu
	*mpStream	<<	"<font  size=\"2\"><B>Find menu:</B>&nbsp; "
				<<	"<a href=\"#AboutMe\">About Me</a> - \n"
				<<	"<a href=\"#Feedback\">Feedback Profile</a> - \n"
				<<	"<a href=\"#EmailAddress\">Email Address and User ID History</a> - \n"
				<<	"<a href=\"#ContactInfo\">Contact Info</a></font>\n";

	// --------------------------------------------
	// 1st form: Find an About Me member
	*mpStream	<<	"<form method=\"get\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageViewUserPage)
				<<	"eBayISAPI.dll\">"
				<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"ViewUserPage\">\n"

				<<	"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\">"
				<<	"<tr>"
				<<	"<td bgcolor=\"#FFFFFF\">"

				<<	"<table border=\"1\" "
					"width=\"600\" cellspacing=\"0\" cellpadding=\"4\">"
					"<tr>\n"
					"<td width=\"25%\" bgcolor=\"#EFEFEF\" valign=\"top\"><a name=\"AboutMe\"><font size=\"3\"><strong>About Me</strong></font> <br>\n"
					"<font size=\"2\">View the <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/basics/g-aboutme.html\">"
					"About Me</a> page of another member</font></td>"
					"<td width=\"75%\" bgcolor=\"#FFFFFF\"><input type=\"text\" name=\"userid\" size=\"25\" maxlength=\"64\"> \n"
					"<input type=\"submit\" value=\"Search\"><br>\n"
					"<font size =\"2\">"
				<<	mpMarketPlace->GetLoginPrompt()
				<<	" of member</font></td>\n"
					"</tr>\n"
					"</table>\n"

				<<	"</td></tr></table>"

					"</form>\n";
	// End of 1st form
	// --------------------------------------------


	// --------------------------------------------
	// 2nd form: Feedback profile
	*mpStream	<<	"<form method=\"get\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageViewUserPage)
				<<	"eBayISAPI.dll\">"
				<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"ViewFeedback\">\n"

				<<	"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\">"
				<<	"<tr>"
				<<	"<td bgcolor=\"#FFFFFF\">"

				<<	"<table border=\"1\" "
					"width=\"600\" cellspacing=\"0\" cellpadding=\"4\">"
					"<tr>\n"
					"<td width=\"25%\" bgcolor=\"#EFEFEF\" valign=\"top\"><a name=\"Feedback\"><font size=\"3\"><strong>Feedback Profile</strong></font> <br>\n"
					"<font size=\"2\">View the <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/basics/g-feedback.html\">"
					"Feedback Profile</a> of another member</font></td>"
					"<td width=\"75%\" bgcolor=\"#FFFFFF\"><input type=\"text\" name=\"userid\" size=\"25\" maxlength=\"64\"> \n"
					"<input type=\"submit\" value=\"Search\"><br>\n"
					"<font size =\"2\">"
				<<	mpMarketPlace->GetLoginPrompt()
				<<	" of member"
					"<br><br>"
					"How many feedback comments do you want on each page?<br>"
					"<input type=\"radio\" name=\"items\" value=\"25\" checked>"
					"25 "
					"<input type=\"radio\" name=\"items\" value=\"50\">"
					"50 "
					"<input type=\"radio\" name=\"items\" value=\"100\">"
					"100 "
					"<input type=\"radio\" name=\"items\" value=\"200\">"
					"200"
					"<input type=\"radio\" name=\"items\" value=\"0\">"
					"All</font>"
					"</td>\n"
					"</tr>\n"
					"</table>\n"

				<<	"</td></tr></table>"

					"</form>\n";
	// End of 2nd form
	// --------------------------------------------

	// --------------------------------------------
	// 3rd form: Request userid and email history 
	*mpStream	<<	"<form method=\"POST\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageReturnUserEmail)
				<<	"eBayISAPI.dll\">\n"
					"<input TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
					"VALUE=\"ReturnUserEmail\">\n";

	*mpStream	<<	"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\">"
				<<	"<tr>"
				<<	"<td bgcolor=\"#FFFFFF\">"

				<<	"<table border=\"1\" width=\"600\" cellspacing=\"0\" cellpadding=\"4\">\n"
					"<tr>\n"
					"<td width=\"25%\" bgcolor=\"#EFEFEF\" valign=\"top\"><a name=\"EmailAddress\"\n"
					"<font size=\"3\"><strong>Email Address</strong> and<br>\n"
					"<a name=\"BidderSearch\"><strong>User ID History</strong></a></font><br>\n"
					"</td>\n"
					"<td width=\"75%\" bgcolor=\"#FFFFFF\">\n";
									
	if (mpUsers->GetUserValidation()->IsSoftValidated() == false)
	{
		// legal, rule
		/*
		*mpStream	<< "<font size=\"2\">" 
					<< "eBay kindly requests that you submit your User ID and password to "
					<< "view the User ID History or email address of another user.\n"
					<< "<P>Note: When the shades icon<img border=0 height=15 width=21 alt=\"mask\""
					<<	"src=\""
					<<	mpMarketPlace->GetImagePath()
					<<	"mask.gif\"> appears next to a User ID, it "
					<< "signifies that the user has changed his/her User ID within the "
					<< "last 30 days.  The shades icon will disappear after the user has "
					<< "maintained the same User ID for a 30-day period.";
		*/
	}

	// link to multiple emails
	/*
	*mpStream	<<	"<p>If you would like to look up the e-mail addresses of <b>multiple users</b> at once, click <a href=\""
				<<	mpMarketPlace->GetCGIPath(PageMultipleEmails)
				<<	"eBayISAPI.dll?GetMultipleEmails\">"
				<<	"here</a>.</font>\n";
	*/

	// form

	*mpStream	<<	"<table><tr>\n"
				<<  "<td><input type=\"text\" name=\"requested\" size=25>";
	
	// determine whether there is requested user id
/*	if (pUserId && stricmp(pUserId, "default") != 0)
	{
		 *mpStream	<<	"value=\""
					<<	pUserId
					<<	"\"\n";
	} */

	*mpStream	<<	"<br>"
				<<	"<font size =\"2\">"
				<<	mpMarketPlace->GetLoginPrompt()
				<<	" of member</font></td></tr>\n";

	if (mpUsers->GetUserValidation()->IsSoftValidated() == false)
	{
		// print out the userid and password form
		*mpStream	<<	"<tr><td><input type=\"text\" name=\"userid\" size=25>"
					<<	"<br><font size=\"2\"><b>Your</b> "
					<<	mpMarketPlace->GetLoginPrompt()
					<<	" or E-mail address</font></td></tr>\n"
						"<tr><td><input type=\"password\" name=\"pass\" size=25><br>"
						"<font size=\"2\"><b>Your</b> "
					<<	mpMarketPlace->GetPasswordPrompt()
					<<	"</font></td></tr>\n";

		*mpStream	<<	"<tr><td>";

		if (ShowRememberMe)
		{
			*mpStream	<<	"<P><input type=\"checkbox\" name=acceptcookie value=1><font size=\"2\">Remember me</font>\n<br>";
		//				<<	MsgCookie;

		// kakiyama 07/09/99

			*mpStream   << clsIntlResource::GetFResString(-1,
								"<font size=\"2\">By choosing this option, you\'ll get a temporary \"cookie\" that enables you "
								"to skip this step if you request another user's email address during this "
								"browser session.  When you turn your browser off, the cookie will "
								"disappear. For more information about cookies, click "
								"<A HREF=\"%{1:GetHTMLPath}help/myinfo/cookies.html\">here</A>.<br></font>",
								clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							        NULL);
		}

		*mpStream	<<	"</td></tr>\n";
	}

	// row for submit button
	*mpStream	<<	"<tr><td><input type=submit value=\"Submit\"></td></tr>";

	// close the table 
	*mpStream	<<	"</table>\n";

	//ending 2nd table for request email
	*mpStream	<<	"</td></tr></table>";

	*mpStream	<<	"</td></tr>"
					"</table>";

	// close the form
	*mpStream	<<	"</form>\n";

	// End of 3rd form
	// --------------------------------------------

	// --------------------------------------------
	// 4th form: Request contact info
	*mpStream	<<	"<form method=\"POST\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageReturnUserEmail)
				<<	"eBayISAPI.dll\">\n"
					"<input TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
					"VALUE=\"UserQuery\">\n";

	*mpStream	<<	"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\">"
				<<	"<tr>"
				<<	"<td bgcolor=\"#FFFFFF\">"

					"<table border=\"1\" width=\"600\" cellspacing=\"0\" cellpadding=\"4\">\n"
					"<tr>\n"
					"<td width=\"25%\" bgcolor=\"#EFEFEF\" valign=\"top\"><a name=\"ContactInfo\"\n"
					"<font size=\"3\"><strong>Contact Info</strong></font><br>\n"
					"</td>"
					"<td width=\"75%\" bgcolor=\"#FFFFFF\">\n";
	//msg for contact info
	*mpStream	<<	ContactInfoMsg1
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/registration/privacy-policy-reg.html\">"
				<<	"<img src=\""
				<<	mpMarketPlace->GetImagePath()
				<<	"truste_button.gif\" width=\"116\" height=\"31\" alt=\"TrustE\" border=0></a>"
				<<	ContactInfoMsg2;	

	*mpStream	<<	"<table>";

	// row for member userid
	*mpStream	<<	"<tr><td>"
					"<input type=text name=otheruserid size=\"25\" maxlength=\"64\"><br>\n"
				<<	"<font size=\"2\">"
				<<	mpMarketPlace->GetLoginPrompt()
				<<	" of member\n"
					"</font></td></tr>";

	// row for your user id
	*mpStream	<<	"<tr>\n"
				<<  "<td>\n"
				<<	"<input type=\"text\" name=\"userid\" size=\"25\" maxlength=\"64\">"
				<<	"<br>\n"
				<<	"<font size =\"2\"><b>Your</b>  "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	" or E-mail address</font></td></tr>\n";

	// row for your password
	*mpStream	<<	"<tr><td>"
					"<input type=\"password\" name=\"pass\" size=\"25\" maxlength=\"64\"><br>\n"
					"<font size=\"2\"><b>Your</b> "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	"</font></td></tr>\n";

	// row for submit button
	*mpStream	<<	"<tr><td><input type=submit value=\"Submit\"></td></tr>";

					
	*mpStream	<<	"</table>\n";

	*mpStream	<<	"</td></tr></table>";

	//ending contact info table
	*mpStream	<<	"</td>\n"
					"</tr>\n"
					"</table></form>\n";

	// --------------------------------------------
	// the footer
	*mpStream << mpMarketPlace->GetFooter()
				 << flush;

	CleanUp();
	return;
}

