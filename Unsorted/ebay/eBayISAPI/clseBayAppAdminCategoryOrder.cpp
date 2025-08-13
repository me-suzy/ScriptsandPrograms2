/*	$Id: clseBayAppAdminCategoryOrder.cpp,v 1.5.394.2 1999/08/05 20:41:58 nsacco Exp $	*/
//
//	File:		clsCategoryAdminOrderCategory.cc
//
//	Class:		clsCategoryAdminApp
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Function:
//	OrderCategory - sorts the order of the categories in the database.
//
//	Modifications:
//				- 09/16/97 tini	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 06/15/99 petra	- wired off
//


#include "ebihdr.h"
#define CHECKED(x)	(!strcmp(x,"on"))
/* petra ----------------------------------------------------
//
// emits a move category page
//
void clseBayApp::OrderCategory(CEBayISAPIExtension *pServer,
						    eBayISAPIAuthEnum authLevel
						)
{
	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Order Category "
			  <<	"</TITLE></head>"
			  <<	flush;

	// start the page blurb
	*mpStream <<	"<hr width=50%>\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Category Administration</h2>"
			  <<	"<br>\n";
	
	// start the page blurb
	*mpStream <<	"<h3>Reorder Category in the database</h3>\n";

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	mpCategories->SortAndOrder();

	*mpStream <<	"\n Done.\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	CleanUp();

	return;
	
};
---------------------------------------------- petra */