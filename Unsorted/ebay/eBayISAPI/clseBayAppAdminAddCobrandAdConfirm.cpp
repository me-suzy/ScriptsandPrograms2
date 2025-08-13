/*	$Id: clseBayAppAdminAddCobrandAdConfirm.cpp,v 1.1.8.1 1999/06/13 21:48:16 wwen Exp $	*/
//
//	File:		clseBayAppAdminAddCobrandAdConfirm.cpp
//
//	Class:		clseBayApp
//
//	Author:		Mila Bird (mila@ebay.com)
//
//	Function:	AdminAddCobrandAdConfirm
//
//
//	Modifications:
//				- 05/31/99 mila		- Created
//


#include "ebihdr.h"


void clseBayApp::AdminAddCobrandAdConfirm(CEBayISAPIExtension *pThis,
										  char *pName,
										  char *pText,
										  eBayISAPIAuthEnum authLevel)
{
	char *pCleanText = NULL;

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

	if (FIELD_OMITTED(pName))
	{
		*mpStream <<	"<h2>Missing Name</h2>\n"
				  <<	"Sorry, you did not enter a name for your ad. Please go back "
				  <<	"and enter a name for your ad."
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (FIELD_OMITTED(pText))
	{
		*mpStream <<	"<h2>Missing Ad Description</h2>\n"
				  <<	"Sorry, you did not enter a description for your ad. Please go "
				  <<	"back and enter a description for your ad."
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Clean the text
	pCleanText = clsUtilities::StripHTML(pText);
	if (pCleanText == NULL)
	{
		*mpStream <<	"<h2>Error Stripping HTML from Description</h2>\n"
				  <<	"Sorry, an error occurred while reading your ad description. "
				  <<	"Please report this problem to <a href=\"mailto:bugs@ebay.com\">"
				  <<	"bugs@ebay.com</a>."
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	// Start the form
	*mpStream <<	"<form method=post action=\""
			  <<	mpMarketPlace->GetCGIPath(PageAdminAddCobrandAd)
			  <<	"eBayISAPI.dll\">"
					"<input type=hidden name=\"MfcISAPICommand\" "
					"value=\"AdminAddCobrandAd\">";

	// Prompt for input verification
	*mpStream <<	"<h2>Review Your Input</h2>\n"
					"Please review the name and description you have entered:"
					"<p>"
					"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
					"<tr>"
					"<td width=\"160\" align=\"right\" bgcolor=\"#EEFFEE\" valign=\"top\">"
					"<strong><font size=\"3\">Name</font></strong><br>"
					"<font size=\"2\" color=\"#006600\">(required)</font>"
					"</td>"
					"<td>"
					"<input type=text name=\"name\" size=\"56\" value=\""
			  <<	pName
			  <<	"\"><br>"
					"<font size=\"2\">(63 characters max)</font>"
					"</td>"
					"</tr>"
					"<tr>"
					"<td width=\"160\" align=\"right\" bgcolor=\"#EEFFEE\" valign=\"top\">"
					"<strong><font size=\"3\">Description</font></strong><br>"
					"<font size=\"2\" color=\"#006600\">(required)</font>"
					"</td>"
					"<td width=\"430\" align=\"left\">"
					"<textarea name=\"text\" cols=\"60\" rows=\"8\">"
			  <<	pCleanText
			  <<	"</textarea><br>"
					"<font size=\"2\">(Must be self-contained and embeddable within HTML)</font>"
					"</td>"
					"</tr>"
					"</table><p>";

	// Display the ad (unless, of course, the HTML syntax is screwed up...)
	*mpStream <<	"<p>The ad should be appear below.  If not, there is probably an "
					"HTML syntax error in your input."
					"<p>"
					"<table width=\"500\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\">"
					"<tr>"
					"<td align=\"center\" colspan=\"2\">"
			  <<	pText
			  <<	"</td>"
					"</tr>"
					"</table>"
					"<p>";

	*mpStream  <<	"<p>"
					"Click "
			  <<	"<input type=submit value=\"submit\">"
					" to save this ad.<br>";

	// End of form
	*mpStream  <<	"</form><br>";

	// Footer
	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	delete [] pCleanText;

	CleanUp();

	return;
}

