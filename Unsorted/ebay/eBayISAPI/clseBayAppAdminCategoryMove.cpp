/*	$Id: clseBayAppAdminCategoryMove.cpp,v 1.5.204.1.100.2 1999/08/05 20:41:57 nsacco Exp $	*/
//
//	File:		clsCategoryAdminMoveCategory.cc
//
//	Class:		clsCategoryAdminApp
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Function:
//	MoveCategory - emits a move category page; allows a user to move items and
//  user interests from one category to another;
//
//  MakeMove - does the actual checks and moves the category items to another
//  category.
//
//	Modifications:
//				- 06/15/99 petra	- wired off
//				- 07/10/97 tini	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//


#include "ebihdr.h"

// Typedefs
typedef vector<clsCategory *> CategoryVector;

#define CHECKED(x)	(!strcmp(x,"on"))
/* petra --------------------------------------------------------
//
// emits a move category page
//
void clseBayApp::MoveCategory(CEBayISAPIExtension *pServer,
						    eBayISAPIAuthEnum authLevel
						)
{
	CategoryVector vCategories;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Move Category "
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

	// start the page blurb
	*mpStream <<	"<h3>Move a Category's items to another Category</h3>\n"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageMakeMove)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"MakeMove\">"
					"<p>\n"
					"<strong>Your "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	"</strong>: is required to move items from a category to another. "
					"<blockquote><input type=text name=userid size=45></blockquote>\n"
					"<p>\n"
					"<strong>Your registered user password</strong>: "
					"A password is required to add a new category. If you have forgotten your password, click <a href=\"";
	*mpStream <<	mpMarketPlace->GetHTMLPath();
	*mpStream <<	"services/buyandsell/reqpass.html\">\n"
					"here</a>.\n"
					"<blockquote><input type=password name=pass size=45></blockquote>\n"
					"<p>\n";

	*mpStream   <<  "<p>"
					"<strong>Select Category whose items you want to move from: </strong> <br>";
	
	// Emit Category choice list; first time calls AllSorted Categories
	mpCategories->EmitHTMLCategoriesRadio(mpStream,
											"fromcategory",
											&vCategories, true);

	*mpStream   <<  "<p>\n"
					"<strong>Select Category whose items you want to move to: </strong> <br>";
	
	// Emit Category choice list; reusing categories in the vector
	mpCategories->EmitHTMLCategoriesRadio(mpStream,
											"tocategory",
											&vCategories, true);

	*mpStream   <<  "<p>\n"
					"<strong>Press this button to move items in your Category:</strong>"
					"<blockquote><input type=submit value=\"move\"></blockquote>"
					"<p>\n"
					"Press this button to clear the form if you made a mistake:"
					"<blockquote><input type=reset value=\"clear form\"></blockquote>"
					"</form>";

	*mpStream <<	"\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	vCategories.erase(vCategories.begin(), vCategories.end());

	CleanUp();

	return;
	
};


//
// does the actual moving of items after checking input validity
//
void clseBayApp::MakeMove(CEBayISAPIExtension *pServer,
							char *pUserId,
							char *pPass,
							char *pFromCategory,
							char *pToCategory,
						    eBayISAPIAuthEnum authLevel
						)
{
	bool		error	= false;
	int			nFromCategory;
	int			nToCategory;
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
			  <<	" Move Category Items Confirmation"
			  <<	"</TITLE></head>"
			  <<	flush;

	// start the page blurb
	*mpStream <<	"<hr width=50%>\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Category Administration</h2>\n"
			  <<	"<br><h3>Move Category Items Confirmation</h3>\n"
			  <<	"<br>\n";
	
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

	// some data conversions now
	if (!FIELD_OMITTED(pFromCategory))
		nFromCategory	= atoi(pFromCategory);
	else
		nFromCategory = 0;

	// some data conversions now
	if (!FIELD_OMITTED(pToCategory))
		nToCategory	= atoi(pToCategory);
	else
		nToCategory = 0;

	// Let's try and get the category
	if (!GetAndCheckCategory(pToCategory))
	{
		CleanUp();
		return;
	}
	pNewCategory = mpCategory;

	// Let's try and get the category
	// do this after GetAndCheckCategory of pToCategory, 
	// so mpCategory is set to the Category its being
	// moved to.
	if (!GetAndCheckCategory(pFromCategory))
	{
		CleanUp();
		return;
	}

	// do the actual move if possible; emits a message to the user
	mpCategories->MoveCategoryItems(mpCategory, nToCategory);

	*mpStream <<	"<p>\n"
			  <<	"Category items has been moved from ";
	mpCategories->EmitHTMLQualifiedName(mpStream, mpCategory);

	*mpStream <<	" to \n";

	mpCategories->EmitHTMLQualifiedName(mpStream, pNewCategory);

	*mpStream  <<	"<p>"
			  <<	"<A href=\""
			  <<	mpMarketPlace->GetCGIPath(PageCategoryAdminRun)
			  <<	"eBayISAPI.dll?CategoryAdmin"
			  <<	"\">"
					"Return to Category Administration page"
					"</A>"
					"<br>\n";

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();
	return;

};
----------------------------------------------- petra */