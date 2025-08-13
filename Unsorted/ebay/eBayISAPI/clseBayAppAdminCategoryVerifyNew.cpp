/*	$Id: clseBayAppAdminCategoryVerifyNew.cpp,v 1.7.278.3 1999/08/05 20:41:58 nsacco Exp $	*/
//
//	File:		clsCategoryAdminVerifyNewCategory.cc
//
//	Class:		clsCategoryAdminApp
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 06/15/99 petra	- wired off 'new' function
//				- 07/10/97 tini	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#define CHECKED(x)	(!strcmp(x,"on"))

/*
void clseBayApp::VerifyNewCategory(
						  CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
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
	bool		error	= false;

	int			nCategory;
	int			nAction;
	double		dFees;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<html></head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" New Category Verification"
			  <<	"</TITLE></head>"
			  <<	flush;

	// start the page blurb
	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Category Administration</h2>"
			  <<	"<hr width=50%>\n"
			  <<	"<br>\n";

	*mpStream <<	"<h3>New Category Verification</h3>\n";
		
	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	// If we didn't get the user, we're done
	if (!mpUser)
	{   *mpStream <<    "Not a valid user or password.";
		*mpStream <<	"<p>\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if the user can administer categories
	if (!mpUser->HasAdmin(Category))
	{
		*mpStream <<	"<p>"
						"You do not have Category Administration privileges."
						"<p>\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

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
		*mpStream	<<	"<p>\n"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's try and get the category
	if (!GetAndCheckCategory(pCategory))
	{
		*mpStream	<<	"<p>\n"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// everything is cool. Do some conversions
	if (FIELD_OMITTED(pDesc))
		pDesc = "";

	if (FIELD_OMITTED(pFileRef))
		pFileRef = "";

	if (!FIELD_OMITTED(pCategory))
		nCategory	= atoi(pCategory);
	else
		nCategory = 0;

	if (!FIELD_OMITTED(pFeaturedCost))
		dFees	= atof(pFeaturedCost);
	else
		dFees	= 0;

	if (!FIELD_OMITTED(pAddAction))
		nAction	= atoi(pAddAction);
	else
		nAction	= 0;

	*mpStream <<	"<p>Please don't use this page for category maintenance.\n";
* petra ---------------------------------
	*mpStream <<	"<p>Please verify your entry as it appears below. If there are any \n"
					"errors, please use the back button on your browser to go back and \n"
					"correct your entry. Once you are satisfied with the entry, please \n"
					"press the submit button.\n";

	*mpStream <<	"<p>"
					"Your User Id:                 <strong>"
			  <<	"<blockquote>"
			  <<	pUserId
			  <<	"</blockquote>"
			  <<	"</strong>\n"
					"The new Category Name:        <strong>"
			  <<	"<blockquote>"
			  <<	pName
			  <<	"</blockquote>"
			  <<	"</strong>\n";
	
	*mpStream <<	"Category Type: <blockquote><strong>";
	if (pAdult[0] == '1')
		*mpStream <<	"Adult";
	else if (pAdult[0] == '2')
		*mpStream <<	"No bid and list for minor";
	else
		*mpStream <<	"General";
	*mpStream <<	"</strong></blockquote>\n";

	*mpStream <<	"The category will be inserted " ;
	if (nAction == 1)
		*mpStream << "as child of ";
	else
		*mpStream << "after ";

	*mpStream <<	"<strong>"
			  <<	"<blockquote>";

	mpCategories->EmitHTMLQualifiedName(mpStream, mpCategory);

	*mpStream <<    " ( "
			  <<	pCategory
			  <<	" )"
			  <<	"</blockquote>"
			  <<	"</strong>\n";

	*mpStream <<	"Featured Cost: <blockquote> <strong>$"
			  <<	dFees
			  <<	"<blockquote></strong>\n";

	*mpStream <<	"The description of the category:"
			  <<	"<blockquote><strong>"
			  <<	pDesc
			  <<	"</strong></em></blink></font></center></h1></pre></blockquote>"
			  <<	"<hr width=50%>\n";

	*mpStream <<	"<p><form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAddNewCategory)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"AddNewCategory\">"
					"\n"
					"<input type=hidden name=userid value=\""
			  <<	pUserId
			  <<	"\">\n"
					"<input type=hidden name=pass value=\""
			  <<	pPass
			  <<	"\">\n"
			 		"<input type=hidden name=cname value=\""
			  <<	pName
			  <<	"\">\n"
					"<input type=hidden name=cdesc value=\""
			  <<	pDesc
			  <<	"\">\n"
					"<input type=hidden name=adult value=\""
			  <<	pAdult[0]
			  <<	"\">\n"
					"<input type=hidden name=featuredcost value=\""
			  <<	dFees
			  <<	"\">\n"
					"<input type=hidden name=fileref value=\""
			  <<	pFileRef
			  <<	"\">\n"
					"<input type=hidden name=category value=\""
			  <<	pCategory
			  <<	"\">\n"
					"<input type=hidden name=addaction value=\""
			  <<	nAction
			  <<	"\">\n"
					"<p><p>\n\n";

	*mpStream <<	"Click this button to submit your new category. <a href=\"";
	*mpStream <<	mpMarketPlace->GetHTMLPath();
	*mpStream <<	"\">Click here if you wish to cancel.</a><br>\n"
			  <<	"<input type=submit value=\"Add Category\"></blockquote><p>";
 -------------------------------------------- petra *

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;


	CleanUp();



}
*/