/*	$Id: clseBayAppAdminCategoryView.cpp,v 1.7.94.1.100.2 1999/08/05 20:41:59 nsacco Exp $	*/
//
//	File:		clsCategoryAdminViewCategory.cc
//
//	Class:		clsCategoryAdminApp
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Function: View and Update functions.
//
//
//	Modifications:
//				- 06/15/99 petra	- wired off
//				- 07/10/97 tini	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#define CHECKED(x)	(!strcmp(x,"on"))
/* petra --------------------------------------------------
//
// view category details
//
void clseBayApp::ViewCategory(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel)
{
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
			  <<	" View Category"
			  <<	"</TITLE></head>"
			  <<	flush;

	// start the page blurb
	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<h2>Category Administration</h2>"
			  <<	"<hr width=50%>\n"
			  <<	"<br>\n";
	
	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// start the page blurb
	*mpStream <<	"<h3>View and Update Category Details</h3>\n"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageVerifyUpdateCategory)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"VerifyUpdateCategory\">"
					"<p>\n"
					"<strong>Your "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	"</strong>: is required to update a category. \n"
					"<blockquote><input type=text name=email size=45></blockquote>\n"
					"<p>\n"
					"<strong>Your registered user password:</strong> "
					"A password is required to add a new category. If you have forgotten your password, click <a href=\"";
	*mpStream <<	mpMarketPlace->GetHTMLPath();
	*mpStream <<	"services/buyandsell/reqpass.html\">\n"
					"here</a>.\n"
					"<blockquote><input type=password name=pass size=45></blockquote>\n"
					"<p>\n"

					"<strong>Select Category to view or update:</strong>";

	// Emit Category choice list
	mpCategories->EmitHTMLCategoriesRadio(mpStream,
											"category",
											&vCategories, true);

	*mpStream  <<   "<p>\n"
					"<strong>Press this button to view details of your Category:</strong>"
					"<blockquote><input type=submit value=\"View\"></blockquote>"
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

// emit the category detail page
void clseBayApp::VerifyUpdateCategory(CEBayISAPIExtension *pServer,
						  char *pEmail,
						  char *pPass,
						  char *pCategory,
						  eBayISAPIAuthEnum authLevel,
						  int currencyId)
{
	bool		error	= false;
	int			nCategory;
	double		maxSize;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	maxSize = mpMarketPlace->GetMaxAmountSize(currencyId);

	// Usual Title and Header
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Update Category"
			  <<	"</TITLE></head>"
			  <<	flush;

	// start the page blurb
	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Category Administration</h2>"
			  <<	"<hr width=50%>\n"
			  <<	"<br>\n";

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	mpUser	= mpUsers->GetAndCheckUserAndPassword(pEmail, pPass, mpStream);

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
	if (!FIELD_OMITTED(pCategory))
		nCategory	= atoi(pCategory);
	else
		nCategory = 0;

	// Let's try and get the category
	if (!GetAndCheckCategory(pCategory))
	{
		CleanUp();
		return;
	}

	// Let's see if we need to leave now
	if (error)
	{
		*mpStream	<<	"<p>\n"
					<<  "Error. No Changes are made. "
					<<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// display category details; 
	// for each field, insert text fields for users' changes and flags to
	// indicate change of field

	*mpStream <<	"<p><form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageUpdateCategory)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"UpdateCategory\">"
					"\n"
					"<input type=hidden name=email value=\""
			  <<	pEmail
			  <<	"\">\n"
					"<input type=hidden name=pass value=\""
			  <<	pPass
			  <<	"\">\n"
					"<input type=hidden name=category value=\""
			  <<	pCategory
			  <<	"\">\n"
					"<p><p>\n\n";

	*mpStream <<	"<p>\nPlease enter your changes and check the fields you changed."
					"Once you are satisfied with the entry, please "
					"press the submit button.\n";

	*mpStream <<	"<p>\n"
					"Your e-mail address:          <strong>"
			  <<	pEmail
			  <<	"</strong>\n";

	*mpStream <<	"<p>\n" 
			  <<    "Category Name: "
			  <<	"<blockquote><input type=text size=20 name=cname value= \""
			  <<	mpCategory->GetName()
			  <<	"\"></blockquote>\n";

	*mpStream <<	"<p>\n"
			  <<	"Category description:"
					"<blockquote><textarea name=cdesc cols=70 rows=10>"
			  <<	mpCategory->GetDescription()
			  <<    "</textarea></blockquote>";

	*mpStream <<	"<p>\n"
			  <<	"File Reference:        "
			  <<	"<blockquote><input type=text size=60 name=fileref value = \""
			  <<	mpCategory->GetFileRef()
			  <<	"\"></blockquote>\n";

	*mpStream <<	"<p>\n"
			  <<	"Featured Price:            $"
					"<blockquote><input type=text name=featuredcost "
					"size=" << maxSize << " "
					"maxlength=" << maxSize << " "
					"value = \""
			  <<	mpCategory->GetFeaturedCost()
			  <<	"\"></blockquote>\n";

	*mpStream <<	"<p><p>\n\n";

	*mpStream <<  "Category Type: <input type=\"radio\" name=\"adult\" value=\"0\" ";
	if (mpCategory->GetAdult() != '1' && mpCategory->GetAdult() != '2')
		*mpStream <<	"CHECKED";
	*mpStream <<	">General&nbsp;&nbsp;\n";

	*mpStream <<  "<input type=\"radio\" name=\"adult\" value=\"1\" ";
	if (mpCategory->GetAdult() == '1')
		*mpStream <<  "CHECKED";
	*mpStream <<  ">Adult&nbsp;&nbsp;\n";

	*mpStream <<  "<input type=\"radio\" name=\"adult\" value=\"2\" ";
	if (mpCategory->GetAdult() == '2')
		*mpStream <<  "CHECKED";
	*mpStream <<  ">Not bid and list for minor\n";

	*mpStream <<	"<p><p>\n\n";

	if (mpCategory->GetIsExpired())
		*mpStream << "<input type=\"checkbox\" name=\"expired\" value=\"1\"CHECKED>Expired Category\n";
	else
		*mpStream << "<input type=\"checkbox\" name=\"expired\" value=\"1\">Expired Category\n";


	*mpStream <<	"<p><p>\nClick this button to submit your updates. <a href=\"";
	*mpStream <<	mpMarketPlace->GetHTMLPath();
	*mpStream <<	"\">Click here if you wish to cancel.</a><br>\n"
			  <<	"<input type=submit value=\"Update Category\"></blockquote><p>\n";

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	CleanUp();

};

// updates category detail page
void clseBayApp::UpdateCategory(CEBayISAPIExtension *pServer,
						  char *pEmail,
						  char *pPass,
						  char *pCategory,
						  char *pName,
						  char *pDesc,
						  char *pFileRef,
						  char *pFeaturedCost,
						  char *pAdult,
						  char *pExpired,
						  eBayISAPIAuthEnum authLevel
						  )
{
	bool		error	= false;

	int			nExpired;
	double		dFees;

	time_t		tStart;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Update Category Confirmation"
			  <<	"</TITLE></head>"
			  <<	flush;

	// start the page blurb
	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Category Administration</h2>"
			  <<	"<hr width=50%>\n"
			  <<	"<br>\n";
	
	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	mpUser	= mpUsers->GetAndCheckUserAndPassword(pEmail, pPass, mpStream);

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

	error = CheckCategoryName(pName);

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
	if (!FIELD_OMITTED(pName))
		mpCategory->SetName(pName);
	
	if (!FIELD_OMITTED(pDesc))
		mpCategory->SetDescription(pDesc);

	if (!FIELD_OMITTED(pFileRef))
		mpCategory->SetFileRef(pFileRef);

	mpCategory->SetAdult(pAdult[0]);

	if (!FIELD_OMITTED(pFeaturedCost))
	{
		dFees	= atof(pFeaturedCost);
		mpCategory->SetFeaturedCost(dFees);
	}
	else
		dFees	= 0;
	

	if (!FIELD_OMITTED(pExpired))
		nExpired	= atoi(pExpired);
	else
		nExpired	= 0;

	if (nExpired == 0)
		mpCategory->SetIsExpired(false);
	else
		mpCategory->SetIsExpired(true);

	time(&tStart);


	mpCategory->UpdateCategory();

	 // blurbs
 	*mpStream <<	"<h3> Category Updated Information.</h3>\n";

	*mpStream <<	"<p>\n"
			  <<	"The category name " ;
	*mpStream <<	"<strong>"
			  <<	mpCategory->GetName();
	*mpStream <<    " ( "
			  <<	pCategory
			  <<	" )"
			  <<	"</strong>\n";

	*mpStream <<	""
			  <<	"<p>\n"
			  <<	"The description of the category:"
			  <<	"<blockquote>"
			  <<	pDesc
			  <<	"</strong></em></blink></font></center></h1></blockquote>\n";

	*mpStream <<	"<p>\n"
			  <<	"Featured Cost:            <strong>$"
			  <<	mpCategory->GetFeaturedCost()
			  <<	"</strong>\n";

	*mpStream <<	"<p>\n"
			  <<	"File Reference:			<strong>"
			  <<	mpCategory->GetFileRef()
			  <<	"<br></strong>\n";

	*mpStream <<	"<p>\nCategoryType: <strong>";
	if (pAdult[0] == '1')
		*mpStream << "Adult";
	else if (pAdult[0] == '2')
		*mpStream << "Moinor can view but no bid and list";
	else
		*mpStream << "General";
	*mpStream <<	"</strong>\n";

	if (CHECKED(pExpired))
		*mpStream <<	"<p>\nExpired:               <strong>yes</strong>\n";
	else
		*mpStream <<	"<p>\nExpired:               <strong>no</strong>\n";

	*mpStream	  <<	"<p>"
				  <<	"<A href=\""
				  <<	mpMarketPlace->GetCGIPath(PageCategoryAdminRun)
				  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"CategoryAdmin\">"
						"Return to Category Administration page"
						"</A>"
						"<br>\n";

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;


	CleanUp();
	
};
--------------------------------------------- petra */