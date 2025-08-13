/*	$Id: clseBayAppAdminCategoryDelete.cpp,v 1.5.204.1.100.2 1999/08/05 20:41:56 nsacco Exp $	*/
//
//	File:		clsCategoryAdminDeleteCategory.cc
//
//	Class:		clsCategoryAdminApp
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Functions: clsCategoryAdminDeleteCategory
//				clsCategoryAdminVerifyDelete
//
//
//	Modifications:
//				- 06/15/99 petra	- wired off
//				- 07/10/97 tini	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

#define CHECKED(x)	(!strcmp(x,"on"))
/* petra ----------------------------------------------------------
//
// emits an "what do you want to delete?" page
//
void clseBayApp::DeleteCategory(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel
						)
{
	// deletes a leaf category; must have no items and no children categories
	//	clsCategory *pCategory;
	bool		error	= false;
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
			  <<	" Delete Category"
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

	*mpStream <<	"<h3>Delete a Category</h3>\n"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageMakeDelete)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"MakeDelete\">"
					"\n"
					"<p>"
					"<p>\n"
			  	    "<strong>Your "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	"</strong>: is required to delete a category. ";
					"<blockquote><input type=text name=userid size=45></blockquote>\n"
					"<p>\n"
					"<strong>Your registered user password</strong>: "
					"A password is required to add a new category. If you have forgotten your password, click <a href=\"";
	*mpStream <<	mpMarketPlace->GetHTMLPath();
	*mpStream <<	"services/buyandsell/reqpass.html\">"
					"here</a>.\n"
					"<blockquote><input type=password name=pass size=45></blockquote>\n"
					"<p>\n"

					"<strong>Select Category to delete:</strong>";

	// Emit Category choice list
	mpCategories->EmitHTMLCategoriesRadio(mpStream,
											"category",
											&vCategories, true);

	*mpStream  <<   "<p>\n"
					"You are about to delete a Category. Deleting a category is an <strong> irreversible "
					"operation</strong>. There will be no review page; the category will be deleted immediately."
					"<p>\n"
					"<strong>Press this button to delete your Category:</strong>"
					"<blockquote><input type=submit value=\"Delete\"></blockquote>"
					"<p>"
					"Press this button to clear the form if you made a mistake:"
					"<blockquote><input type=reset value=\"clear form\"></blockquote>"
					"</form>";

	*mpStream <<	""
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	vCategories.erase(vCategories.begin(), vCategories.end());

	CleanUp();

	return;
}

//
// Does actual deletion of the page
//
void clseBayApp::MakeDelete(CEBayISAPIExtension *pServer,
							char *pUserId,
							char *pPass,
							char *pCategoryId,
						    eBayISAPIAuthEnum authLevel
						)
{
	// deletes a leaf category; must have no items and no children categories
	//	clsCategory *pCategory;
	bool		error	= false;
	int			nCategory;

	CategoryVector				vCategories;
	CategoryVector::iterator	i;
	ItemVector					vItems;
	ItemVector::iterator		j;

	// Setup
	SetUp();

 	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Delete Category Confirmation"
			  <<	"</TITLE></head>"
			  <<	flush;

	// start the page blurb
	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Category Administration</h2>"
			  <<	"<hr width=50%>\n"
			  <<	"<br>\n";

	*mpStream << "<h3>Delete Category Confirmation</h3>"
			  << "<br>\n";

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
		*mpStream <<	"<p>"
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
	if (!FIELD_OMITTED(pCategoryId))
		nCategory	= atoi(pCategoryId);
	else
		nCategory = 0;

	// Let's try and get the category
	if (!GetAndCheckCategory(pCategoryId))
	{
		CleanUp();
		return;
	}

	// do the actual deletion if possible
	// this category should not have any items or children
	mpCategories->Children(&vCategories, mpCategory);

	mpCategory->GetItems(&vItems, 0);  // any date?

	if ((vCategories.begin() != vCategories.end()) ||
		(vItems.begin() != vItems.end()))
	{
		*mpStream <<	"<p>\n"
				  <<	"Cannot delete category because it has children or items listed. "
				  <<	"Please delete children or remove items from the category. ";
		*mpStream <<	"<p>\n"
				  <<	mpMarketPlace->GetFooter();

	// go to cleanup
	}
	else
	{  // deletable
		mpCategories->DeleteCategory(mpCategory);
		*mpStream <<	"<p>\n"
				  <<	"Category has been deleted. ";

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

	// go to cleanup
	}

	// clean up category vector
	for (i = vCategories.begin();
	     i != vCategories.end();
	     i++)
	{
		// Delete the category
		delete	(*i);
	}

	vCategories.erase(vCategories.begin(), vCategories.end());

	// clean up item vector
	for (j = vItems.begin();
	     j != vItems.end();
	     j++)
	{
		// Delete the category
		delete	(*j);
	}

	vItems.erase(vItems.begin(), vItems.end());

	CleanUp();
}


--------------------------------------------------- petra */