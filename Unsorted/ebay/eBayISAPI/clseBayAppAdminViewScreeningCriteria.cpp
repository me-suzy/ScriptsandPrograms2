/*	$Id: clseBayAppAdminViewScreeningCriteria.cpp,v 1.2 1999/05/19 02:34:25 josh Exp $	*/
//
//	File:		clseBayAppAdminViewCriteria.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAdminViewScreeningCriteria
//
//
//	Modifications:
//				- 04/11/99 lou - Created

//	For use with Legal Buddies and Bottom Feeder.


#include "ebihdr.h"

void clseBayApp::AdminViewScreeningCriteria(CEBayISAPIExtension *pThis,
												eBayISAPIAuthEnum authLevel)
{
	CategoryVector		vCategories;
	
	// Setup
	SetUp();	

	// Title			
	EmitHeader("View Screening Criteria");

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Header
	*mpStream	<<	"\n"
					"<h2>View Category Screening Criteria</h2>\n";

	//Start form
	*mpStream	<<	"<form method=post action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminViewScreeningCriteriaShow)
				<<	"eBayISAPI.dll?AdminViewScreeningCriteriaShow\">\n";
					
	// Display some information text
	*mpStream	<<	"<p><b>Select a category:</b><br>\n";

	//Display all the categories and the leafs
	mpCategories->EmitHTMLLeafSelectionList(mpStream, "categoryid", 0,
												NULL,
												NULL,
												&vCategories, true, true);
	// Row for Review
	*mpStream	<<	"<p>Press "
				<<	"<input type=submit value=\"view\">"
				<<	" to display filters and messages currently configured.\n";

	// End form
	*mpStream	<<	"</form>\n";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	//Clean up memory
	vCategories.erase(vCategories.begin(), vCategories.end());

	CleanUp();

	return;

}

