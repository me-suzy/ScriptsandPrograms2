/*	$Id: clseBayAppAdminAddCobrandAdShow.cpp,v 1.1.8.1 1999/06/13 21:48:16 wwen Exp $	*/
//
//	File:		clseBayAppAdminAddCobrandAdShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Mila Bird (mila@ebay.com)
//
//	Function:	AdminAddCobrandAdShow
//
//
//	Modifications:
//				- 05/31/99 mila		- Created
//


#include "ebihdr.h"
#include "clsAds.h"

//static const int kAddAction = 0;
//static const int kModifyAction = 1;
//static const int kDeleteAction = 2;


void clseBayApp::AdminAddCobrandAdShow(CEBayISAPIExtension *pThis,
									   eBayISAPIAuthEnum authLevel)
{
	// Setup
	SetUp();	
				
	// Title
	EmitHeader("Create Cobrand Ad");

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	*mpStream <<	"<h2>Create Cobrand Ad</h2>";

		*mpStream <<	"As you enter a name and description for your ad, please keep "
					"these points in mind:<br>"
					"<li>The ad name must be unique.</li>"
					"<li>The ad description must be self-contained in that it must, "
					"by itself, be capable of displaying the ad when embedded within "
					"static or dynamically-generated HTML text.</li><br>";

	// Start the form
	*mpStream <<	"<form method=post action=\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminAddCobrandAdConfirm)
			  <<	"eBayISAPI.dll\">"
					"<input type=hidden name=\"MfcISAPICommand\" "
					"value=\"AdminAddCobrandAdConfirm\">";

	// Emit the input fields
	*mpStream <<	"<p>"
					"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
					"<tr>"
					"<td width=\"160\" align=\"right\" bgcolor=\"#EEFFEE\" valign=\"top\">"
					"<strong><font size=\"3\">Name</font></strong><br>"
					"<font size=\"2\" color=\"#006600\">(required)</font>"
					"</td>"
					"<td>"
					"<input type=text name=\"name\" size=\"56\"><br>"
					"<font size=\"2\">(63 characters max)</font>"
					"</td>"
					"</tr>"
					"<tr>"
					"<td width=\"160\" align=\"right\" bgcolor=\"#EEFFEE\" valign=\"top\">"
					"<strong><font size=\"3\">Description</font></strong><br>"
					"<font size=\"2\" color=\"#006600\">(required)</font>"
					"</td>"
					"<td width=\"430\" align=\"left\">"
					"<textarea name=\"text\" cols=\"60\" rows=\"8\"></textarea><br>"
					"<font size=\"2\">(Must be self-contained and embeddable within HTML)</font>"
					"</td>"
					"</tr>"
					"</table><br>";

	// Display submit button
	*mpStream	<<	"<p>Press "
				<<	"<input type=\"submit\" value=\"submit\">"
					" to continue.</p>";

	// Display clear button
	*mpStream	<<	"<P>Press "
					"<input type=\"reset\" value=\"clear\" name=\"reset\">"
				<<	" to clear the form and start over.</p>\n";

	// End form
	*mpStream	<<	"</form>";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}

