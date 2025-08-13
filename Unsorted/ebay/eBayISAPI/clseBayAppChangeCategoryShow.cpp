/*	$Id: clseBayAppChangeCategoryShow.cpp,v 1.7.54.9.14.1 1999/08/01 03:01:08 barry Exp $	*/
//
//	File:		clsItemChangeCategoryShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 06/14/97 michael	- Created
//				- 05/13/99 bill     - Added a new style of category selector
//				- 07/19/99 nsacco	- Changed title

#include "ebihdr.h"

void clseBayApp::ChangeCategoryShow(CEBayISAPIExtension *pServer,
									int item, bool oldStyle)
{
	char			cItem[EBAY_MAX_ITEM_SIZE + 1];
	char			*pCategory = NULL;
	CategoryVector	vCategories;
	clsCategory		*pDefaultCategory;
	bool			browserIsJScompatible;

	// Setup
	SetUp();

	// Determine if browser is JS compatible
	browserIsJScompatible = ((GetEnvironment()->GetMozillaLevel() >= 4) && (!GetEnvironment()->IsWebTV()) && (!GetEnvironment()->IsWin16()) && (!GetEnvironment()->IsOpera()));

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change Category"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// If we have an item number, let's get the item
	if (item != 0)
	{
		sprintf(cItem, "%d", item);
		GetAndCheckItem(cItem);

		if (!mpItem)
		{
			*mpStream <<	"<p>"
					  <<	mpMarketPlace->GetFooter();

			CleanUp();

			return;
		}
	}

	*mpStream <<	"<h2>Change the Category of Your Item</h2>\n"


			  <<	"You can use this form to change the category your item is listed under. <br>\n"
					"<br>\n"
					"If the category you've changed to has a different fee structure than the original "
					"one, you'll be charged or credited for the difference. See "
					"<a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/sellerguide/selling-fees.html\">"
					" eBay fees</A>"
					" for details.</b>\n";

	// Link to the same form, but forcing the old style selector
	if (!oldStyle && browserIsJScompatible)
	{
		*mpStream <<    "<p>";
		*mpStream <<	"Note: If you have problems using this page, please try these "
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageChangeCategoryShow)
				  <<	"eBayISAPI.dll?ChangeCategoryShow&oldStyle=1";

		if (item)
			*mpStream	<<	"&item=" << item;

		*mpStream <<	"\">alternate pages</a>.";
	}	
	else
	{
		*mpStream <<	"<p>"
						"<b>Note:</b> Some users (particularly Mac users) have reported problems with this page. If you "
						"experience a problem with this page, please try these <a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"services/buyandsell/change_category1.html\">"
						"alternate</a> pages.";
	}

	// start a form
	*mpStream <<	"<p>\n"
					"<form name=\"ChangeCategoryShow\" method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageChangeCategory)
			  <<	"eBayISAPI.dll?ChangeCategory"
					"\""
					">\n"
			  <<	"<pre>\n"
					"Your "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	":               <input type=text name=userid size=30>\n"
			  <<	"Your "
			  <<	mpMarketPlace->GetPasswordPrompt()
			  <<	":"
					"              <input type=password name=pass size=30>\n"
					"The item number:"
					"            <input type=text name=item size=30";
	
	if (item != 0)
			*mpStream <<	" value="
					  <<	item;

	*mpStream <<	">\n"
					"<br>"
					"</pre>\n";

	// If we have an item number, let's display the current category
	if (item != 0)
	{
		// The Category could contain a number of leading
		// ':' characters, which is a side affect of the query
		// used to retrieve it. Let's skip past them.
		pCategory	= mpItem->GetCategoryName();

		if (pCategory)
		{
			for (;
				 *pCategory == ':';
				 pCategory++)
			{
				;
			}
		}
		else
			pCategory	= "";

		*mpStream <<	"Current Category:"
						"           "
				  <<	pCategory
				  <<	"\n";
	}
/*
	// Now, the new category selection
	*mpStream <<	"New Category:"
					"               ";
*/
	////////////////////////////////////////////////////////////////////////
	if (item != 0)
	{
		// Use javascript version if browser supports it
		if (!oldStyle && browserIsJScompatible)
		{
			mpCategories->EmitHTMLJavascriptCategorySelector(
									mpStream, 
									"ChangeCategoryShow", 
									"", 
									"newcategory", 
									(item ? mpItem->GetCategory() : NULL), 
									true);

		}
		else
		{
			mpCategories->EmitHTMLLeafSelectionList(
									mpStream,
									"newcategory",
									(CategoryId)mpItem->GetCategory(),
									NULL,
									NULL,
									&vCategories, false, true);
		}
	}
	else
	{
		// Use javascript version if browser supports it
		if (!oldStyle && browserIsJScompatible)
		{
			mpCategories->EmitHTMLJavascriptCategorySelector(
									mpStream, 
									"ChangeCategoryShow",
									"", 
									"newcategory",
									(item ? pDefaultCategory->GetId() : NULL), 
									true);

		}
		else	// use old-style category selector
		{
			pDefaultCategory	= mpCategories->GetCategoryDefault(true);

			mpCategories->EmitHTMLLeafSelectionList(
									mpStream,
									"newcategory",
									pDefaultCategory->GetId(),
									NULL,
									NULL,
									&vCategories, false, true);
		}
	}

	///////////////////////////////////////////////////////////////////////
	*mpStream <<	"\n"
					"<p>\n"
					"<font size=+0>"
					"<strong>Press this button to change the category of your listing:</strong>"
					"<blockquote><input type=submit value=\"change category\"></blockquote>"
					"<p>"
					"Press this button to reset the form if you made a mistake:"
					"<blockquote><input type=reset value=\"reset form\"></blockquote>"
					"</form>"
					"<p>";

	*mpStream <<	""
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	vCategories.erase(vCategories.begin(), vCategories.end());

	CleanUp();

	return;

}

