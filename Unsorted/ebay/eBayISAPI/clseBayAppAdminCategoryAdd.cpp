/*	$Id: clseBayAppAdminCategoryAdd.cpp,v 1.8.166.2 1999/08/05 20:41:56 nsacco Exp $	*/
//
//	File:		clsCategoryAdminAppAddCategory.cc
//
//	Class:		clsCategoryAdminApp
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Function: clsCategoryAdminAddCategory does the actual add of a new category
//			  called by clsCategoryAdminVerifyNewCategory.
//
//
//	Modifications:
//				- 06/15/99 petra	- wired off
//				- 07/10/97 tini	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

#define CHECKED(x)	(!strcmp(x,"on"))
/* petra --------------------------------------------------------------
//
// this function does the actual add category action to the database
//
void clseBayApp::AddNewCategory(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPassword,
						  char *pName,
						  char *pDesc,
						  char *pAdult,
						  char *pFeaturedCost,
						  char *pFileRef,
						  char *pCategory,
						  char *pAddAction,
						  eBayISAPIAuthEnum authLevel
							)
{

//	clsCategory *pCategory;
	bool		error	= false;
	double		dFeaturedCost = atof(pFeaturedCost);
	time_t		tStart;
	int			nCategory = atoi(pCategory);
	bool		isLeaf;
	bool		isExpired;
	int			actionCode = atoi(pAddAction);

	clsCategory *pNewCategory;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" New Category Confirmation"
			  <<	"</TITLE></head>"
			  <<	flush;

	// start the page blurb
	*mpStream <<	"<hr width=50%>\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Category Administration</h2>"
			  <<	"<br>\n";
	
	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}
	// Let's revalidate the data
	error	= CheckCategoryData(pName,
						pDesc,
						pAdult,
						pFeaturedCost,
						pFileRef,
						pCategory,
						pAddAction,
						Currency_USD); // For now, all fees are in USD

	// Let's see if we need to leave now
	if (error)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	isLeaf = true;
	isExpired = false;
	time(&tStart);

	pNewCategory	= new clsCategory	(
				   gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
				   0,
				   pName,				// Name
				   pDesc,				// Description
				   pAdult[0],				// Private?
				   isLeaf,				// leaf?
				   isExpired,			// expired?
				   0,					// default initialize
				   0,
				   0,
				   0,
				   0,
				   0,
				   0,
				   0,
				   0,
				   0,
				   dFeaturedCost,
				   (long) tStart,
				   pFileRef,
				   (long) tStart,
				   false,
				   false
				 );

	// addAction code: 
	if (actionCode == 1) // insert child
		mpMarketPlace->GetCategories()->AddCategoryChild(pNewCategory, nCategory);
	else if (actionCode == 2) // add after
		mpMarketPlace->GetCategories()->AddCategoryAfter(pNewCategory, nCategory);
				 
	 // blurbs
 	*mpStream <<	"<h3>New Category Added.</h3>"
			<<	"Your new category: ";
	mpCategories->EmitHTMLQualifiedName(mpStream, pNewCategory);
	*mpStream		<<	" has been saved.";

	*mpStream	  <<	"<p>"
				  <<	"<A href=\""
				  <<	mpMarketPlace->GetCGIPath(PageCategoryAdminRun)
				  <<	"eBayISAPI.dll?CategoryAdmin"
				  <<	"\">"
						"Return to Category Administration page"
						"</A>"
						"<br>\n";

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	delete pNewCategory;
	CleanUp();

}
--------------------------------------------------- petra */