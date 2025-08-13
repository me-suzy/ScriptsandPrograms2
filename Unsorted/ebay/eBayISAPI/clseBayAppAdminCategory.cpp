/*	$Id: clseBayAppAdminCategory.cpp,v 1.8.284.2 1999/08/05 20:41:54 nsacco Exp $	*/
//
//	File:		clsCategoryAdminApp.cc
//
//	Class:		clsCategoryAdminApp
//
//	Author:		Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 09/07/97 tini	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 06/15/99 petra	- wired off links to category administration pages 
//				- 08/05/99 nsacco	- Added html and head tags where needed.
//
#include "ebihdr.h"
bool clseBayApp::CheckCategoryName(char *pName)
{
	bool		error	= false;
	char		*pI;
	bool		foundNonBlank;

	// name is empty?
	if (FIELD_OMITTED(pName))
	{
		*mpStream <<	"<h2>"
						"Empty Name"
						"</h2>"
						"You must enter a name for your category. "
						"Please go back and try again.<p>\n ";

		error	= true;
	}

	// name too long?
	if ( strlen(pName) > 20)
	{
		*mpStream <<	"<h2>"
						"Name too long"
						"</h2>"
						"Category names are limited to 20 characters."
						" Please go back and shorten your name<p>\n";

		error	= true;
	}

	// All blanks?
	foundNonBlank	= false;
	for (pI	= pName;
		 *pI != '\0';
		 pI++)
	{
		if (*pI != ' ')
		{
			foundNonBlank	= true;
			break;
		}
	}

	if (!foundNonBlank)
	{
		*mpStream <<	"<h2>Name blank</h2>"
						"The name you entered was all blank. You must "
						"enter a name for your item. Please go back and try "
						"again."
						"<p>";
		error	= true;
	}
	return error;
}

//
// CheckCategoryData
//
// Common routine to validate category data.
//
bool clseBayApp::CheckCategoryData(	
								char *pName,
								char *pDesc,
								char *pAdult,
								char *pFeaturedCost,
								char *pFileRef,
								char *pCategory,
								char *pAddAction,
								int   feeCurrencyId
							  )
{
	bool		error	= false;
	double		dFeaturedCost;

	error = CheckCategoryName(pName);

		// Bad featured cost price
	if (!FIELD_OMITTED(pFeaturedCost))
	{
		dFeaturedCost	= atof(pFeaturedCost);

		if (dFeaturedCost > mpMarketPlace->GetMaxAmount(feeCurrencyId))
		{
			*mpStream <<	"<h2>Error in featured cost</h2>"
							"The cost to feature an item in the category of\n ";

			clsCurrencyWidget currencyWidget(mpMarketPlace, feeCurrencyId, dFeaturedCost);
			currencyWidget.EmitHTML(mpStream);

			*mpStream <<	" seems to be too large to be legitimate. Please go back "
					  <<	"and check it again.\n"
							"<p>\n";
			error	= true;
		}
	}

	if (FIELD_OMITTED(pAddAction))
	{
		*mpStream <<	"<h2>"
						"Add after or add child?"
						"</h2>\n"
						"You must choose to enter after a category or as child of the category. "
						"Please go back and try again.<p>\n ";

		error	= true;
	}

	return error;	
}

//
// Common routine to get the category (if we can), and report
// an error if we can't...
//
bool clseBayApp::GetAndCheckCategory(char *pCategory)
{
	int		category;

	// Ok, let's get started
	if (pCategory)
	{
		category	= atoi(pCategory);
		mpCategory	= mpCategories->GetCategory(category);
	}

	// If we did't get the item, then put out a 
	// nice error message.
	if (!mpCategory || category == 0)
	{
		*mpStream <<	"<HTML>"
						"<HEAD>"
						"<TITLE>"
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" - Invalid Category"
						"</TITLE>"
						"</HEAD>";

		*mpStream <<	mpMarketPlace->GetHeader();

		*mpStream <<	"<p>"
						"<B>"
						"Category \""
				  <<	pCategory
				  <<	"\" is invalid or could not be found."
						"</B>"
						"<p>"
						"Please go back and try again.";

		*mpStream <<	mpMarketPlace->GetFooter();

		*mpStream <<	flush;
		return false;
	}

	return true;
}

void clseBayApp::CategoryAdminRun(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel)
{
	CategoryVector vCategories;
	
	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// We'll need a title here
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Category Administration"
			  <<	"</TITLE></head>"
			  <<	flush;

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// start the page blurb
	*mpStream <<	"<hr width=50%>\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Category Administration</h2>"
			  <<	"<br>\n";

	// displays all categories (and use cache)
	mpCategories->EmitHTMLCategoriesList(mpStream,
											"category",
											&vCategories, true);


	// Links to administration detail pages, etc;
	// petra wired off links
	*mpStream <<	"<b>Please do not use this page for category administration.</b>\n"
			  <<	"Categories are maintained offline.";
// petra -----------------------------------
//	*mpStream <<	"Follow the links to:"
//					"<br>"
//			  <<	"<A href=\""
//			  <<	mpMarketPlace->GetCGIPath(PageNewCategory)
//			  <<	"eBayISAPI.dll?NewCategory"
//			  <<	"\">"
//					"Add New Category"
//					"</A>"
//					"<br>\n"
//					" <p> "
//					"<A href=\""
//			  <<	mpMarketPlace->GetCGIPath(PageDeleteCategory)
//			  <<	"eBayISAPI.dll?DeleteCategory"
//			  <<	"\">"
//					"Delete Category"
//					"</A>"
//					"<br>\n"
//					" <p> "
//					"<A href=\""
//			  <<	mpMarketPlace->GetCGIPath(PageMoveCategory)
//			  <<	"eBayISAPI.dll?MoveCategory"
//			  <<	"\">"
//					"Move Items in Category"
//					"</A>"
//					"<br>\n"
//					" <p> "
//					"<A href=\""
//			  <<	mpMarketPlace->GetCGIPath(PageViewCategory)
//			  <<	"eBayISAPI.dll?ViewCategory"
//			  <<	"\">"
//					"View and Update Category Details"
//					"</A>"
//					"<br>"
//					"<p>\n"
//			  <<	"<A href=\""
//			  <<	mpMarketPlace->GetCGIPath(PageOrderCategory)
//			  <<	"eBayISAPI.dll?OrderCategory"
//			  <<	"\">"
//					"Reorder Categories"
//					"</A>"
//					"<br>\n"
//					" <p> "; 
//---------------------------------------

	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;
		
	vCategories.erase(vCategories.begin(), vCategories.end());

	CleanUp();
}


void clseBayApp::CategoryChecker(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel)
{
	CategoryVector vCategories;
	CategoryVector::iterator	i;
	const int	maxCatId = 10000;
	clsCategory* categoryArray[maxCatId];
	int j;
	clsCategory *pPrevCategory;
	clsCategory *pNextCategory;
	clsCategory *pThisCategory;
	clsCategory *pLevel1Category;
	clsCategory *pLevel2Category;
	clsCategory *pLevel3Category;
	clsCategory *pLevel4Category;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// We'll need a title here
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Category Integrity Checker"
			  <<	"</TITLE></head>"
			  <<	flush;

	// Let's see if we're allowed to do this
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// start the page blurb
	*mpStream <<	"<hr width=50%>\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<h2 align=center>Category Integrity Checker</h2>"
			  <<	"<br>\n";

	// get all the categories sorted by order_no, and use the cache
	mpCategories->All(&vCategories, true);


	// we've got the vector of all categories, but let's make a parallel array
	//  for fast, random-access lookups by categoryid.
	for (j = 0; j < maxCatId; j++) categoryArray[j] = NULL;
	for (i = vCategories.begin(); i != vCategories.end(); i++)
		categoryArray[(*i)->GetId()] = *i;

	// for each category in the vector, let's do a battery of tests
	for (i = vCategories.begin(); i != vCategories.end(); i++)
	{
		// safety
		if ((*i) == NULL) continue;

		// setup
		pThisCategory = *i;
		pPrevCategory = categoryArray[pThisCategory->GetPrevSibling()];
		pNextCategory = categoryArray[pThisCategory->GetNextSibling()];
		pLevel1Category = categoryArray[pThisCategory->GetLevel1()];
		pLevel2Category = categoryArray[pThisCategory->GetLevel2()];
		pLevel3Category = categoryArray[pThisCategory->GetLevel3()];
		pLevel4Category = categoryArray[pThisCategory->GetLevel4()];

		// test reciprocal sibling pointers
		if (pPrevCategory && (pPrevCategory->GetNextSibling() != pThisCategory->GetId()))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s prevcategory is "
						<<	pPrevCategory->GetName()
						<<	" ("
						<<	pPrevCategory->GetId()
						<<	")"
						<<	", but not vice-versa.\n";
		}
		if (pNextCategory && (pNextCategory->GetPrevSibling() != pThisCategory->GetId()))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s nextcategory is "
						<<	pNextCategory->GetName()
						<<	" ("
						<<	pNextCategory->GetId()
						<<	")"
						<<	", but not vice-versa.\n";
		}

		// test that siblings have exactly the same ancestors
		if (pPrevCategory &&
			((pPrevCategory->GetLevel1() != pThisCategory->GetLevel1()) ||
			(pPrevCategory->GetLevel2() != pThisCategory->GetLevel2()) ||
			(pPrevCategory->GetLevel3() != pThisCategory->GetLevel3()) ||
			(pPrevCategory->GetLevel4() != pThisCategory->GetLevel4())))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	" and "
						<<	pPrevCategory->GetName()
						<<	" ("
						<<	pPrevCategory->GetId()
						<<	")"
						<<	" are supposedly siblings, but they have different ancestors.\n";
		}
		if (pNextCategory &&
			((pNextCategory->GetLevel1() != pThisCategory->GetLevel1()) ||
			(pNextCategory->GetLevel2() != pThisCategory->GetLevel2()) ||
			(pNextCategory->GetLevel3() != pThisCategory->GetLevel3()) ||
			(pNextCategory->GetLevel4() != pThisCategory->GetLevel4())))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	" and "
						<<	pNextCategory->GetName()
						<<	" ("
						<<	pNextCategory->GetId()
						<<	")"
						<<	" are supposedly siblings, but they have different ancestors.\n";
		}

		// test that the level1...level4 categories exist
		if ((!pLevel1Category) && (pThisCategory->GetLevel1()))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s Level1"
						<<	" ("
						<<	pThisCategory->GetLevel1()
						<<	")"
						<<	" doesn't exist as a category.\n";
		}
		if ((!pLevel2Category) && (pThisCategory->GetLevel2()))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s Level2"
						<<	" ("
						<<	pThisCategory->GetLevel2()
						<<	")"
						<<	" doesn't exist as a category.\n";
		}
		if ((!pLevel3Category) && (pThisCategory->GetLevel3()))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s Level3"
						<<	" ("
						<<	pThisCategory->GetLevel3()
						<<	")"
						<<	" doesn't exist as a category.\n";
		}
		if ((!pLevel4Category) && (pThisCategory->GetLevel4()))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s Level4"
						<<	" ("
						<<	pThisCategory->GetLevel4()
						<<	")"
						<<	" doesn't exist as a category.\n";
		}

		// test that level ids match name ids
		if (pLevel1Category &&
			((pThisCategory->GetName1() && pLevel1Category->GetName()) &&
			(strcmp(pThisCategory->GetName1(), categoryArray[pThisCategory->GetLevel1()]->GetName()))))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s Name1 ("
						<<	pThisCategory->GetName1()
						<<	") doesn't match the name of its Level1 ("
						<<	categoryArray[pThisCategory->GetLevel1()]->GetName()
						<<	")";
		}
		if (pLevel2Category &&
			((pThisCategory->GetName2() && pLevel2Category->GetName()) &&
			(strcmp(pThisCategory->GetName2(), categoryArray[pThisCategory->GetLevel2()]->GetName()))))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s Name2 ("
						<<	pThisCategory->GetName2()
						<<	") doesn't match the name of its Level2 ("
						<<	categoryArray[pThisCategory->GetLevel2()]->GetName()
						<<	")";
		}
		if (pLevel3Category &&
			((pThisCategory->GetName3() && pLevel3Category->GetName()) &&
			(strcmp(pThisCategory->GetName3(), categoryArray[pThisCategory->GetLevel3()]->GetName()))))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s Name3 ("
						<<	pThisCategory->GetName3()
						<<	") doesn't match the name of its Level3 ("
						<<	categoryArray[pThisCategory->GetLevel1()]->GetName()
						<<	")";
		}
		if (pLevel4Category &&
			((pThisCategory->GetName4() && pLevel4Category->GetName()) &&
			(strcmp(pThisCategory->GetName4(), categoryArray[pThisCategory->GetLevel4()]->GetName()))))
		{
			*mpStream	<<	"<p>"
						<<	pThisCategory->GetName()
						<<	" ("
						<<	pThisCategory->GetId()
						<<	")"
						<<	"'s Name4 ("
						<<	pThisCategory->GetName4()
						<<	") doesn't match the name of its Level4 ("
						<<	categoryArray[pThisCategory->GetLevel4()]->GetName()
						<<	")";
		}

	}


	*mpStream <<	"<p>\n"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	vCategories.erase(vCategories.begin(), vCategories.end());

	CleanUp();
}

//void clseBayApp::Trace(char *pFormat, ...)
//{
//	// We always do this..
//	va_list args;
//  	va_start(args, pFormat);

//	ISAPITRACE((LPCTSTR)pFormat, args);

//	return;
//}

