//
//	File:	clseBayAppReportQuestionableItemShow.cpp
//
//	Class:	clseBayApp
//
//	Author:	Steve Yan (stevey@ebay.com)
//
//	Function:
//
//		The form which is used to report a questionable item to support
//
// Modifications:
//				- 04/05/99 Steve	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"


void clseBayApp::ReportQuestionableItemShow(CEBayISAPIExtension *pThis,
							  int itemID)
							  
{

	SetUp();


	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" E-mail questionable item to support show"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";


	// Let's try and get the item
	if (!GetAndCheckItem(itemID))
	{
		// Item does not exist
		*mpStream	<< "<P>"
					<<	mpMarketPlace->GetFooter()
					<< flush;
		CleanUp();
		return;
	}

	*mpStream <<	"<center>"
					"<table border=1 cellspacing=0 "
					"width=\"100%\" bgcolor=\"#99CCCC\">\n"
					"<tr>\n"
					"<td align=center width=\"100%\">"
					"<font size=4 color=\"#000000\">"
					"<b>";

	*mpStream <<"E-mail Questionable Item to Support"	
				"</b></font></td>\n"
				"</tr>\n"
				"</table></center>\n";



	*mpStream	<<	"<form method=post action=\""			  
				<<	mpMarketPlace->GetCGIPath(PageReportQuestionableItem)
				<<	"eBayISAPI.dll"					
				<<	"\">\n"		
				<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"ReportQuestionableItem\">\n"
				<<	"<input type=\"hidden\" name=\"item\" value=\"" << itemID << "\">"
					"<table border=\"1\" width=\"590\" cellspacing=\"0\" cellpadding=\"4\">\n"
					"<tr>\n"
					"<td width=\"170\" bgcolor=\"#EFEFEF\">";



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

	// Item type dropdown box
	*mpStream	<<  "<tr>\n"
					"<td width=\"170\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"><strong>Type of Item</strong> <br>\n"
					"</font></td>\n"
					"<td width=\"420\">";


	EmitDropDownList(mpStream,
				 "itemtype",
				 (DropDownSelection *)&ItemTypeSelection,
				 NULL,
				 "default",
				 "Please select one");
					
	*mpStream	<<  "</td>\n"
					"</tr>";

	// Title of Item
	*mpStream	<<  "<tr>\n"
					"<td width=\"170\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"><strong>Title of Item</strong> <br>\n"
					"</font></td>\n";

	*mpStream	<< "<td width=\"420\">"
				<< mpItem->GetTitle()
				<< "</td>\n"
					"</tr>";

	// ID of Item
	*mpStream	<<  "<tr>\n"
					"<td width=\"170\" bgcolor=\"#EFEFEF\"><font color=\"#006600\"><strong>Item ID</strong> <br>\n"
					"</font></td>\n";

	*mpStream	<< "<td width=\"420\">"
				<< itemID 
				<< "</td>\n"
					"</tr>";


		
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
	return;
}
