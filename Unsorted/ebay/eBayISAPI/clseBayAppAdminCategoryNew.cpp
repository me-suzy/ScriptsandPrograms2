/*	$Id: clseBayAppAdminCategoryNew.cpp,v 1.7.94.1.108.3 1999/08/05 20:41:57 nsacco Exp $	*/
//
//	File:		clsCategoryAdminNewItem.cc
//
//	Class:		clsCategoryAdminApp
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 07/09/97 tini	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

#define CHECKED(x)	(!strcmp(x,"on"))

/*
void clseBayApp::NewCategory(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel,
						  int currencyId)
{
	CategoryVector vCategories;
	int			   maxSize;
	

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
			  <<	" New Category "
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

	// start the page blurb
	*mpStream <<	"<h3>Create New Category</h3>\n"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageVerifyNewCategory)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"VerifyNewCategory\">"
					"\n"
					"<p>\n"
					"<strong>Your "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	"</strong>: is required to enter a new category. "
					"<blockquote><input type=text name=userid size=45></blockquote>\n"
					"<p>\n"
					"<strong>Your registered user password</strong>: "
					"A password is required to add a new category. If you have forgotten your password, click <a href=\"";
	*mpStream <<	mpMarketPlace->GetHTMLPath();
	*mpStream <<	"services/buyandsell/reqpass.html\">"
					"here.</a>\n"
					"<blockquote><input type=password name=pass size=45></blockquote>\n"
					"<p>\n";

	*mpStream <<	"<font size=+0>"
					"<p>\n"
					"<strong>Name of the category.</strong> "
					"Please don't use <strong>quotes</strong> or <strong> <, > </strong> in the name. "
					"<blockquote><input type=text size=20 name=cname></blockquote>\n"
					"<p>\n"
					"<strong>Description of the category.</strong> "
					"<blockquote><textarea name=cdesc cols=70 rows=10></textarea></blockquote>"
					"<p>\n"
					"<strong>File Reference</strong> If you have a category specific template "
					"to include in the category listing display, "
					"you may enter the file path here, and it will be included automatically."
					"<p>\n"
					"File Reference: <blockquote><input type=text size=60 name=fileref></blockquote>"
					"<p>\n"
					"<strong>Featured Price.</strong> This is the cost of featuring any item "
					"in the category. "
					"Please enter only numbers and the decimal point (if required) in this field. Do not include "
					"a dollar sign (\'$\') or other currency symbol."
					"<p>\n"
					"Featured price (optional): "
					"<blockquote><input type=text name=featuredcost "
					"size=" << maxSize << " "
					"maxlength=" << maxSize << " "
					"></blockquote>"
					"<p>\n"
					"<strong>Type of Category.</strong>"
					"<blockquote>"
					"<input type=\"radio\" name=\"adult\" value=\"0\" checked>General&nbsp;&nbsp;"
					"<input type=\"radio\" name=\"adult\" value=\"1\">Adult Category&nbsp;&nbsp;"
					"<input type=\"radio\" name=\"adult\" value=\"2\">No bid and list for minor"
					"</blockquote>"
					"<p>\n"
					"<strong>Select insertion point (insert after, or insert child with this category):</strong>";

	// Emit Category choice list
	mpCategories->EmitHTMLCategoriesRadio(mpStream,
											"category",
											&vCategories, true);

	*mpStream   <<  "<p>\n"
					"<strong>Insertion point: </strong> <br>"
					"<INPUT TYPE = \"radio\" NAME=\"addaction\" VALUE=\"1\" CHECKED>"
					"Insert Child"
					"<INPUT TYPE = \"radio\" NAME=\"addaction\" VALUE=\"2\" CHECKED>"
					"Insert After"
					"<p>\n"
					"<strong>Press this button to review and create your Category:</strong>"
					"<blockquote><input type=submit value=\"review\"></blockquote>"
					"<p>\n"
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
*/